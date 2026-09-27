<?php

namespace App\Filament\Pages\Reports;

use App\Enums\HandlingType;
use App\Enums\RehabilitationCaseStatus;
use App\Filament\Exports\RehabilitationCaseExporter;
use App\Models\ClientCategory;
use App\Models\ReferralInstitution;
use App\Models\RehabilitationCase;
use BackedEnum;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\ExportAction;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Page;
use Filament\Schemas\Components\EmbeddedTable;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\StreamedResponse;
use UnitEnum;

class RehabilitationReportPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|UnitEnum|null $navigationGroup = 'Laporan & Statistik';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $navigationLabel = 'Rekap Rehabilitasi Sosial';

    protected static ?string $title = 'Laporan Rekapitulasi Kasus & Rujukan Rehabilitasi Sosial';

    protected static ?int $navigationSort = 3;

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ! $user->isOperator()
            && ! $user->hasRole('warga');
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                EmbeddedTable::make(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                RehabilitationCase::query()
                    ->with(['client.category', 'client.village.district', 'officer', 'referrals.institution'])
            )
            ->defaultSort('received_at', 'desc')
            ->columns([
                TextColumn::make('case_number')
                    ->label('No. Kasus')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('received_at')
                    ->label('Tgl Masuk')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('client.name')
                    ->label('Nama Klien')
                    ->searchable()
                    ->description(fn (RehabilitationCase $record): string => 'NIK: '.($record->client?->nik ?? '-')),

                TextColumn::make('client.category.name')
                    ->label('Kategori PMKS')
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                TextColumn::make('client.village.district.name')
                    ->label('Kecamatan')
                    ->sortable(),

                TextColumn::make('client.village.name')
                    ->label('Desa / Kelurahan')
                    ->sortable(),

                TextColumn::make('handling_type')
                    ->label('Penanganan')
                    ->formatStateUsing(fn ($state) => $state instanceof HandlingType ? $state->label() : (string) $state),

                TextColumn::make('status')
                    ->label('Status Kasus')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof RehabilitationCaseStatus ? $state->label() : (string) $state)
                    ->color(fn ($state): string => $state instanceof RehabilitationCaseStatus ? $state->color() : 'gray'),

                TextColumn::make('referrals_count')
                    ->label('Rujukan Mitra')
                    ->state(fn (RehabilitationCase $record): string => $record->referrals->isNotEmpty()
                        ? $record->referrals->map(fn ($r) => $r->institution?->name ?? 'Mitra')->implode(', ')
                        : 'Penanganan Mandiri'
                    ),
            ])
            ->filters([
                Filter::make('periode')
                    ->label('Periode Masuk Kasus')
                    ->form([
                        DatePicker::make('dari_tanggal')->label('Dari Tanggal'),
                        DatePicker::make('sampai_tanggal')->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['dari_tanggal'], fn (Builder $q, $date) => $q->whereDate('received_at', '>=', $date))
                            ->when($data['sampai_tanggal'], fn (Builder $q, $date) => $q->whereDate('received_at', '<=', $date));
                    }),

                SelectFilter::make('client_category_id')
                    ->label('Kategori PMKS')
                    ->options(fn () => ClientCategory::pluck('name', 'id'))
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'],
                            fn (Builder $q, $catId) => $q->whereHas('client', fn ($cq) => $cq->where('client_category_id', $catId))
                        );
                    }),

                SelectFilter::make('status')
                    ->label('Status Kasus')
                    ->options(collect(RehabilitationCaseStatus::cases())->mapWithKeys(fn ($case) => [$case->value => $case->label()])),

                SelectFilter::make('referral_institution_id')
                    ->label('Lembaga Mitra Rujukan')
                    ->options(fn () => ReferralInstitution::where('is_active', true)->pluck('name', 'id'))
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'],
                            fn (Builder $q, $instId) => $q->whereHas('referrals', fn ($rq) => $rq->where('referral_institution_id', $instId))
                        );
                    }),
            ])
            ->headerActions([
                ExportAction::make('exportExcel')
                    ->label('Ekspor Excel')
                    ->icon(Heroicon::ArrowDownTray)
                    ->color('success')
                    ->exporter(RehabilitationCaseExporter::class),

                Action::make('exportPdf')
                    ->label('Ekspor PDF')
                    ->icon(Heroicon::DocumentArrowDown)
                    ->color('danger')
                    ->action(fn (): StreamedResponse => $this->exportPdf()),
            ]);
    }

    public function exportPdf(): StreamedResponse
    {
        $records = $this->getFilteredTableQuery()->get();

        $headers = [
            'No. Kasus',
            'Tgl Masuk',
            'Nama Klien',
            'Kategori PMKS',
            'Wilayah (Kec/Desa)',
            'Penanganan',
            'Status',
            'Lembaga Rujukan',
        ];

        $rows = $records->map(fn (RehabilitationCase $item): array => [
            $item->case_number ?? '-',
            $item->received_at?->format('d/m/Y') ?? '-',
            ($item->client?->name ?? '-').' (NIK: '.($item->client?->nik ?? '-').')',
            $item->client?->category?->name ?? '-',
            ($item->client?->village?->district?->name ?? '-').' / '.($item->client?->village?->name ?? '-'),
            $item->handling_type instanceof HandlingType ? $item->handling_type->label() : (string) $item->handling_type,
            $item->status instanceof RehabilitationCaseStatus ? $item->status->label() : (string) $item->status,
            $item->referrals->isNotEmpty()
                ? $item->referrals->map(fn ($r) => $r->institution?->name ?? 'Mitra')->implode(', ')
                : 'Mandiri / Keluarga',
        ])->toArray();

        $totalCases = count($records);
        $totalReferrals = $records->sum(fn ($c) => $c->referrals->count());
        $closedCases = $records->where('status', RehabilitationCaseStatus::CLOSED)->count();

        $summaryText = "Total kasus rehabilitasi sosial dalam laporan ini sebanyak {$totalCases} kasus ({$closedCases} kasus telah selesai/ditutup), dengan keterlibatan {$totalReferrals} rujukan ke lembaga/balai sosial mitra.";

        $pdf = Pdf::loadView('reports.pdf-template', [
            'title' => 'Laporan Rekapitulasi Pelayanan Rehabilitasi Sosial',
            'subtitle' => 'Data Penanganan PPKS, Asesmen, dan Rujukan Lembaga Mitra',
            'meta' => [
                'Total Kasus Terdaftar' => "{$totalCases} Kasus",
                'Total Rujukan Mitra' => "{$totalReferrals} Rujukan",
            ],
            'summaryText' => $summaryText,
            'headers' => $headers,
            'rows' => $rows,
        ]);

        return response()->streamDownload(
            fn () => print ($pdf->output()),
            'Laporan_Rekap_Rehabilitasi_'.now()->format('Ymd_His').'.pdf',
            ['Content-Type' => 'application/pdf']
        );
    }
}
