<?php

namespace App\Filament\Resources\MatRequests\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class MRItemsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('MR Items')
            ->schema([
                Repeater::make('mr_items')
                ->relationship('mrItems')
                        ->label('Items')
                        ->schema([
                            // Nama item full width
                            TextInput::make('itemName')
                                ->label('Item Name')
                                ->required(),

                            // Row bawah: Qty + Unit
                            Grid::make(2)
                                ->schema([
                                    TextInput::make('Qty')
                                        ->label('Quantity')
                                        ->required(),
                                    TextInput::make('satuan')
                                        ->label('Unit')
                                        ->required(),
                                ]),
                        ])
                        ->createItemButtonLabel('Add Item'),
                ])
                ->extraAttributes([
                    'style' => 'max-height:400px; overflow-y:auto; display:block;',
                ]),
        ]);
    }
}
