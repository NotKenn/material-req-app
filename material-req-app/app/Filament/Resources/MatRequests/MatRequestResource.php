<?php

namespace App\Filament\Resources\MatRequests;

use App\Filament\Resources\MatRequests\Pages\CreateMatRequest;
use App\Filament\Resources\MatRequests\Pages\EditMatRequest;
use App\Filament\Resources\MatRequests\Pages\ListMatRequests;
use App\Filament\Resources\MatRequests\Schemas\MatRequestForm;
use App\Filament\Resources\MatRequests\Schemas\MRDetailsForm;
use App\Filament\Resources\MatRequests\Schemas\MRItemsForm;
use App\Filament\Resources\MatRequests\Tables\MatRequestsTable;
use App\Models\matRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class MatRequestResource extends Resource
{
    protected static ?string $model = matRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ClipboardDocumentList;

    protected static ?string $recordTitleAttribute = 'Material Request';

    protected static string|UnitEnum|null $navigationGroup = 'Material Request';

    protected static ?int $navigationSort = 2; // biar urutannya jelas

    public static function form(Schema $schema): Schema
    {
        return $schema->components(array_merge(
            MatRequestForm::configure($schema)->getComponents(),
            MRItemsForm::configure($schema)->getComponents(),
            [
            Section::make('')
            ->schema(MRDetailsForm::configure($schema)->getComponents())
            ->columnSpanFull(),
            ]
        ));
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
