<?php

namespace App\Filament\Resources\Requesters\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RequestersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('namaPT')->label('Nama PT'),
                TextColumn::make('alamatPT')->label('Alamat PT'),
                TextColumn::make('namaKontakPT')->label('Nama Kontak PT'),
                TextColumn::make('noTelpKontakPT')->label('No. Telp Kontak PT'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
