<?php

namespace App\Filament\Exports;

use App\Enums\ServiceRequestStatus;
use App\Models\ServiceRequest;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ServiceRequestExporter extends Exporter
{
    protected static ?string $model = ServiceRequest::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('request_number')
                ->label('No. Pengajuan'),
            ExportColumn::make('applicant_name')
                ->label('Nama Pemohon'),
            ExportColumn::make('applicant_nik')
                ->label('NIK Pemohon'),
            ExportColumn::make('serviceType.name')
                ->label('Jenis Layanan'),
            ExportColumn::make('village.district.name')
                ->label('Kecamatan'),
            ExportColumn::make('village.name')
                ->label('Desa / Kelurahan'),
            ExportColumn::make('status')
                ->label('Status')
                ->formatStateUsing(fn ($state) => $state instanceof ServiceRequestStatus ? $state->label() : (string) $state),
            ExportColumn::make('officer.name')
                ->label('Petugas Penanganan'),
            ExportColumn::make('submitted_at')
                ->label('Tanggal Masuk'),
            ExportColumn::make('completed_at')
                ->label('Tanggal Selesai'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Ekspor data pengajuan layanan selesai dan '.number_format($export->successful_rows).' baris berhasil diekspor.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' baris gagal diekspor.';
        }

        return $body;
    }
}
