<?php

namespace App\Filament\Resources\MatRequests\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class MRItemsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('')
            ->schema([
                Repeater::make('members')
                ->label('MR Items')
                ->relationship('mrItems')
                ->table([
                    TableColumn::make('Item Name'),
                    TableColumn::make('Keterangan'),
                    TableColumn::make('Qty'),
                    TableColumn::make('Satuan'),
                ])
                ->schema([
                    TextInput::make('itemName')
                        ->label('Item Name')
                        ->required(),
                    TextInput::make('notes')
                        ->label('Keterangan'),
                    TextInput::make('Qty')
                        ->label('Quantity')
                        ->required(),
                    TextInput::make('satuan')
                        ->label('Satuan')
                        ->required(),
                ])
                ->createItemButtonLabel('Add Item'),
            // Back to default, cluttered Item lists


                // Repeater::make('mr_items')
                // ->relationship('mrItems')
                //         ->label('Items')
                //         ->schema([
                //             // Nama item full width ke sini klo mau


                //             // Row bawah: Qty + Unit
                //             Grid::make(4)
                //                 ->schema([
                //                     TextInput::make('itemName')
                //                         ->label('Item Name')
                //                         ->required(),
                //                     TextInput::make('notes')
                //                         ->label('Keterangan'),
                //                     TextInput::make('Qty')
                //                         ->label('Quantity')
                //                         ->required(),
                //                     TextInput::make('satuan')
                //                         ->label('Satuan')
                //                         ->required(),
                //                 ]),
                //         ])
                //         ->createItemButtonLabel('Add Item'),
                // // ])
                // ->extraAttributes([
                //     'style' => 'max-height:400px; overflow-y:auto; display:block;',


                ]),
        ]);
    }
}
