<?php

namespace App\Filament\Resources\MatRequests\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MatRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('kodeRequest')
                    ->required(),
                TextInput::make('requester_id')
                    ->numeric()
                    ->default(null),
                TextInput::make('status')
                    ->required(),
                TextInput::make('po_file')
                    ->required(),
            ]);
    }
}
