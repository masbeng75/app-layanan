<?php

namespace App\Filament\Pages\Reports;

use App\Enums\ServiceRequestStatus;
use App\Filament\Exports\ServiceRequestExporter;
use App\Models\District;
use App\Models\ServiceRequest;
use App\Models\ServiceType;
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

class ServiceRequestsReportPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|UnitEnum|null $navigationGroup = 'Laporan & Statistik';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static ?string $navigationLabel = 'Rekap Pelayanan Sosial';

    protected static ?string $title = 'Laporan Rekapitulasi Seluruh Pengajuan Pelayanan';

    protected static ?int $navigationSort = 4;

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
                ServiceRequest::query()
                    ->with(['serviceType', 'village.district', 'officer'])
            )
            ->defaultSort('submitted_at', 'desc')
            ->columns([
                TextColumn::make('request_number')
                    ->label('No. Pengajuan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('submitted_at')
                    ->label('Tgl Masuk')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('applicant_name')
                    ->label('Nama Pemohon')
                    ->searchable()
                    ->description(fn (ServiceRequest $record): string => 'NIK: '.($record->applicant_nik ?? '-')),

                TextColumn::make('serviceType.name')
                    ->label('Jenis Layanan')
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                TextColumn::make('village.district.name')
                    ->label('Kecamatan')
                    ->sortable(),

                TextColumn::make('village.name')
                    ->label('Desa / Kelurahan')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof ServiceRequestStatus ? $state->label() : (string) $state)
                    ->color(fn ($state): string => $state instanceof ServiceRequestStatus ? $state->color() : 'gray')
                    ->sortable(),

                TextColumn::make('officer.name')
                    ->label('Petugas')
                    ->placeholder('Belum Ditugaskan'),

                TextColumn::make('completed_at')
                    ->label('Tgl Selesai')
                    ->date('d/m/Y')
                    ->placeholder('-'),
            ])
            ->filters([
                Filter::make('periode')
                    ->label('Periode Pengajuan')
                    ->form([
                        DatePicker::make('dari_tanggal')->label('Dari Tanggal'),
                        DatePicker::make('sampai_tanggal')->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['dari_tanggal'], fn (Builder $q, $date) => $q->whereDate('submitted_at', '>=', $date))
                            ->when($data['sampai_tanggal'], fn (Builder $q, $date) => $q->whereDate('submitted_at', '<=', $date));
                    }),

                SelectFilter::make('service_type_id')
                    ->label('Jenis Layanan')
                    ->options(fn () => ServiceType::where('is_active', true)->pluck('name', 'id')),

                SelectFilter::make('status')
                    ->label('Status Pengajuan')
                    ->options(collect(ServiceRequestStatus::cases())->mapWithKeys(fn ($case) => [$case->value => $case->getLabel()])),

                SelectFilter::make('district_id')
                    ->label('Kecamatan')
                    ->options(fn () => District::orderBy('name')->pluck('name', 'id'))
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'],
                            fn (Builder $q, $districtId) => $q->whereHas('village', fn ($vq) => $vq->where('district_id', $districtId))
                        );
                    }),
            ])
            ->headerActions([
                ExportAction::make('exportExcel')
                    ->label('Ekspor Excel')
                    ->icon(Heroicon::ArrowDownTray)
                    ->color('success')
                    ->exporter(ServiceRequestExporter::class),

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
            'Nama Pemohon',
            'Jenis Layanan',
            'Kecamatan',
            'Desa / Kelurahan',
            'Status',
            'Petugas',
            'Tgl Selesai',
        ];

        $rows = $records->map(fn (ServiceRequest $item): array => [
            $item->request_number ?? '-',
            $item->submitted_at?->format('d/m/Y') ?? '-',
            ($item->applicant_name ?? '-').' (NIK: '.($item->applicant_nik ?? '-').')',
            $item->serviceType?->name ?? '-',
            $item->village?->district?->name ?? '-',
            $item->village?->name ?? '-',
            $item->status instanceof ServiceRequestStatus ? $item->status->label() : (string) $item->status,
            $item->officer?->name ?? 'Belum Ditugaskan',
            $item->completed_at?->format('d/m/Y') ?? '-',
        ])->toArray();

        $totalRequests = count($records);
        $completedRequests = $records->whereIn('status', [ServiceRequestStatus::ISSUED, ServiceRequestStatus::COMPLETED, ServiceRequestStatus::REACTIVATED])->count();
        $inProcessRequests = $totalRequests - $completedRequests - $records->where('status', ServiceRequestStatus::REJECTED)->count();

        $summaryText = "Total pelayanan yang tercakup dalam laporan ini sebanyak {$totalRequests} permohonan. Rincian status: {$completedRequests} permohonan selesai/terbit, {$inProcessRequests} sedang dalam proses verifikasi/persetujuan.";

        $pdf = Pdf::loadView('reports.pdf-template', [
            'title' => 'Laporan Rekapitulasi Pelayanan Sosial Terpadu',
            'subtitle' => 'Data Seluruh Jenis Pengajuan Layanan Masyarakat pada SAPA SOSIAL',
            'meta' => [
                'Total Pengajuan' => "{$totalRequests} Berkas",
                'Permohonan Selesai' => "{$completedRequests} Berkas",
            ],
            'summaryText' => $summaryText,
            'headers' => $headers,
            'rows' => $rows,
        ]);

        return response()->streamDownload(
            fn () => print ($pdf->output()),
            'Laporan_Rekap_Pelayanan_'.now()->format('Ymd_His').'.pdf',
            ['Content-Type' => 'application/pdf']
        );
    }
}
