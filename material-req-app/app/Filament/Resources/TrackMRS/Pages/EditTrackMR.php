<?php

namespace App\Filament\Resources\TrackMRS\Pages;

use App\Filament\Resources\TrackMRS\TrackMRResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTrackMR extends EditRecord
{
    protected static string $resource = TrackMRResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
