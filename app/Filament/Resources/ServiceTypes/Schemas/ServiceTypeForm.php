<?php

namespace App\Filament\Resources\ServiceTypes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ServiceTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label('Kode Layanan')
                    ->required()
                    ->maxLength(50)
                    ->unique(ignoreRecord: true),
                TextInput::make('name')
                    ->label('Nama Layanan')
                    ->required()
                    ->maxLength(255),
                TextInput::make('category')
                    ->label('Kategori / Bidang')
                    ->required()
                    ->maxLength(255),
                Select::make('handler')
                    ->label('Tipe Pemrosesan (Handler)')
                    ->options([
                        'dtsen' => 'DTSEN (Surat Keterangan Desil)',
                        'pbi' => 'PBI (Reaktivasi BPJS/KIS)',
                        'generic' => 'Generik / Layanan Sosial Lainnya',
                    ])
                    ->required(),
                TextInput::make('sla_days')
                    ->label('SLA Penyelesaian (Hari Kerja)')
                    ->numeric()
                    ->default(3)
                    ->required(),
                Toggle::make('needs_assessment')
                    ->label('Memerlukan Asesmen Lapangan')
                    ->default(false),
                Toggle::make('is_active')
                    ->label('Status Aktif')
                    ->default(true),
                Textarea::make('description')
                    ->label('Deskripsi Layanan')
                    ->columnSpanFull()
                    ->rows(3),
            ]);
    }
}
