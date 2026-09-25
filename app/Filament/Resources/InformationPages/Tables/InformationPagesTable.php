<?php

namespace App\Filament\Resources\InformationPages\Tables;

use App\Enums\PublishStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class InformationPagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Judul Informasi / Panduan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->sortable(),
                TextColumn::make('serviceType.name')
                    ->label('Layanan Terkait')
                    ->placeholder('-')
                    ->sortable(),
                TextColumn::make('publish_status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof PublishStatus ? $state->label() : $state)
                    ->color(fn ($state): string => $state instanceof PublishStatus ? $state->color() : 'gray')
                    ->sortable(),
                TextColumn::make('manager.name')
                    ->label('Pengelola')
                    ->placeholder('-'),
                TextColumn::make('published_at')
                    ->label('Tgl Publikasi')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('publish_status')
                    ->label('Status')
                    ->options(
                        collect(PublishStatus::cases())
                            ->mapWithKeys(fn ($p) => [$p->value => $p->label()])
                            ->all()
                    ),
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
