<?php

namespace App\Filament\Resources\Vendors;

use App\Filament\Resources\Vendors\Pages\CreateVendor;
use App\Filament\Resources\Vendors\Pages\EditVendor;
use App\Filament\Resources\Vendors\Pages\ListVendors;
use App\Filament\Resources\Vendors\Schemas\VendorForm;
use App\Filament\Resources\Vendors\Tables\VendorsTable;
use App\Models\vendor;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class VendorResource extends Resource
{
    protected static ?string $model = Vendor::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static ?string $recordTitleAttribute = 'Vendor';    
    
    protected static string|UnitEnum|null $navigationGroup = 'Purchasing';

    protected static ?int $navigationSort = 2; // biar urutannya jelas

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


    public static function form(Schema $schema): Schema
    {
        
        return VendorForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VendorsTable::configure($table);
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
            'index' => ListVendors::route('/'),
            'create' => CreateVendor::route('/create'),
            'edit' => EditVendor::route('/{record}/edit'),
        ];
    }
}
