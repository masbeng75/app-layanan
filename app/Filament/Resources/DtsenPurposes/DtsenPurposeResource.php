<?php

namespace App\Filament\Resources\DtsenPurposes;

use App\Filament\Resources\DtsenPurposes\Pages\CreateDtsenPurpose;
use App\Filament\Resources\DtsenPurposes\Pages\EditDtsenPurpose;
use App\Filament\Resources\DtsenPurposes\Pages\ListDtsenPurposes;
use App\Filament\Resources\DtsenPurposes\Schemas\DtsenPurposeForm;
use App\Filament\Resources\DtsenPurposes\Tables\DtsenPurposesTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DtsenPurposeResource extends Resource
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentCheck;

    protected static string|\UnitEnum|null $navigationGroup = 'Data Master';

    protected static ?string $navigationLabel = 'Tujuan SK DTSEN';

    protected static ?string $modelLabel = 'Tujuan SK DTSEN';

    protected static ?string $pluralModelLabel = 'Data Tujuan SK DTSEN';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return DtsenPurposeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DtsenPurposesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDtsenPurposes::route('/'),
            'create' => CreateDtsenPurpose::route('/create'),
            'edit' => EditDtsenPurpose::route('/{record}/edit'),
        ];
    }
}
