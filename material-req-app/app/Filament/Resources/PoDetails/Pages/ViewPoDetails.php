<?php

namespace App\Filament\Resources\PoDetails\Pages;

use App\Filament\Resources\PoDetails\PoDetailsResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;

class ViewPoDetails extends ViewRecord
{
    protected static string $resource = PoDetailsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // EditAction::make(),
            Action::make('back')
                ->label('Back')
                ->icon(Heroicon::ArrowLeftEndOnRectangle)
                ->url($this->getResource()::getUrl('index'))
                ->color(Color::Orange),
        ];
    }
}
