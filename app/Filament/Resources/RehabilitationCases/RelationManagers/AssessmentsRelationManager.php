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
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class AssessmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'assessments';

    protected static ?string $title = 'Data Asesmen Klien';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('assessment_date')
                    ->label('Tanggal Asesmen')
                    ->default(now())
                    ->required(),
                Select::make('officer_id')
                    ->label('Petugas Asesor')
                    ->options(fn () => User::whereHas('roles', fn ($q) => $q->where('name', 'petugas_dinsos'))->pluck('name', 'id'))
                    ->default(fn () => Auth::id())
                    ->required(),
                Textarea::make('result')
                    ->label('Hasil Asesmen / Kondisi Fisik & Psikososial')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull(),
                Textarea::make('service_needs')
                    ->label('Kebutuhan Pelayanan Klien')
                    ->rows(2)
                    ->columnSpanFull(),
                Textarea::make('recommendation')
                    ->label('Rekomendasi Tindakan')
                    ->required()
                    ->rows(2)
                    ->columnSpanFull(),
                Toggle::make('needs_referral')
                    ->label('Memerlukan Rujukan ke Lembaga / Panti')
                    ->default(false),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('assessment_date')
            ->columns([
                TextColumn::make('assessment_date')
                    ->label('Tgl Asesmen')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('officer.name')
                    ->label('Petugas Asesor')
                    ->placeholder('-'),
                TextColumn::make('result')
                    ->label('Hasil Asesmen')
                    ->limit(50),
                TextColumn::make('recommendation')
                    ->label('Rekomendasi')
                    ->limit(40),
                IconColumn::make('needs_referral')
                    ->label('Perlu Rujukan')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()->label('Tambah Asesmen'),
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
