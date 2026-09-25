<?php

namespace App\Filament\Resources\Clients\Schemas;

use App\Models\District;
use App\Models\Village;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class ClientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas Klien PMKS')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('name')
                                ->label('Nama Lengkap')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('nik')
                                ->label('NIK Klien (16 Digit)')
                                ->length(16),
                            Select::make('client_category_id')
                                ->relationship('category', 'name')
                                ->label('Kategori PMKS')
                                ->required(),
                            Select::make('gender')
                                ->label('Jenis Kelamin')
                                ->options([
                                    'L' => 'Laki-laki',
                                    'P' => 'Perempuan',
                                ])
                                ->required(),
                            DatePicker::make('birth_date')
                                ->label('Tanggal Lahir'),
                            TextInput::make('phone')
                                ->label('Nomor Telepon / HP')
                                ->tel(),
                        ]),
                    ]),

                Section::make('Alamat & Wilayah')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('district_id')
                                ->label('Kecamatan')
                                ->options(District::pluck('name', 'id'))
                                ->searchable()
                                ->live()
                                ->afterStateUpdated(fn ($set) => $set('village_id', null)),
                            Select::make('village_id')
                                ->label('Desa / Kelurahan')
                                ->options(fn (Get $get) => $get('district_id')
                                    ? Village::where('district_id', $get('district_id'))->pluck('name', 'id')
                                    : Village::pluck('name', 'id')
                                )
                                ->searchable()
                                ->preload()
                                ->required(),
                            Textarea::make('address')
                                ->label('Alamat Lengkap / Keterangan Lokasi')
                                ->columnSpanFull(),
                        ]),
                    ]),
            ]);
    }
}
