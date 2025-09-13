<?php

namespace App\Filament\Resources\TrackMRS\Pages;

use App\Filament\Resources\TrackMRS\TrackMRResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTrackMRS extends ListRecords
{
    protected static string $resource = TrackMRResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
}
