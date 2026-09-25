<?php

namespace App\Filament\Resources\RehabilitationCases\Schemas;

use App\Enums\HandlingType;
use App\Enums\RehabilitationCaseStatus;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RehabilitationCaseInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Kasus')
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('case_number')
                                ->label('Nomor Kasus')
                                ->weight('bold')
                                ->copyable(),
                            TextEntry::make('handling_type')
                                ->label('Bentuk Pelayanan')
                                ->badge()
                                ->formatStateUsing(fn ($state) => $state instanceof HandlingType ? $state->label() : $state),
                            TextEntry::make('status')
                                ->label('Status Terkini')
                                ->badge()
                                ->formatStateUsing(fn ($state) => $state instanceof RehabilitationCaseStatus ? $state->label() : $state)
                                ->color(fn ($state): string => $state instanceof RehabilitationCaseStatus ? $state->color() : 'gray'),
                            TextEntry::make('officer.name')
                                ->label('Peksos / Petugas Penanggung Jawab')
                                ->placeholder('Belum Ditugaskan'),
                            TextEntry::make('received_at')
                                ->label('Waktu Penerimaan Kasus')
                                ->dateTime('d F Y, H:i'),
                            TextEntry::make('closed_at')
                                ->label('Waktu Penutupan Kasus')
                                ->dateTime('d F Y, H:i')
                                ->placeholder('Kasus Masih Aktif'),
                        ]),
                    ]),

                Section::make('Identitas Klien PMKS')
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('client.name')
                                ->label('Nama Lengkap Klien')
                                ->weight('bold'),
                            TextEntry::make('client.category.name')
                                ->label('Kategori PMKS')
                                ->badge(),
                            TextEntry::make('client.nik')
                                ->label('NIK Klien')
                                ->placeholder('-'),
                            TextEntry::make('client.gender')
                                ->label('Jenis Kelamin')
                                ->formatStateUsing(fn ($state) => $state === 'L' ? 'Laki-laki' : 'Perempuan'),
                            TextEntry::make('client.phone')
                                ->label('Nomor Telepon')
                                ->placeholder('-'),
                            TextEntry::make('client.village.name')
                                ->label('Wilayah Desa')
                                ->suffix(fn ($record) => $record->client?->village?->district ? ' (Kec. '.$record->client->village->district->name.')' : ''),
                            TextEntry::make('client.address')
                                ->label('Alamat Domisili')
                                ->columnSpanFull(),
                        ]),
                    ]),

                Section::make('Evaluasi & Hasil Penanganan')
                    ->schema([
                        TextEntry::make('handling_result')
                            ->label('Ringkasan Hasil Pelayanan Sosial')
                            ->placeholder('Belum ada kesimpulan hasil penanganan.')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
