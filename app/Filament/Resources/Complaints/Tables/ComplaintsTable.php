<?php

namespace App\Filament\Resources\Complaints\Tables;

use App\Enums\ComplaintStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ComplaintsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('reported_at', 'desc')
            ->columns([
                TextColumn::make('complaint_number')
                    ->label('No. Pengaduan')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),
                TextColumn::make('title')
                    ->label('Judul Laporan')
                    ->searchable()
                    ->limit(40)
                    ->tooltip(fn ($record): string => $record->title ?? ''),
                TextColumn::make('reporter_name')
                    ->label('Pelapor')
                    ->formatStateUsing(fn ($record): string => $record->is_anonymous ? 'Rahasia (Anonim)' : ($record->reporter_name ?? '-'))
                    ->description(fn ($record): ?string => $record->is_anonymous ? null : $record->reporter_phone)
                    ->searchable(),
                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->sortable(),
                TextColumn::make('village.name')
                    ->label('Lokasi')
                    ->description(fn ($record): string => $record->village?->district?->name ?? '-')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof ComplaintStatus ? $state->label() : $state)
                    ->color(fn ($state): string => $state instanceof ComplaintStatus ? $state->color() : 'gray')
                    ->sortable(),
                TextColumn::make('officer.name')
                    ->label('Petugas')
                    ->placeholder('Belum Ditugaskan')
                    ->sortable(),
                TextColumn::make('reported_at')
                    ->label('Tgl Masuk')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status Pengaduan')
                    ->options(
                        collect(ComplaintStatus::cases())
                            ->mapWithKeys(fn ($status) => [$status->value => $status->label()])
                            ->all()
                    ),
                SelectFilter::make('complaint_category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name'),
                SelectFilter::make('village_id')
                    ->label('Desa / Kelurahan')
                    ->relationship('village', 'name')
                    ->searchable(),
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
