<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Akun')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label('Nama Lengkap')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('email')
                                ->label('Email')
                                ->email()
                                ->required()
                                ->maxLength(255)
                                ->unique(ignoreRecord: true),
                            TextInput::make('password')
                                ->label('Kata Sandi')
                                ->password()
                                ->dehydrated(fn (?string $state): bool => filled($state))
                                ->required(fn (string $operation): bool => $operation === 'create')
                                ->maxLength(255),
                            TextInput::make('phone')
                                ->label('Nomor WhatsApp / HP')
                                ->tel()
                                ->maxLength(50),
                            TextInput::make('nik')
                                ->label('NIK')
                                ->maxLength(16),
                            Toggle::make('is_active')
                                ->label('Status Aktif')
                                ->default(true),
                        ]),
                    ]),

                Section::make('Penugasan & Peran')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('roles')
                                ->relationship('roles', 'name')
                                ->label('Peran Akses (Role)')
                                ->multiple()
                                ->preload()
                                ->required(),
                            Select::make('work_unit_id')
                                ->relationship('workUnit', 'name')
                                ->label('Unit Kerja / Bidang (Petugas/Kabid)')
                                ->searchable()
                                ->preload(),
                            Select::make('district_id')
                                ->relationship('district', 'name')
                                ->label('Kecamatan (Operator/Warga)')
                                ->searchable()
                                ->preload()
                                ->live(),
                            Select::make('village_id')
                                ->relationship(
                                    name: 'village',
                                    titleAttribute: 'name',
                                    modifyQueryUsing: fn ($query, Get $get) => $get('district_id')
                                        ? $query->where('district_id', $get('district_id'))
                                        : $query
                                )
                                ->label('Desa / Kelurahan (Operator/Warga)')
                                ->searchable()
                                ->preload(),
                        ]),
                    ]),
            ]);
    }
}
