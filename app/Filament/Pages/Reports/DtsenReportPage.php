<?php

namespace App\Filament\Pages\Reports;

use App\Filament\Exports\DtsenCertificateExporter;
use App\Models\District;
use App\Models\DtsenCertificate;
use App\Models\DtsenPurpose;
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

class DtsenReportPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|UnitEnum|null $navigationGroup = 'Laporan & Statistik';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $navigationLabel = 'Rekap SK DTSEN';

    protected static ?string $title = 'Laporan Rekapitulasi SK DTSEN';

    protected static ?int $navigationSort = 1;

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
                DtsenCertificate::query()
                    ->with(['serviceRequest.village.district', 'purpose', 'signer'])
                    ->whereNotNull('issued_at')
            )
            ->defaultSort('issued_at', 'desc')
            ->columns([
                TextColumn::make('certificate_number')
                    ->label('Nomor SK DTSEN')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('issued_at')
                    ->label('Tgl Terbit')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('serviceRequest.applicant_name')
                    ->label('Pemohon')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('subject_name')
                    ->label('Nama Subjek')
                    ->searchable()
                    ->description(fn (DtsenCertificate $record): string => 'NIK: '.($record->subject_nik ?? '-')),

                TextColumn::make('purpose.name')
                    ->label('Tujuan Penggunaan')
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                TextColumn::make('decile')
                    ->label('Desil')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ? "Desil {$state}" : '-')
                    ->color(fn ($state) => match (true) {
                        $state <= 3 => 'success',
                        $state <= 7 => 'warning',
                        default => 'danger',
                    })
                    ->sortable(),

                TextColumn::make('serviceRequest.village.district.name')
                    ->label('Kecamatan')
                    ->sortable(),

                TextColumn::make('serviceRequest.village.name')
                    ->label('Desa / Kelurahan')
                    ->sortable(),

                TextColumn::make('valid_until')
                    ->label('Berlaku Sampai')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                Filter::make('periode')
                    ->label('Periode Terbit')
                    ->form([
                        DatePicker::make('dari_tanggal')
                            ->label('Dari Tanggal'),
                        DatePicker::make('sampai_tanggal')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['dari_tanggal'], fn (Builder $q, $date) => $q->whereDate('issued_at', '>=', $date))
                            ->when($data['sampai_tanggal'], fn (Builder $q, $date) => $q->whereDate('issued_at', '<=', $date));
                    }),

                SelectFilter::make('dtsen_purpose_id')
                    ->label('Tujuan Penggunaan')
                    ->options(fn () => DtsenPurpose::where('is_active', true)->pluck('name', 'id')),

                SelectFilter::make('district_id')
                    ->label('Kecamatan')
                    ->options(fn () => District::orderBy('name')->pluck('name', 'id'))
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'],
                            fn (Builder $q, $districtId) => $q->whereHas('serviceRequest.village', fn ($vq) => $vq->where('district_id', $districtId))
                        );
                    }),

                SelectFilter::make('decile')
                    ->label('Kelompok Desil')
                    ->options([
                        '1-3' => 'Desil 1–3 (Sangat Miskin / Miskin)',
                        '4-7' => 'Desil 4–7 (Rentan Miskin)',
                        '8-10' => 'Desil 8–10 (Mampu)',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return match ($data['value'] ?? null) {
                            '1-3' => $query->whereBetween('decile', [1, 3]),
                            '4-7' => $query->whereBetween('decile', [4, 7]),
                            '8-10' => $query->where('decile', '>=', 8),
                            default => $query,
                        };
                    }),
            ])
            ->headerActions([
                ExportAction::make('exportExcel')
                    ->label('Ekspor Excel')
                    ->icon(Heroicon::ArrowDownTray)
                    ->color('success')
                    ->exporter(DtsenCertificateExporter::class),

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
            'No. SK DTSEN',
            'Tgl Terbit',
            'Pemohon',
            'Subjek Diterangkan',
            'Tujuan Penggunaan',
            'Desil',
            'Kecamatan',
            'Desa / Kelurahan',
            'Masa Berlaku',
        ];

        $rows = $records->map(fn (DtsenCertificate $item): array => [
            $item->certificate_number ?? '-',
            $item->issued_at?->format('d/m/Y') ?? '-',
            $item->serviceRequest?->applicant_name ?? '-',
            ($item->subject_name ?? '-').' (NIK: '.($item->subject_nik ?? '-').')',
            $item->purpose?->name ?? '-',
            $item->decile ? "Desil {$item->decile}" : '-',
            $item->serviceRequest?->village?->district?->name ?? '-',
            $item->serviceRequest?->village?->name ?? '-',
            $item->valid_until?->format('d/m/Y') ?? '-',
        ])->toArray();

        $totalIssued = count($records);
        $desilRendah = $records->whereBetween('decile', [1, 3])->count();
        $desilMenengah = $records->whereBetween('decile', [4, 7])->count();
        $desilTinggi = $records->where('decile', '>=', 8)->count();

        $summaryText = "Total penerbitan SK DTSEN pada laporan ini berjumlah {$totalIssued} surat, dengan rincian Desil 1–3: {$desilRendah} surat, Desil 4–7: {$desilMenengah} surat, dan Desil 8+: {$desilTinggi} surat.";

        $pdf = Pdf::loadView('reports.pdf-template', [
            'title' => 'Laporan Rekapitulasi Penerbitan Surat Keterangan DTSEN',
            'subtitle' => 'Data Penerbitan SK Terdaftar pada Aplikasi SAPA SOSIAL',
            'meta' => [
                'Total Data Terbit' => "{$totalIssued} Berkas",
                'Kriteria Cetak' => 'Berdasarkan filter aktif tabel laporan',
            ],
            'summaryText' => $summaryText,
            'headers' => $headers,
            'rows' => $rows,
        ]);

        return response()->streamDownload(
            fn () => print ($pdf->output()),
            'Laporan_Rekap_SK_DTSEN_'.now()->format('Ymd_His').'.pdf',
            ['Content-Type' => 'application/pdf']
        );
    }
}
