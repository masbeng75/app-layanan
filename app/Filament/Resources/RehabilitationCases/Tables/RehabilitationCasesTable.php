<?php

namespace App\Filament\Resources\RehabilitationCases\Tables;

use App\Enums\HandlingType;
use App\Enums\RehabilitationCaseStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class RehabilitationCasesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('received_at', 'desc')
            ->columns([
                TextColumn::make('case_number')
                    ->label('No. Kasus')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),
                TextColumn::make('client.name')
                    ->label('Nama Klien')
                    ->description(fn ($record): string => $record->client?->category?->name ?? '-')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('handling_type')
                    ->label('Jenis Penanganan')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof HandlingType ? $state->label() : $state),
                TextColumn::make('status')
                    ->label('Status Kasus')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof RehabilitationCaseStatus ? $state->label() : $state)
                    ->color(fn ($state): string => $state instanceof RehabilitationCaseStatus ? $state->color() : 'gray')
                    ->sortable(),
                TextColumn::make('officer.name')
                    ->label('Peksos / Petugas')
                    ->placeholder('Belum Ditugaskan')
                    ->sortable(),
                TextColumn::make('received_at')
                    ->label('Tgl Diterima')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status Kasus')
                    ->options(
                        collect(RehabilitationCaseStatus::cases())
                            ->mapWithKeys(fn ($status) => [$status->value => $status->label()])
                            ->all()
                    ),
                SelectFilter::make('handling_type')
                    ->label('Jenis Penanganan')
                    ->options(
                        collect(HandlingType::cases())
                            ->mapWithKeys(fn ($type) => [$type->value => $type->label()])
                            ->all()
                    ),
                SelectFilter::make('officer_id')
                    ->label('Petugas Peksos')
                    ->relationship('officer', 'name'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
