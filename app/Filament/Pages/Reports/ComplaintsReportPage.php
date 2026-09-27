<?php

namespace App\Filament\Pages\Reports;

use App\Enums\ComplaintStatus;
use App\Filament\Exports\ComplaintExporter;
use App\Models\Complaint;
use App\Models\ComplaintCategory;
use App\Models\District;
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

class ComplaintsReportPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|UnitEnum|null $navigationGroup = 'Laporan & Statistik';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMegaphone;

    protected static ?string $navigationLabel = 'Rekap Pengaduan';

    protected static ?string $title = 'Laporan Rekapitulasi Pengaduan Masyarakat';

    protected static ?int $navigationSort = 5;

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
                Complaint::query()
                    ->with(['category', 'village.district', 'officer'])
            )
            ->defaultSort('reported_at', 'desc')
            ->columns([
                TextColumn::make('complaint_number')
                    ->label('No. Pengaduan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('reported_at')
                    ->label('Tgl Masuk')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('reporter_name')
                    ->label('Nama Pelapor')
                    ->searchable()
                    ->description(fn (Complaint $record): string => 'HP: '.($record->reporter_phone ?? '-')),

                TextColumn::make('category.name')
                    ->label('Kategori Pengaduan')
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
                    ->label('Status Laporan')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof ComplaintStatus ? $state->label() : (string) $state)
                    ->color(fn ($state): string => $state instanceof ComplaintStatus ? $state->color() : 'gray')
                    ->sortable(),

                TextColumn::make('officer.name')
                    ->label('Petugas Lapangan')
                    ->placeholder('Belum Didisposisi'),

                TextColumn::make('resolved_at')
                    ->label('Tgl Selesai Ditangani')
                    ->date('d/m/Y')
                    ->placeholder('-'),
            ])
            ->filters([
                Filter::make('periode')
                    ->label('Periode Laporan Masuk')
                    ->form([
                        DatePicker::make('dari_tanggal')->label('Dari Tanggal'),
                        DatePicker::make('sampai_tanggal')->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['dari_tanggal'], fn (Builder $q, $date) => $q->whereDate('reported_at', '>=', $date))
                            ->when($data['sampai_tanggal'], fn (Builder $q, $date) => $q->whereDate('reported_at', '<=', $date));
                    }),

                SelectFilter::make('complaint_category_id')
                    ->label('Kategori Pengaduan')
                    ->options(fn () => ComplaintCategory::where('is_active', true)->pluck('name', 'id')),

                SelectFilter::make('status')
                    ->label('Status Pengaduan')
                    ->options(collect(ComplaintStatus::cases())->mapWithKeys(fn ($case) => [$case->value => $case->getLabel()])),

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
                    ->exporter(ComplaintExporter::class),

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
            'No. Pengaduan',
            'Tgl Masuk',
            'Nama Pelapor',
            'Kategori Pengaduan',
            'Kecamatan',
            'Desa / Kelurahan',
            'Status',
            'Petugas Penangan',
            'Tgl Selesai',
        ];

        $rows = $records->map(fn (Complaint $item): array => [
            $item->complaint_number ?? '-',
            $item->reported_at?->format('d/m/Y') ?? '-',
            $item->reporter_name ?? '-',
            $item->category?->name ?? '-',
            $item->village?->district?->name ?? '-',
            $item->village?->name ?? '-',
            $item->status instanceof ComplaintStatus ? $item->status->label() : (string) $item->status,
            $item->officer?->name ?? 'Belum Didisposisi',
            $item->resolved_at?->format('d/m/Y') ?? '-',
        ])->toArray();

        $totalComplaints = count($records);
        $resolvedComplaints = $records->where('status', ComplaintStatus::RESOLVED)->count();
        $inHandling = $records->whereIn('status', [ComplaintStatus::DISPATCHED, ComplaintStatus::IN_HANDLING, ComplaintStatus::VERIFICATION])->count();

        $summaryText = "Total laporan pengaduan masuk sebanyak {$totalComplaints} laporan. {$resolvedComplaints} laporan telah selesai ditangani, {$inHandling} laporan sedang dalam proses verifikasi atau penanganan lapangan.";

        $pdf = Pdf::loadView('reports.pdf-template', [
            'title' => 'Laporan Rekapitulasi Pengaduan Masyarakat Terpadu',
            'subtitle' => 'Data Pengaduan dan Aspirasi Masalah Kesejahteraan Sosial Kabupaten Blitar',
            'meta' => [
                'Total Pengaduan' => "{$totalComplaints} Laporan",
                'Selesai Ditangani' => "{$resolvedComplaints} Laporan",
            ],
            'summaryText' => $summaryText,
            'headers' => $headers,
            'rows' => $rows,
        ]);

        return response()->streamDownload(
            fn () => print ($pdf->output()),
            'Laporan_Rekap_Pengaduan_'.now()->format('Ymd_His').'.pdf',
            ['Content-Type' => 'application/pdf']
        );
    }
}
