<?php

namespace App\Filament\Resources\ReferralInstitutions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ReferralInstitutionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Lembaga / Panti')
                    ->required()
                    ->maxLength(255),
                Select::make('type')
                    ->label('Jenis Lembaga')
                    ->options([
                        'panti_sosial' => 'Panti Sosial / Balai Rehabilitasi',
                        'rumah_sakit' => 'Rumah Sakit / Fasilitas Kesehatan',
                        'lks' => 'Lembaga Kesejahteraan Sosial (LKS)',
                        'kepolisian' => 'Kepolisian / Penegak Hukum',
                        'pendidikan' => 'Lembaga Pendidikan / SLB',
                        'lainnya' => 'Lainnya',
                    ])
                    ->required(),
                TextInput::make('contact_person')
                    ->label('Nama Narahubung (PIC)')
                    ->maxLength(255),
                TextInput::make('phone')
                    ->label('Nomor Telepon')
                    ->tel()
                    ->maxLength(50),
                TextInput::make('email')
                    ->label('Alamat Email')
                    ->email()
                    ->maxLength(255),
                Toggle::make('is_active')
                    ->label('Status Aktif')
                    ->default(true),
                Textarea::make('address')
                    ->label('Alamat Lembaga')
                    ->columnSpanFull()
                    ->rows(3),
            ]);
    }
}
