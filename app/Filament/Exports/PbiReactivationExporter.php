<?php

namespace App\Filament\Exports;

use App\Enums\PbiReason;
use App\Enums\ServiceRequestStatus;
use App\Models\PbiReactivation;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class PbiReactivationExporter extends Exporter
{
    protected static ?string $model = PbiReactivation::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('serviceRequest.request_number')
                ->label('No. Pengajuan'),
            ExportColumn::make('serviceRequest.submitted_at')
                ->label('Tanggal Masuk'),
            ExportColumn::make('participant_name')
                ->label('Nama Peserta'),
            ExportColumn::make('participant_nik')
                ->label('NIK Peserta'),
            ExportColumn::make('bpjs_card_number')
                ->label('Nomor BPJS/KIS'),
            ExportColumn::make('reason')
                ->label('Alasan Reaktivasi')
                ->formatStateUsing(fn ($state) => $state instanceof PbiReason ? $state->label() : (string) $state),
            ExportColumn::make('health_facility_name')
                ->label('Faskes / RS'),
            ExportColumn::make('serviceRequest.village.district.name')
                ->label('Kecamatan'),
            ExportColumn::make('serviceRequest.village.name')
                ->label('Desa / Kelurahan'),
            ExportColumn::make('serviceRequest.status')
                ->label('Status')
                ->formatStateUsing(fn ($state) => $state instanceof ServiceRequestStatus ? $state->label() : (string) $state),
            ExportColumn::make('recommendation_number')
                ->label('No. Rekomendasi'),
            ExportColumn::make('proposed_to_ministry_at')
                ->label('Diusulkan ke Kemensos'),
            ExportColumn::make('reactivated_date')
                ->label('Tgl Aktif Kembali'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Ekspor data reaktivasi PBI-JK selesai dan '.number_format($export->successful_rows).' baris berhasil diekspor.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' baris gagal diekspor.';
        }

        return $body;
    }
}
