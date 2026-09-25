<?php

namespace App\Filament\Resources\ServiceRequests\Schemas;

use App\Enums\ServiceRequestStatus;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ServiceRequestInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pengajuan & Status')
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('request_number')
                                ->label('Nomor Tiket / Pengajuan')
                                ->weight('bold')
                                ->copyable(),
                            TextEntry::make('serviceType.name')
                                ->label('Jenis Layanan'),
                            TextEntry::make('status')
                                ->label('Status Terkini')
                                ->badge()
                                ->formatStateUsing(fn ($state) => $state instanceof ServiceRequestStatus ? $state->label() : $state)
                                ->color(fn ($state): string => $state instanceof ServiceRequestStatus ? $state->color() : 'gray'),
                            TextEntry::make('officer.name')
                                ->label('Petugas Pemeriksa')
                                ->placeholder('Belum Ditugaskan'),
                            TextEntry::make('workUnit.name')
                                ->label('Bidang Penanggung Jawab')
                                ->placeholder('-'),
                            TextEntry::make('created_at')
                                ->label('Tanggal Pengajuan Masuk')
                                ->dateTime('d F Y, H:i'),
                        ]),
                    ]),

                Section::make('Identitas Pemohon')
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('applicant_name')
                                ->label('Nama Pemohon'),
                            TextEntry::make('applicant_nik')
                                ->label('NIK Pemohon')
                                ->copyable(),
                            TextEntry::make('applicant_kk')
                                ->label('Nomor KK')
                                ->placeholder('-'),
                            TextEntry::make('applicant_phone')
                                ->label('Nomor WhatsApp / HP'),
                            TextEntry::make('applicant_email')
                                ->label('Email')
                                ->placeholder('-'),
                            TextEntry::make('village.name')
                                ->label('Desa / Kelurahan')
                                ->suffix(fn ($record) => $record->village?->district ? ' (Kec. '.$record->village->district->name.')' : ''),
                            TextEntry::make('applicant_address')
                                ->label('Alamat Lengkap')
                                ->columnSpanFull(),
                            TextEntry::make('notes')
                                ->label('Catatan / Keterangan Pemohon')
                                ->placeholder('Tidak ada catatan tambahan')
                                ->columnSpanFull(),
                        ]),
                    ]),

                Section::make('Detail Data DTSEN')
                    ->visible(fn ($record): bool => (bool) $record->dtsenCertificate)
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('dtsenCertificate.purpose.name')
                                ->label('Tujuan Penggunaan SK'),
                            TextEntry::make('dtsenCertificate.subject_name')
                                ->label('Nama yang Diterangkan'),
                            TextEntry::make('dtsenCertificate.subject_nik')
                                ->label('NIK yang Diterangkan'),
                            TextEntry::make('dtsenCertificate.relationship_to_applicant')
                                ->label('Hubungan dengan Pemohon'),
                            TextEntry::make('dtsenCertificate.decile')
                                ->label('Peringkat Desil')
                                ->badge()
                                ->color('primary')
                                ->placeholder('Belum Dicek'),
                            TextEntry::make('dtsenCertificate.certificate_number')
                                ->label('Nomor Surat Keterangan')
                                ->placeholder('Belum Terbit')
                                ->weight('bold'),
                        ]),
                    ]),

                Section::make('Detail Reaktivasi PBI-JK')
                    ->visible(fn ($record): bool => (bool) $record->pbiReactivation)
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('pbiReactivation.participant_name')
                                ->label('Nama Peserta BPJS'),
                            TextEntry::make('pbiReactivation.participant_nik')
                                ->label('NIK Peserta'),
                            TextEntry::make('pbiReactivation.bpjs_card_number')
                                ->label('Nomor Kartu BPJS / KIS'),
                            TextEntry::make('pbiReactivation.reason')
                                ->label('Alasan Penonaktifan / Pengusulan'),
                            TextEntry::make('pbiReactivation.health_facility')
                                ->label('Faskes Rujukan / Rawat'),
                            TextEntry::make('pbiReactivation.recommendation_number')
                                ->label('Nomor Rekomendasi Dinas')
                                ->placeholder('Belum Terbit'),
                        ]),
                    ]),
            ]);
    }
}
