<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                Hidden::make('email_verified_at')
                    ->default(now()),
                TextInput::make('password')
                    ->password()
                    ->revealable()
                    ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null) // hash kalau diisi
                    ->dehydrated(fn ($state) => filled($state)) // simpan cuma kalau ada isinya
                    ->required(fn ($operation) => $operation === 'create'), // wajib saat create aja
                Select::make('role')
                    ->label('Role')
                    ->options([
                        'User'          => 'User',
                        'Purchasing'    => 'Purchasing',
                        'Admin'         => 'Admin',
                        'MRSupervisor'  => 'MRSupervisor',
                        'POSupervisor'  => 'POSupervisor'
                    ])
                    ->required(),
            ]);
    }
}
