<?php

namespace App\Filament\Resources\PoDetails;

use App\Filament\Resources\PoDetails\Pages\CreatePoDetails;
use App\Filament\Resources\PoDetails\Pages\EditPoDetails;
use App\Filament\Resources\PoDetails\Pages\ListPoDetails;
use App\Filament\Resources\PoDetails\Tables\PoDetailsTable;
use App\Filament\Resources\PoDetails\Schemas\PoDetailsForm;
use App\Filament\Resources\PoDetails\Schemas\PoItemsForm;
use App\Models\PoDetails;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PoDetailsResource extends Resource
{
    protected static ?string $model = PoDetails::class;
    
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;
    
    protected static ?string $recordTitleAttribute = 'Purchasing Orders';
    
    protected static ?string $navigationLabel = 'Purchasing Orders';
    
    protected static string|UnitEnum|null $navigationGroup = 'Purchasing';

    protected static ?int $navigationSort = 1; // biar urutannya jelas

    public static function shouldRegisterNavigation(): bool
    {
        $user = filament()->auth()->user();
        return $user && in_array($user->role, ['Admin','Purchasing']);
    }

    public static function canViewAny(): bool
    {
        $user = filament()->auth()->user();
        return $user && in_array($user->role, ['Admin','Purchasing']);
    }

    // Penting: biar pivot matRequests disave setelah PO tersimpan
    protected static bool $saveRelationshipsAfterSave = true;

    public static function getPluralLabel(): string
    {
        return 'Purchase Orders';
    }

    public static function getLabel(): string
    {
        return 'Purchase Order';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components(array_merge(
            PoDetailsForm::configure($schema)->getComponents(),
            [
                Section::make('')
                    ->schema(PoItemsForm::configure($schema)->getComponents())
                    ->columnSpanFull(),
            ]
        ));
    }

    public static function table(Table $table): Table
    {
        return PoDetailsTable::configure($table);
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
            'index' => ListPoDetails::route('/'),
            'create' => CreatePoDetails::route('/create'),
            'edit' => EditPoDetails::route('/{record}/edit'),
        ];
    }
}
