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
                TextInput::make('namaPT')
                ->label('Nama PT')
                ->required(),
                TextInput::make('alamatPT')
                ->label('Alamat PT')
                ->required(),
                TextInput::make('namaKontakPT')
                ->label('Nama Kontak PT')
                ->required(),
                TextInput::make('noTelpKontakPT')
                ->label('No. Telp Kontak PT')
                ->required(),
            ]);
    }
}
