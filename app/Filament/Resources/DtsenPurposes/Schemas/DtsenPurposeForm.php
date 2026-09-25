<?php

namespace App\Filament\Resources\DtsenPurposes\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DtsenPurposeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label('Kode Keperluan')
                    ->required()
                    ->maxLength(50)
                    ->unique(ignoreRecord: true),
                TextInput::make('name')
                    ->label('Nama Tujuan / Keperluan')
                    ->required()
                    ->maxLength(255),
                TextInput::make('max_decile')
                    ->label('Maksimal Desil (1 - 10)')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(10)
                    ->required(),
                TextInput::make('validity_days')
                    ->label('Masa Berlaku (Hari)')
                    ->numeric()
                    ->default(30)
                    ->required(),
                Toggle::make('is_active')
                    ->label('Status Aktif')
                    ->default(true),
                Textarea::make('description')
                    ->label('Keterangan / Persyaratan Khusus')
                    ->columnSpanFull()
                    ->rows(3),
            ]);
    }
}
