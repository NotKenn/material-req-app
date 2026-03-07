<?php

namespace App\Filament\Resources\ItemMasters\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ItemMasterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('itemName')
                ->label('Nama Item'),
                TextInput::make('itemDesc')
                ->label('Deskripsi Item')
            ]);
    }
}
