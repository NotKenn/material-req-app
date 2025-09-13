<?php

namespace App\Filament\Resources\TrackMRS;

use App\Filament\Resources\TrackMRS\Pages\CreateTrackMR;
use App\Filament\Resources\TrackMRS\Pages\EditTrackMR;
use App\Filament\Resources\TrackMRS\Pages\ListTrackMRS;
use App\Filament\Resources\TrackMRS\Schemas\TrackMRForm;
use App\Filament\Resources\TrackMRS\Tables\TrackMRSTable;
use App\Models\matRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TrackMRResource extends Resource
{
    protected static ?string $model = matRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Track MR';

    protected static ?string $navigationLabel = 'Track/Acc Requests';

    public static function getPluralLabel(): string
    {
        return 'Track/Acc Requests';
    }

    public static function getLabel(): string
    {
        return 'Track/Acc Requests';
    }

    public static function form(Schema $schema): Schema
    {
        return TrackMRForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TrackMRSTable::configure($table);
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
            'index' => ListTrackMRS::route('/'),
            'create' => CreateTrackMR::route('/create'),
            'edit' => EditTrackMR::route('/{record}/edit'),
        ];
    }
}
