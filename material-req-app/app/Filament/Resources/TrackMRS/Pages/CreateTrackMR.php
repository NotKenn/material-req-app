<?php

namespace App\Filament\Resources\TrackMRS\Pages;

use App\Filament\Resources\TrackMRS\TrackMRResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTrackMR extends CreateRecord
{
    protected static string $resource = TrackMRResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
