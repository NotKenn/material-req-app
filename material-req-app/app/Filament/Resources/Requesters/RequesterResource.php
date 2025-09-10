<?php

namespace App\Filament\Resources\Requesters;

use App\Filament\Resources\Requesters\Pages\CreateRequester;
use App\Filament\Resources\Requesters\Pages\EditRequester;
use App\Filament\Resources\Requesters\Pages\ListRequesters;
use App\Filament\Resources\Requesters\Schemas\RequesterForm;
use App\Filament\Resources\Requesters\Tables\RequestersTable;
use App\Models\requesters;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class RequesterResource extends Resource
{
    protected static ?string $model = requesters::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $recordTitleAttribute = 'Requester';
        
    protected static string|UnitEnum|null $navigationGroup = 'Material Request';

    protected static ?int $navigationSort = 2; // biar urutannya jelas

    public static function shouldRegisterNavigation(): bool
    {
        $user = filament()->auth()->user();
        return $user && in_array($user->role, ['Admin','User']);
    }

    public static function canViewAny(): bool
    {
        $user = filament()->auth()->user();
        return $user && in_array($user->role, ['Admin','User']);
    }


    public static function form(Schema $schema): Schema
    {
        return RequesterForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RequestersTable::configure($table);
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
            'index' => ListRequesters::route('/'),
            'create' => CreateRequester::route('/create'),
            'edit' => EditRequester::route('/{record}/edit'),
        ];
    }
}
