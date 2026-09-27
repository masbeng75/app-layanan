<?php

namespace App\Filament\Pages\Reports;

use App\Enums\PbiReason;
use App\Enums\ServiceRequestStatus;
use App\Filament\Exports\PbiReactivationExporter;
use App\Models\District;
use App\Models\PbiReactivation;
use BackedEnum;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
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

class PbiReportPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|UnitEnum|null $navigationGroup = 'Laporan & Statistik';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHeart;

    protected static ?string $navigationLabel = 'Rekap Reaktivasi PBI-JK';

    protected static ?string $title = 'Laporan Rekapitulasi Reaktivasi PBI-JK';

    protected static ?int $navigationSort = 2;

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
                PbiReactivation::query()
                    ->with(['serviceRequest.village.district', 'signer'])
            )
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('serviceRequest.request_number')
                    ->label('No. Pengajuan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('serviceRequest.submitted_at')
                    ->label('Tgl Masuk')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('participant_name')
                    ->label('Nama Peserta / Pasien')
                    ->searchable()
                    ->description(fn (PbiReactivation $record): string => 'NIK: '.($record->participant_nik ?? '-')),

                TextColumn::make('bpjs_card_number')
                    ->label('No. BPJS/KIS')
                    ->searchable(),

                TextColumn::make('reason')
                    ->label('Alasan')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof PbiReason ? $state->label() : (string) $state)
                    ->color('info'),

                TextColumn::make('health_facility_name')
                    ->label('Faskes / RS')
                    ->placeholder('Tidak Disebutkan'),

                TextColumn::make('serviceRequest.status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof ServiceRequestStatus ? $state->label() : (string) $state)
                    ->color(fn ($state): string => $state instanceof ServiceRequestStatus ? $state->color() : 'gray'),

                TextColumn::make('serviceRequest.village.district.name')
                    ->label('Kecamatan')
                    ->sortable(),

                TextColumn::make('processing_duration')
                    ->label('Lama Proses')
                    ->state(function (PbiReactivation $record): string {
                        $submitted = $record->serviceRequest?->submitted_at;
                        if (! $submitted) {
                            return '-';
                        }
                        $end = $record->reactivated_date
                            ? Carbon::parse($record->reactivated_date)
                            : ($record->serviceRequest?->completed_at ?? now());

                        $days = (int) $submitted->diffInDays($end);

                        return "{$days} hari";
                    }),
            ])
            ->filters([
                Filter::make('periode')
                    ->label('Periode Pengajuan')
                    ->form([
                        DatePicker::make('dari_tanggal')
                            ->label('Dari Tanggal'),
                        DatePicker::make('sampai_tanggal')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['dari_tanggal'], fn (Builder $q, $date) => $q->whereHas('serviceRequest', fn ($sq) => $sq->whereDate('submitted_at', '>=', $date)))
                            ->when($data['sampai_tanggal'], fn (Builder $q, $date) => $q->whereHas('serviceRequest', fn ($sq) => $sq->whereDate('submitted_at', '<=', $date)));
                    }),

                SelectFilter::make('reason')
                    ->label('Alasan Reaktivasi')
                    ->options(collect(PbiReason::cases())->mapWithKeys(fn ($case) => [$case->value => $case->label()])),

                SelectFilter::make('status')
                    ->label('Status Tiket')
                    ->options(collect(ServiceRequestStatus::cases())->mapWithKeys(fn ($case) => [$case->value => $case->getLabel()]))
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'],
                            fn (Builder $q, $status) => $q->whereHas('serviceRequest', fn ($sq) => $sq->where('status', $status))
                        );
                    }),

                SelectFilter::make('district_id')
                    ->label('Kecamatan')
                    ->options(fn () => District::orderBy('name')->pluck('name', 'id'))
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'],
                            fn (Builder $q, $districtId) => $q->whereHas('serviceRequest.village', fn ($vq) => $vq->where('district_id', $districtId))
                        );
                    }),
            ])
            ->headerActions([
                ExportAction::make('exportExcel')
                    ->label('Ekspor Excel')
                    ->icon(Heroicon::ArrowDownTray)
                    ->color('success')
                    ->exporter(PbiReactivationExporter::class),

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
            'No. Pengajuan',
            'Tgl Masuk',
            'Nama Pasien',
            'No. BPJS/KIS',
            'Alasan',
            'Faskes / RS',
            'Status',
            'Kecamatan',
            'Lama Proses',
        ];

        $totalDuration = 0;
        $countWithDuration = 0;

        $rows = $records->map(function (PbiReactivation $item) use (&$totalDuration, &$countWithDuration): array {
            $submitted = $item->serviceRequest?->submitted_at;
            $durationStr = '-';
            if ($submitted) {
                $end = $item->reactivated_date
                    ? Carbon::parse($item->reactivated_date)
                    : ($item->serviceRequest?->completed_at ?? now());
                $days = (int) $submitted->diffInDays($end);
                $durationStr = "{$days} hari";
                $totalDuration += $days;
                $countWithDuration++;
            }

            $reasonLabel = $item->reason instanceof PbiReason ? $item->reason->label() : (string) $item->reason;
            $statusLabel = $item->serviceRequest?->status instanceof ServiceRequestStatus
                ? $item->serviceRequest->status->label()
                : (string) ($item->serviceRequest?->status ?? '-');

            return [
                $item->serviceRequest?->request_number ?? '-',
                $submitted?->format('d/m/Y') ?? '-',
                ($item->participant_name ?? '-').' (NIK: '.($item->participant_nik ?? '-').')',
                $item->bpjs_card_number ?? '-',
                $reasonLabel,
                $item->health_facility_name ?? '-',
                $statusLabel,
                $item->serviceRequest?->village?->district?->name ?? '-',
                $durationStr,
            ];
        })->toArray();

        $avgDays = $countWithDuration > 0 ? round($totalDuration / $countWithDuration, 1) : 0;
        $totalCount = count($records);
        $activeCount = $records->filter(fn ($r) => in_array($r->serviceRequest?->status, [ServiceRequestStatus::REACTIVATED, ServiceRequestStatus::MINISTRY_APPROVED]))->count();

        $summaryText = "Total permohonan reaktivasi pada laporan ini berjumlah {$totalCount} berkas, dengan {$activeCount} berkas telah aktif kembali. Rata-rata lama penanganan/proses adalah {$avgDays} hari kalender.";

        $pdf = Pdf::loadView('reports.pdf-template', [
            'title' => 'Laporan Rekapitulasi Reaktivasi PBI-JK',
            'subtitle' => 'Data Fasilitasi Reaktivasi Peserta JKN-KIS PBI Menonaktifkan ke Kemensos RI',
            'meta' => [
                'Total Pengajuan' => "{$totalCount} Berkas",
                'Rata-rata Waktu Proses' => "{$avgDays} Hari",
            ],
            'summaryText' => $summaryText,
            'headers' => $headers,
            'rows' => $rows,
        ]);

        return response()->streamDownload(
            fn () => print ($pdf->output()),
            'Laporan_Rekap_PBI_'.now()->format('Ymd_His').'.pdf',
            ['Content-Type' => 'application/pdf']
        );
    }
}
