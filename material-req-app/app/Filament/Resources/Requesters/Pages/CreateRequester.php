<?php

namespace App\Filament\Resources\Requesters\Pages;

use App\Filament\Resources\Requesters\RequesterResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRequester extends CreateRecord
{
    protected static string $resource = RequesterResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
