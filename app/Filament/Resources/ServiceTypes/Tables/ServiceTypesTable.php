<?php

namespace App\Filament\Resources\ServiceTypes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ServiceTypesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Kode')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Nama Layanan')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('category')
                    ->label('Kategori')
                    ->sortable(),
                TextColumn::make('handler')
                    ->label('Handler')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'dtsen' => 'info',
                        'pbi' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('sla_days')
                    ->label('SLA')
                    ->suffix(' hari kerja')
                    ->sortable(),
                IconColumn::make('needs_assessment')
                    ->label('Asesmen')
                    ->boolean(),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('handler')
                    ->options([
                        'dtsen' => 'DTSEN',
                        'pbi' => 'PBI',
                        'generic' => 'Generik',
                    ]),
                TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
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
