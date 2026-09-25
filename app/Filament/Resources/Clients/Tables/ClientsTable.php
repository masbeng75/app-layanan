<?php

namespace App\Filament\Resources\Clients\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ClientsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Klien')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('category.name')
                    ->label('Kategori PMKS')
                    ->badge()
                    ->sortable(),
                TextColumn::make('nik')
                    ->label('NIK')
                    ->placeholder('-')
                    ->searchable(),
                TextColumn::make('gender')
                    ->label('L/P')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'L' ? 'info' : 'warning'),
                TextColumn::make('village.name')
                    ->label('Desa/Kelurahan')
                    ->description(fn ($record): string => $record->village?->district?->name ?? '-')
                    ->sortable(),
                TextColumn::make('phone')
                    ->label('Kontak')
                    ->placeholder('-'),
                TextColumn::make('rehabilitation_cases_count')
                    ->counts('rehabilitationCases')
                    ->label('Jumlah Kasus')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('client_category_id')
                    ->label('Kategori PMKS')
                    ->relationship('category', 'name'),
                SelectFilter::make('village_id')
                    ->label('Wilayah Desa')
                    ->relationship('village', 'name')
                    ->searchable(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
