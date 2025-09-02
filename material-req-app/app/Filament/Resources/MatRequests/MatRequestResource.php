<?php

namespace App\Filament\Resources\MatRequests;

use App\Filament\Resources\MatRequests\Pages\CreateMatRequest;
use App\Filament\Resources\MatRequests\Pages\EditMatRequest;
use App\Filament\Resources\MatRequests\Pages\ListMatRequests;
use App\Filament\Resources\MatRequests\Schemas\MatRequestForm;
use App\Filament\Resources\MatRequests\Tables\MatRequestsTable;
use App\Models\matRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MatRequestResource extends Resource
{
    protected static ?string $model = matRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ClipboardDocumentList;

    protected static ?string $recordTitleAttribute = 'Material Request';

    public static function form(Schema $schema): Schema
    {
        return MatRequestForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MatRequestsTable::configure($table);
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
            'index' => ListMatRequests::route('/'),
            'create' => CreateMatRequest::route('/create'),
            'edit' => EditMatRequest::route('/{record}/edit'),
        ];
    }
}
