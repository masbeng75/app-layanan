<?php

namespace App\Filament\Resources\ActivityLogs\Tables;

use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Models\Activity;

class ActivityLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable(),
                TextColumn::make('causer.name')
                    ->label('Pengguna')
                    ->default('Sistem / Tamu')
                    ->searchable(),
                TextColumn::make('event')
                    ->label('Aksi')
                    ->badge()
                    ->colors([
                        'success' => 'created',
                        'info' => 'updated',
                        'danger' => 'deleted',
                    ]),
                TextColumn::make('subject_type')
                    ->label('Entitas')
                    ->formatStateUsing(fn (?string $state): string => $state ? class_basename($state) : '-'),
                TextColumn::make('subject_id')
                    ->label('ID Data')
                    ->numeric(),
                TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(40),
            ])
            ->filters([
                SelectFilter::make('event')
                    ->label('Jenis Aksi')
                    ->options([
                        'created' => 'Data Dibuat (Created)',
                        'updated' => 'Data Diubah (Updated)',
                        'deleted' => 'Data Dihapus (Deleted)',
                    ]),
                Filter::make('created_at')
                    ->label('Rentang Waktu')
                    ->form([
                        DatePicker::make('from')->label('Dari Tanggal'),
                        DatePicker::make('until')->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn (Builder $q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['until'], fn (Builder $q, $date) => $q->whereDate('created_at', '<=', $date));
                    }),
            ])
            ->actions([
                ViewAction::make()
                    ->label('Detail')
                    ->icon(Heroicon::OutlinedEye)
                    ->form([
                        Grid::make(2)->schema([
                            TextInput::make('created_at')
                                ->label('Waktu Kejadian')
                                ->disabled(),
                            TextInput::make('event')
                                ->label('Aksi')
                                ->disabled(),
                            TextInput::make('causer_name')
                                ->label('Pengguna / Aktor')
                                ->afterStateHydrated(fn ($component, Activity $record) => $component->state($record->causer?->name ?? 'Sistem'))
                                ->disabled(),
                            TextInput::make('subject_type')
                                ->label('Entitas Terkait')
                                ->afterStateHydrated(fn ($component, Activity $record) => $component->state($record->subject_type ? class_basename($record->subject_type).' #'.$record->subject_id : '-'))
                                ->disabled(),
                        ]),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->disabled()
                            ->columnSpanFull(),
                        Textarea::make('properties_formatted')
                            ->label('Detail Perubahan Data (JSON)')
                            ->rows(8)
                            ->afterStateHydrated(fn ($component, Activity $record) => $component->state(json_encode($record->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)))
                            ->disabled()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
