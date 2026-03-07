<?php

namespace App\Filament\Resources\ItemMasters;

use App\Filament\Resources\ItemMasters\Pages\CreateItemMaster;
use App\Filament\Resources\ItemMasters\Pages\EditItemMaster;
use App\Filament\Resources\ItemMasters\Pages\ListItemMasters;
use App\Filament\Resources\ItemMasters\Pages\ViewItemMaster;
use App\Filament\Resources\ItemMasters\Schemas\ItemMasterForm;
use App\Filament\Resources\ItemMasters\Schemas\ItemMasterInfolist;
use App\Filament\Resources\ItemMasters\Tables\ItemMastersTable;
use App\Models\itemmaster as Itemmaster;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ItemMasterResource extends Resource
{
    protected static ?string $model = Itemmaster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Item Master';

    protected static ?string $navigationLabel = 'Item Master';

    public static function getPluralLabel(): string
    {
        return 'Item Master';
    }

    public static function getLabel(): string
    {
        return 'Item Master';
    }

    public static function form(Schema $schema): Schema
    {
        return ItemMasterForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ItemMasterInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ItemMastersTable::configure($table);
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
            'index' => ListItemMasters::route('/'),
            'create' => CreateItemMaster::route('/create'),
            'view' => ViewItemMaster::route('/{record}'),
            'edit' => EditItemMaster::route('/{record}/edit'),
        ];
    }
}
