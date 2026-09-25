<?php

namespace App\Filament\Resources\Complaints\Schemas;

use App\Enums\ComplaintStatus;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ComplaintInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Laporan & Status')
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('complaint_number')
                                ->label('Nomor Pengaduan')
                                ->weight('bold')
                                ->copyable(),
                            TextEntry::make('category.name')
                                ->label('Kategori Pengaduan')
                                ->badge(),
                            TextEntry::make('status')
                                ->label('Status Terkini')
                                ->badge()
                                ->formatStateUsing(fn ($state) => $state instanceof ComplaintStatus ? $state->label() : $state)
                                ->color(fn ($state): string => $state instanceof ComplaintStatus ? $state->color() : 'gray'),
                            TextEntry::make('officer.name')
                                ->label('Petugas Penangan')
                                ->placeholder('Belum Ditugaskan'),
                            TextEntry::make('reported_at')
                                ->label('Tanggal Laporan Masuk')
                                ->dateTime('d F Y, H:i'),
                            TextEntry::make('resolved_at')
                                ->label('Tanggal Penyelesaian')
                                ->dateTime('d F Y, H:i')
                                ->placeholder('Belum Selesai'),
                        ]),
                    ]),

                Section::make('Identitas Pelapor')
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('reporter_name')
                                ->label('Nama Pelapor')
                                ->formatStateUsing(fn ($record): string => $record->is_anonymous ? 'Rahasia (Anonim)' : ($record->reporter_name ?? '-')),
                            TextEntry::make('reporter_phone')
                                ->label('Nomor WhatsApp / HP')
                                ->formatStateUsing(fn ($record): string => $record->is_anonymous ? 'Disembunyikan' : ($record->reporter_phone ?? '-')),
                            TextEntry::make('reporter_email')
                                ->label('Email Pelapor')
                                ->placeholder('-'),
                        ]),
                    ]),

                Section::make('Lokasi Permasalahan')
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('village.district.name')
                                ->label('Kecamatan'),
                            TextEntry::make('village.name')
                                ->label('Desa / Kelurahan'),
                            TextEntry::make('coordinates')
                                ->label('Titik Koordinat')
                                ->placeholder('-'),
                            TextEntry::make('location_address')
                                ->label('Alamat / Patokan')
                                ->columnSpanFull(),
                        ]),
                    ]),

                Section::make('Uraian Pengaduan')
                    ->schema([
                        TextEntry::make('title')
                            ->label('Pokok Pengaduan')
                            ->weight('bold'),
                        TextEntry::make('description')
                            ->label('Kronologi / Deskripsi Lengkap')
                            ->columnSpanFull(),
                    ]),

                Section::make('Tindak Lanjut & Penyelesaian')
                    ->schema([
                        TextEntry::make('resolution_notes')
                            ->label('Catatan Penanganan / Solusi dari Dinas Sosial')
                            ->placeholder('Belum ada catatan penyelesaian.')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
