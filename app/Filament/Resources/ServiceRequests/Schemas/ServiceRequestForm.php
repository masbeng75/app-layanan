<?php

namespace App\Filament\Resources\ServiceRequests\Schemas;

use App\Enums\ServiceRequestStatus;
use App\Models\District;
use App\Models\User;
use App\Models\Village;
use App\Services\DtsenDuplicateCheckService;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class ServiceRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Pemohon')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('applicant_name')
                                ->label('Nama Lengkap Pemohon')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('applicant_nik')
                                ->label('NIK Pemohon (16 Digit)')
                                ->required()
                                ->length(16)
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (?string $state): void {
                                    if ($state && strlen($state) === 16) {
                                        $duplicate = DtsenDuplicateCheckService::checkDuplicate($state);
                                        if ($duplicate) {
                                            Notification::make()
                                                ->warning()
                                                ->title('Perhatian: Surat Keterangan DTSEN Masih Aktif')
                                                ->body("NIK {$state} sudah terdaftar pada SK DTSEN {$duplicate->certificate_number} untuk keperluan {$duplicate->purpose?->name} (berlaku s.d. ".($duplicate->valid_until ? $duplicate->valid_until->format('d/m/Y') : 'permanen').'). Harap verifikasi sebelum memproses pengajuan baru.')
                                                ->persistent()
                                                ->send();
                                        }
                                    }
                                })
                                ->helperText(function (Get $get): ?string {
                                    $nik = $get('applicant_nik');
                                    if ($nik && strlen((string) $nik) === 16) {
                                        $duplicate = DtsenDuplicateCheckService::checkDuplicate((string) $nik);
                                        if ($duplicate) {
                                            return '⚠️ NIK ini memiliki SK DTSEN aktif: '.$duplicate->certificate_number;
                                        }
                                    }

                                    return null;
                                }),
                            TextInput::make('applicant_kk')
                                ->label('Nomor Kartu Keluarga (KK)')
                                ->length(16),
                            TextInput::make('applicant_phone')
                                ->label('Nomor WhatsApp / HP')
                                ->tel()
                                ->required()
                                ->maxLength(50),
                            TextInput::make('applicant_email')
                                ->label('Email Pemohon')
                                ->email()
                                ->maxLength(255),
                            Select::make('district_id')
                                ->label('Kecamatan')
                                ->options(fn () => auth()->user()?->isOperator() && auth()->user()?->district_id
                                    ? District::where('id', auth()->user()->district_id)->pluck('name', 'id')
                                    : District::pluck('name', 'id')
                                )
                                ->default(fn () => auth()->user()?->district_id)
                                ->disabled(fn () => auth()->user()?->isOperator() && auth()->user()?->district_id !== null)
                                ->dehydrated()
                                ->searchable()
                                ->preload()
                                ->live()
                                ->afterStateUpdated(fn ($set) => $set('village_id', null)),
                            Select::make('village_id')
                                ->label('Desa / Kelurahan')
                                ->options(function (Get $get) {
                                    $user = auth()->user();
                                    if ($user?->isOperator() && $user?->village_id) {
                                        return Village::where('id', $user->village_id)->pluck('name', 'id');
                                    }
                                    $districtId = $get('district_id') ?? $user?->district_id;

                                    return $districtId
                                        ? Village::where('district_id', $districtId)->pluck('name', 'id')
                                        : Village::pluck('name', 'id');
                                })
                                ->default(fn () => auth()->user()?->village_id)
                                ->disabled(fn () => auth()->user()?->isOperator() && auth()->user()?->village_id !== null)
                                ->dehydrated()
                                ->searchable()
                                ->preload()
                                ->required(),
                            Textarea::make('applicant_address')
                                ->label('Alamat Lengkap (RT/RW/Dusun)')
                                ->required()
                                ->columnSpanFull(),
                        ]),
                    ]),

                Section::make('Jenis Layanan')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('service_type_id')
                                ->relationship('serviceType', 'name')
                                ->label('Jenis Layanan Sosial')
                                ->required()
                                ->live(),
                            TextInput::make('request_number')
                                ->label('Nomor Tiket / Pengajuan')
                                ->default(fn () => 'REQ-'.date('Ymd').'-'.strtoupper(bin2hex(random_bytes(3))))
                                ->disabled()
                                ->dehydrated()
                                ->required(),
                            Textarea::make('notes')
                                ->label('Catatan Pemohon / Alasan Pengajuan')
                                ->columnSpanFull(),
                        ]),
                    ]),

                Section::make('Status & Penugasan')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('status')
                                ->label('Status Pengajuan')
                                ->options(
                                    collect(ServiceRequestStatus::cases())
                                        ->mapWithKeys(fn ($status) => [$status->value => $status->label()])
                                        ->all()
                                )
                                ->default(ServiceRequestStatus::SUBMITTED->value)
                                ->required(),
                            Select::make('officer_id')
                                ->label('Petugas Pemeriksa (Dinsos)')
                                ->options(fn () => User::whereHas('roles', fn ($q) => $q->where('name', 'petugas_dinsos'))->pluck('name', 'id'))
                                ->searchable()
                                ->preload(),
                            Select::make('work_unit_id')
                                ->relationship('workUnit', 'name')
                                ->label('Bidang / Unit Kerja Penanggung Jawab'),
                            Textarea::make('rejection_reason')
                                ->label('Alasan Penolakan')
                                ->columnSpanFull()
                                ->visible(fn (Get $get): bool => $get('status') === ServiceRequestStatus::REJECTED->value),
                        ]),
                    ]),
            ]);
    }
}
