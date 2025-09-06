<?php

namespace App\Filament\Resources\PoDetails\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PoDetailsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('companyName')
                    ->label('Nama Perusahaan')
                    ->searchable(),
                TextColumn::make('officeAddress')
                    ->label('Alamat')
                    ->searchable(),
                TextColumn::make('contactName')
                    ->label('')
                    ->searchable(),
                TextColumn::make('po_number')
                    ->label('Nomor PO')
                    ->searchable(),
                TextColumn::make('date')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),
                TextColumn::make('termOfPayment')
                    ->label('Term of Payment')
                    ->searchable(),
                TextColumn::make('vendorID')
                    ->label('Vendor')
                    ->searchable(),
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
