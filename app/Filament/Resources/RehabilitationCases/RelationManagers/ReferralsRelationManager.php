<?php

namespace App\Filament\Resources\RehabilitationCases\RelationManagers;

use App\Enums\ReferralStatus;
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

class ReferralsRelationManager extends RelationManager
{
    protected static string $relationship = 'referrals';

    protected static ?string $title = 'Data Rujukan Klien ke Lembaga';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('referral_number')
                    ->label('Nomor Surat Rujukan')
                    ->default(fn () => 'RUJ-'.date('Ymd').'-'.strtoupper(bin2hex(random_bytes(3))))
                    ->required(),
                Select::make('referral_institution_id')
                    ->relationship('institution', 'name')
                    ->label('Lembaga / Panti Tujuan')
                    ->searchable()
                    ->preload()
                    ->required(),
                DatePicker::make('referral_date')
                    ->label('Tanggal Pengiriman Rujukan')
                    ->default(now())
                    ->required(),
                Select::make('status')
                    ->label('Status Rujukan')
                    ->options(
                        collect(ReferralStatus::cases())
                            ->mapWithKeys(fn ($status) => [$status->value => $status->label()])
                            ->all()
                    )
                    ->default(ReferralStatus::SENT->value)
                    ->required(),
                Textarea::make('service_result')
                    ->label('Hasil / Catatan Penanganan Lembaga Rujukan')
                    ->columnSpanFull()
                    ->rows(3),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('referral_number')
            ->columns([
                TextColumn::make('referral_number')
                    ->label('No. Rujukan')
                    ->weight('bold')
                    ->searchable(),
                TextColumn::make('institution.name')
                    ->label('Lembaga Rujukan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('referral_date')
                    ->label('Tgl Rujukan')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof ReferralStatus ? $state->label() : $state)
                    ->color(fn ($state): string => $state instanceof ReferralStatus ? $state->color() : 'gray'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()->label('Buat Rujukan'),
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
