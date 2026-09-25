<?php

namespace App\Filament\Resources\Complaints\Schemas;

use App\Enums\ComplaintStatus;
use App\Models\District;
use App\Models\User;
use App\Models\Village;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class ComplaintForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Pelapor')
                    ->schema([
                        Toggle::make('is_anonymous')
                            ->label('Laporkan Secara Anonim (Kerahasiaan Identitas Dijamin)')
                            ->live()
                            ->default(false)
                            ->columnSpanFull(),
                        Grid::make(3)->schema([
                            TextInput::make('reporter_name')
                                ->label('Nama Lengkap')
                                ->required(fn (Get $get): bool => ! (bool) $get('is_anonymous'))
                                ->maxLength(255),
                            TextInput::make('reporter_phone')
                                ->label('Nomor WhatsApp / HP')
                                ->tel()
                                ->required(fn (Get $get): bool => ! (bool) $get('is_anonymous'))
                                ->maxLength(50),
                            TextInput::make('reporter_email')
                                ->label('Email Pelapor')
                                ->email()
                                ->maxLength(255),
                        ]),
                    ]),

                Section::make('Lokasi Kejadian / Permasalahan')
                    ->schema([
                        Grid::make(3)->schema([
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
                                ->required(),
                            TextInput::make('coordinates')
                                ->label('Titik Koordinat (Opsional)')
                                ->placeholder('-8.09876, 112.16543'),
                            Textarea::make('location_address')
                                ->label('Detail Alamat / Patokan Lokasi Kasus')
                                ->required()
                                ->columnSpanFull(),
                        ]),
                    ]),

                Section::make('Rincian Pengaduan')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('complaint_number')
                                ->label('Nomor Pengaduan')
                                ->default(fn () => 'LAP-'.date('Ymd').'-'.strtoupper(bin2hex(random_bytes(3))))
                                ->disabled()
                                ->dehydrated()
                                ->required(),
                            Select::make('complaint_category_id')
                                ->relationship('category', 'name')
                                ->label('Kategori Pengaduan')
                                ->required(),
                            TextInput::make('title')
                                ->label('Pokok Pengaduan / Judul')
                                ->required()
                                ->columnSpanFull(),
                            Textarea::make('description')
                                ->label('Kronologi / Uraian Lengkap Kejadian')
                                ->rows(4)
                                ->required()
                                ->columnSpanFull(),
                        ]),
                    ]),

                Section::make('Tindak Lanjut & Penanganan')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('status')
                                ->label('Status Pengaduan')
                                ->options(
                                    collect(ComplaintStatus::cases())
                                        ->mapWithKeys(fn ($status) => [$status->value => $status->label()])
                                        ->all()
                                )
                                ->default(ComplaintStatus::RECEIVED->value)
                                ->required(),
                            Select::make('officer_id')
                                ->label('Petugas Penangan (Dinsos)')
                                ->options(fn () => User::whereHas('roles', fn ($q) => $q->where('name', 'petugas_dinsos'))->pluck('name', 'id'))
                                ->searchable(),
                            Textarea::make('resolution_notes')
                                ->label('Hasil / Catatan Penyelesaian Laporan')
                                ->rows(3)
                                ->columnSpanFull(),
                        ]),
                    ]),
            ]);
    }
}
