<?php

namespace App\Filament\Exports;

use App\Enums\ComplaintStatus;
use App\Models\Complaint;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ComplaintExporter extends Exporter
{
    protected static ?string $model = Complaint::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('complaint_number')
                ->label('No. Pengaduan'),
            ExportColumn::make('reported_at')
                ->label('Tanggal Laporan'),
            ExportColumn::make('reporter_name')
                ->label('Nama Pelapor'),
            ExportColumn::make('reporter_phone')
                ->label('Nomor Telepon'),
            ExportColumn::make('category.name')
                ->label('Kategori Pengaduan'),
            ExportColumn::make('village.district.name')
                ->label('Kecamatan'),
            ExportColumn::make('village.name')
                ->label('Desa / Kelurahan'),
            ExportColumn::make('location_detail')
                ->label('Lokasi Detail'),
            ExportColumn::make('status')
                ->label('Status')
                ->formatStateUsing(fn ($state) => $state instanceof ComplaintStatus ? $state->label() : (string) $state),
            ExportColumn::make('officer.name')
                ->label('Petugas Penanganan'),
            ExportColumn::make('resolved_at')
                ->label('Tanggal Selesai'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Ekspor data pengaduan masyarakat selesai dan '.number_format($export->successful_rows).' baris berhasil diekspor.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' baris gagal diekspor.';
        }

        return $body;
    }
}
