<?php

namespace App\Filament\Exports;

use App\Models\DtsenCertificate;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class DtsenCertificateExporter extends Exporter
{
    protected static ?string $model = DtsenCertificate::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('certificate_number')
                ->label('Nomor SK DTSEN'),
            ExportColumn::make('issued_at')
                ->label('Tanggal Terbit'),
            ExportColumn::make('serviceRequest.applicant_name')
                ->label('Nama Pemohon'),
            ExportColumn::make('serviceRequest.applicant_nik')
                ->label('NIK Pemohon'),
            ExportColumn::make('subject_name')
                ->label('Nama Subjek'),
            ExportColumn::make('subject_nik')
                ->label('NIK Subjek'),
            ExportColumn::make('relationship_to_applicant')
                ->label('Hubungan'),
            ExportColumn::make('purpose.name')
                ->label('Tujuan Penggunaan'),
            ExportColumn::make('decile')
                ->label('Peringkat Desil')
                ->formatStateUsing(fn ($state) => $state ? "Desil {$state}" : '-'),
            ExportColumn::make('serviceRequest.village.district.name')
                ->label('Kecamatan'),
            ExportColumn::make('serviceRequest.village.name')
                ->label('Desa / Kelurahan'),
            ExportColumn::make('valid_until')
                ->label('Berlaku Sampai'),
            ExportColumn::make('verification_code')
                ->label('Kode Verifikasi'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Ekspor data rekap SK DTSEN selesai dan '.number_format($export->successful_rows).' baris berhasil diekspor.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' baris gagal diekspor.';
        }

        return $body;
    }
}
