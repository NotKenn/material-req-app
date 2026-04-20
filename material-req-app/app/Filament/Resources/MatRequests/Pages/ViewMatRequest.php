<?php

namespace App\Filament\Resources\MatRequests\Pages;

use App\Filament\Resources\MatRequests\MatRequestResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;

class ViewMatRequest extends ViewRecord
{
    protected static string $resource = MatRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // EditAction::make(),
            Action::make('back')
                ->label('Back')
                ->icon(Heroicon::ArrowLeftEndOnRectangle)
                ->url('javascript:history.back()')
                ->color(Color::Orange),
        ];
    }
}
