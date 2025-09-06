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
                TextInput::make('vendorName')
                ->label('Nama Vendor')
                ->required(),
                TextInput::make('alamat')
                ->label('Alamat')
                ->required(),
                TextInput::make('namaKontak')
                ->label('Nama Kontak')
                ->required(),
                TextInput::make('nomorTelepon')
                ->label('Nomor Telepon')
                ->required(),
            ]);
    }
}
