<?php

namespace App\Filament\Exports;

use App\Enums\RehabilitationCaseStatus;
use App\Models\RehabilitationCase;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class RehabilitationCaseExporter extends Exporter
{
    protected static ?string $model = RehabilitationCase::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('case_number')
                ->label('No. Kasus'),
            ExportColumn::make('received_at')
                ->label('Tanggal Masuk'),
            ExportColumn::make('client.name')
                ->label('Nama Klien'),
            ExportColumn::make('client.nik')
                ->label('NIK Klien'),
            ExportColumn::make('client.category.name')
                ->label('Kategori PMKS'),
            ExportColumn::make('client.village.district.name')
                ->label('Kecamatan'),
            ExportColumn::make('client.village.name')
                ->label('Desa / Kelurahan'),
            ExportColumn::make('handling_type')
                ->label('Jenis Penanganan'),
            ExportColumn::make('status')
                ->label('Status Kasus')
                ->formatStateUsing(fn ($state) => $state instanceof RehabilitationCaseStatus ? $state->label() : (string) $state),
            ExportColumn::make('officer.name')
                ->label('Petugas Pendamping'),
            ExportColumn::make('handling_result')
                ->label('Hasil Penanganan'),
            ExportColumn::make('closed_at')
                ->label('Tanggal Ditutup'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Ekspor data kasus rehabilitasi selesai dan '.number_format($export->successful_rows).' baris berhasil diekspor.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' baris gagal diekspor.';
        }

        return $body;
    }
}
