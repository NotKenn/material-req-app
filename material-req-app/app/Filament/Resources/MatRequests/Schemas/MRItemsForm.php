<?php

namespace App\Filament\Resources\MatRequests\Schemas;

use App\Models\itemmaster;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;

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
                    TableColumn::make('Last Pembelian'),

                ])
                ->schema([
                    Select::make('itemName')
                    ->options(itemmaster::pluck('itemName', 'itemName'))
                    ->searchable()
                    ->live()
                    ->createOptionForm([
                        TextInput::make('itemName')
                            ->label('Item Name')
                            ->required(),

                        TextInput::make('itemDesc')
                            ->label('Description'),
                    ])
                    ->createOptionUsing(function (array $data) {

                        $item = itemmaster::create([
                            'itemName' => $data['itemName'],
                            'itemDesc' => $data['itemDesc'],
                        ]);

                        return $item->itemName; // ini penting karena key kamu itemName
                    })
                    ->afterStateUpdated(function ($state, Set $set) {

                        $item = itemmaster::where('itemName', $state)->first();

                        if ($item) {
                            $set('notes', $item->itemDesc);
                        }

                    })
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
                    TextInput::make('lastPembelian')
                    ->label('Detail Pembelian Terakhir'),
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
