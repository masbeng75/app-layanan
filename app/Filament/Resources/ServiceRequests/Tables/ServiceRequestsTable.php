<?php

namespace App\Filament\Resources\ServiceRequests\Tables;

use App\Enums\ServiceRequestStatus;
use App\Filament\Exports\ServiceRequestExporter;
use App\Models\User;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ExportBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class ServiceRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->orderByDesc('is_priority')->orderByDesc('created_at'))
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('request_number')
                    ->label('No. Pengajuan')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),
                TextColumn::make('applicant_name')
                    ->label('Nama Pemohon')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record): string => 'NIK: '.($record->applicant_nik ?? '-')),
                TextColumn::make('serviceType.name')
                    ->label('Jenis Layanan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('village.name')
                    ->label('Wilayah')
                    ->description(fn ($record): string => $record->village?->district?->name ?? '-')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof ServiceRequestStatus ? $state->label() : $state)
                    ->color(fn ($state): string => $state instanceof ServiceRequestStatus ? $state->color() : 'gray')
                    ->sortable(),
                TextColumn::make('officer.name')
                    ->label('Petugas')
                    ->placeholder('Belum Ditugaskan')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Tgl Masuk')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status Pengajuan')
                    ->options(
                        collect(ServiceRequestStatus::cases())
                            ->mapWithKeys(fn ($status) => [$status->value => $status->label()])
                            ->all()
                    ),
                SelectFilter::make('service_type_id')
                    ->label('Jenis Layanan')
                    ->relationship('serviceType', 'name'),
                SelectFilter::make('village_id')
                    ->label('Desa / Kelurahan')
                    ->relationship('village', 'name')
                    ->searchable(),
            ])
            ->headerActions([
                ExportAction::make()
                    ->label('Ekspor Data')
                    ->exporter(ServiceRequestExporter::class)
                    ->color('success'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    ExportBulkAction::make()
                        ->label('Ekspor Pilihan')
                        ->exporter(ServiceRequestExporter::class),
                    BulkAction::make('assignOfficer')
                        ->label('Tugaskan Petugas')
                        ->icon('heroicon-o-user-plus')
                        ->schema([
                            Select::make('officer_id')
                                ->label('Pilih Petugas')
                                ->options(fn () => User::whereHas('roles', fn ($q) => $q->where('name', 'petugas_dinsos'))->pluck('name', 'id'))
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data): void {
                            $records->each(fn ($record) => $record->update(['officer_id' => $data['officer_id']]));
                        }),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
