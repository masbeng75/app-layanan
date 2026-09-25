<?php

namespace App\Filament\Resources\RehabilitationCases\Schemas;

use App\Enums\HandlingType;
use App\Enums\RehabilitationCaseStatus;
use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RehabilitationCaseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Kasus & Klien')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('case_number')
                                ->label('Nomor Kasus')
                                ->default(fn () => 'REH-'.date('Ymd').'-'.strtoupper(bin2hex(random_bytes(3))))
                                ->disabled()
                                ->dehydrated()
                                ->required(),
                            Select::make('client_id')
                                ->relationship('client', 'name')
                                ->label('Klien PMKS')
                                ->searchable()
                                ->preload()
                                ->required(),
                            Select::make('handling_type')
                                ->label('Bentuk Penanganan')
                                ->options(
                                    collect(HandlingType::cases())
                                        ->mapWithKeys(fn ($t) => [$t->value => $t->label()])
                                        ->all()
                                )
                                ->default(HandlingType::DIRECT->value)
                                ->required(),
                            Select::make('officer_id')
                                ->label('Pekerja Sosial (Peksos) / Petugas')
                                ->options(fn () => User::whereHas('roles', fn ($q) => $q->where('name', 'petugas_dinsos'))->pluck('name', 'id'))
                                ->searchable()
                                ->preload(),
                        ]),
                    ]),

                Section::make('Status & Progres Penanganan')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('status')
                                ->label('Status Penanganan')
                                ->options(
                                    collect(RehabilitationCaseStatus::cases())
                                        ->mapWithKeys(fn ($s) => [$s->value => $s->label()])
                                        ->all()
                                )
                                ->default(RehabilitationCaseStatus::RECEIVED->value)
                                ->required(),
                            DateTimePicker::make('received_at')
                                ->label('Waktu Penerimaan Kasus')
                                ->default(now()),
                            DateTimePicker::make('closed_at')
                                ->label('Waktu Kasus Selesai / Ditutup'),
                            Textarea::make('handling_result')
                                ->label('Hasil / Kesimpulan Penanganan Kasus')
                                ->columnSpanFull()
                                ->rows(3),
                        ]),
                    ]),
            ]);
    }
}
