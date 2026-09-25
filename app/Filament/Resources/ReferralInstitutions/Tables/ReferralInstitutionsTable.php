<?php

namespace App\Filament\Resources\ReferralInstitutions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ReferralInstitutionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Lembaga')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Jenis')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'panti_sosial' => 'Panti Sosial',
                        'rumah_sakit' => 'Faskes / RS',
                        'lks' => 'LKS',
                        'kepolisian' => 'Kepolisian',
                        'pendidikan' => 'Pendidikan',
                        default => ucfirst($state),
                    }),
                TextColumn::make('contact_person')
                    ->label('PIC / Kontak')
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('Telepon'),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'panti_sosial' => 'Panti Sosial',
                        'rumah_sakit' => 'Rumah Sakit',
                        'lks' => 'LKS',
                        'kepolisian' => 'Kepolisian',
                        'pendidikan' => 'Pendidikan',
                        'lainnya' => 'Lainnya',
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
