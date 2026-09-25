<?php

namespace App\Filament\Resources\InformationPages\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DownloadableFormsRelationManager extends RelationManager
{
    protected static string $relationship = 'downloadableForms';

    protected static ?string $title = 'Formulir Unduhan';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Formulir / Dokumen Template')
                    ->required()
                    ->maxLength(255),
                FileUpload::make('file_path')
                    ->label('File Template (PDF / Word)')
                    ->directory('downloadable-forms')
                    ->visibility('public')
                    ->required(),
                TextInput::make('version')
                    ->label('Nomor Versi')
                    ->default('v1.0')
                    ->maxLength(50),
                Toggle::make('is_current')
                    ->label('Versi Aktif Terkini')
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Formulir')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('version')
                    ->label('Versi')
                    ->badge(),
                IconColumn::make('is_current')
                    ->label('Aktif')
                    ->boolean(),
                TextColumn::make('file_path')
                    ->label('Berkas')
                    ->formatStateUsing(fn () => 'Unduh File')
                    ->url(fn ($record): ?string => $record->file_path ? asset('storage/'.$record->file_path) : null, shouldOpenInNewTab: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()->label('Tambah Formulir'),
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
