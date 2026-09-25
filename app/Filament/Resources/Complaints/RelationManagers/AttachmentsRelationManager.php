<?php

namespace App\Filament\Resources\Complaints\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AttachmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'attachments';

    protected static ?string $title = 'Lampiran & Bukti Pengaduan';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('file_path')
                    ->label('File Foto / Dokumen Bukti')
                    ->directory('complaint-attachments')
                    ->visibility('public')
                    ->required(),
                Select::make('type')
                    ->label('Jenis Lampiran')
                    ->options([
                        'foto' => 'Foto Kondisi di Lapangan',
                        'dokumen' => 'Dokumen / Surat Pendukung',
                        'lainnya' => 'Lain-lain',
                    ])
                    ->default('foto')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('file_path')
            ->columns([
                ImageColumn::make('file_path')
                    ->label('Pratinjau')
                    ->visibility('public'),
                TextColumn::make('type')
                    ->label('Jenis')
                    ->badge(),
                TextColumn::make('file_path')
                    ->label('Unduh / Lihat File')
                    ->formatStateUsing(fn () => 'Buka Berkas')
                    ->url(fn ($record): ?string => $record->file_path ? asset('storage/'.$record->file_path) : null, shouldOpenInNewTab: true),
                TextColumn::make('created_at')
                    ->label('Waktu Unggah')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Lampiran'),
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
