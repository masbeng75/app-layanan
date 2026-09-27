<?php

namespace App\Filament\Exports;

use App\Models\Client;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ClientExporter extends Exporter
{
    protected static ?string $model = Client::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('name')
                ->label('Nama Klien'),
            ExportColumn::make('nik')
                ->label('NIK Klien'),
            ExportColumn::make('birth_date')
                ->label('Tanggal Lahir'),
            ExportColumn::make('gender')
                ->label('Jenis Kelamin')
                ->formatStateUsing(fn ($state) => $state === 'male' ? 'Laki-laki' : ($state === 'female' ? 'Perempuan' : (string) $state)),
            ExportColumn::make('category.name')
                ->label('Kategori PMKS'),
            ExportColumn::make('address')
                ->label('Alamat'),
            ExportColumn::make('village.district.name')
                ->label('Kecamatan'),
            ExportColumn::make('village.name')
                ->label('Desa / Kelurahan'),
            ExportColumn::make('phone')
                ->label('Nomor Telepon'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Ekspor data klien sosial selesai dan '.number_format($export->successful_rows).' baris berhasil diekspor.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' baris gagal diekspor.';
        }

        return $body;
    }
}
