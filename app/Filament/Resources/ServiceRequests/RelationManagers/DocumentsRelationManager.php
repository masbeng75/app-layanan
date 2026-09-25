<?php

namespace App\Filament\Resources\ServiceRequests\RelationManagers;

use App\Enums\DocumentVerificationStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'documents';

    protected static ?string $title = 'Dokumen Persyaratan Pemohon';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('service_requirement_id')
                    ->relationship('requirement', 'name')
                    ->label('Persyaratan Terkait')
                    ->searchable()
                    ->preload(),
                FileUpload::make('file_path')
                    ->label('File Berkas')
                    ->directory('service-documents')
                    ->visibility('public')
                    ->required(),
                TextInput::make('original_name')
                    ->label('Nama Berkas Asli')
                    ->maxLength(255),
                Select::make('verification_status')
                    ->label('Status Verifikasi Dokumen')
                    ->options(DocumentVerificationStatus::class)
                    ->default(DocumentVerificationStatus::PENDING)
                    ->required(),
                TextInput::make('notes')
                    ->label('Catatan / Alasan jika Perlu Perbaikan')
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('original_name')
            ->columns([
                TextColumn::make('requirement.name')
                    ->label('Jenis Dokumen')
                    ->placeholder('Dokumen Tambahan')
                    ->searchable(),
                TextColumn::make('original_name')
                    ->label('Nama File')
                    ->searchable(),
                TextColumn::make('file_path')
                    ->label('Unduh Berkas')
                    ->formatStateUsing(fn () => 'Buka Dokumen')
                    ->url(fn ($record): ?string => $record->file_path ? asset('storage/'.$record->file_path) : null, shouldOpenInNewTab: true),
                TextColumn::make('verification_status')
                    ->label('Status')
                    ->badge(),
                TextColumn::make('notes')
                    ->label('Catatan')
                    ->placeholder('-'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Unggah Dokumen'),
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
