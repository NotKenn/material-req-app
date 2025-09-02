<?php

namespace App\Filament\Resources\Requesters\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RequesterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('namaPT')->label('Nama PT'),
                TextInput::make('alamatPT')->label('Alamat PT'),
                TextInput::make('namaKontakPT')->label('Nama Kontak PT'),
                TextInput::make('noTelpKontakPT')->label('No. Telp Kontak PT'),
            ]);
    }
}
