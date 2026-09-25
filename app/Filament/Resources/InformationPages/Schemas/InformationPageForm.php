<?php

namespace App\Filament\Resources\InformationPages\Schemas;

use App\Enums\PublishStatus;
use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class InformationPageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('title')
                                ->label('Judul Panduan / Layanan')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn ($set, ?string $state) => $set('slug', Str::slug($state ?? ''))),
                            TextInput::make('slug')
                                ->label('Slug URL')
                                ->required()
                                ->maxLength(255),
                            Select::make('service_type_id')
                                ->relationship('serviceType', 'name')
                                ->label('Terkait Jenis Layanan')
                                ->searchable()
                                ->preload(),
                            TextInput::make('category')
                                ->label('Kategori')
                                ->default('Layanan Sosial')
                                ->required(),
                            Select::make('publish_status')
                                ->label('Status Publikasi')
                                ->options(
                                    collect(PublishStatus::cases())
                                        ->mapWithKeys(fn ($p) => [$p->value => $p->label()])
                                        ->all()
                                )
                                ->default(PublishStatus::PUBLISHED->value)
                                ->required(),
                            DateTimePicker::make('published_at')
                                ->label('Tanggal Publikasi')
                                ->default(now()),
                            Select::make('manager_id')
                                ->label('Pengelola / Editor')
                                ->options(User::pluck('name', 'id'))
                                ->searchable(),
                        ]),
                    ]),

                Section::make('Konten & Panduan')
                    ->schema([
                        RichEditor::make('description')
                            ->label('Deskripsi Layanan')
                            ->columnSpanFull(),
                        RichEditor::make('requirements')
                            ->label('Persyaratan Pelayanan')
                            ->columnSpanFull(),
                        RichEditor::make('procedure')
                            ->label('Alur & Prosedur Pelayanan')
                            ->columnSpanFull(),
                    ]),

                Section::make('Informasi Lokasi & Kontak')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('service_hours')
                                ->label('Jam Layanan')
                                ->default('Senin - Kamis (08.00 - 15.00), Jumat (08.00 - 14.30) WIB'),
                            TextInput::make('location')
                                ->label('Lokasi Layanan Tatap Muka')
                                ->default('Front Office Pelayanan Terpadu Dinsos Kab. Blitar'),
                            TextInput::make('contact')
                                ->label('Kontak / Call Center')
                                ->default('0812-3456-7890'),
                        ]),
                    ]),
            ]);
    }
}
