<?php

namespace App\Filament\Resources\Vendors\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class VendorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('vendorName'),
                TextInput::make('alamat'),
                TextInput::make('namaKontak'),
                TextInput::make('nomorTelepon'),
            ]);
    }
}
