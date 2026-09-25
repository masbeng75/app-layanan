<?php

namespace App\Filament\Resources\RehabilitationCases\RelationManagers;

use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class MonitoringRecordsRelationManager extends RelationManager
{
    protected static string $relationship = 'monitoringRecords';

    protected static ?string $title = 'Catatan Monitoring & Perkembangan';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('monitoring_date')
                    ->label('Tanggal Monitoring')
                    ->default(now())
                    ->required(),
                Select::make('officer_id')
                    ->label('Petugas Monitoring')
                    ->options(fn () => User::whereHas('roles', fn ($q) => $q->where('name', 'petugas_dinsos'))->pluck('name', 'id'))
                    ->default(fn () => Auth::id())
                    ->required(),
                TextInput::make('progress')
                    ->label('Ringkasan Perkembangan Klien')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Textarea::make('result_notes')
                    ->label('Catatan Observasi Lengkap')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('progress')
            ->columns([
                TextColumn::make('monitoring_date')
                    ->label('Tgl Monitoring')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('officer.name')
                    ->label('Petugas')
                    ->placeholder('-'),
                TextColumn::make('progress')
                    ->label('Perkembangan')
                    ->searchable()
                    ->limit(50),
                TextColumn::make('result_notes')
                    ->label('Catatan')
                    ->limit(40)
                    ->placeholder('-'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()->label('Tambah Catatan Monitoring'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
