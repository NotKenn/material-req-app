<?php

namespace App\Filament\Schemas;

use App\Models\matRequestItems;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select; 
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PoItemsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Material Requests')
                ->schema([
                    Select::make('matRequests')
                        ->label('Pilih Material Request')
                        ->multiple()
                        ->relationship('matRequests', 'kodeRequest')
                        ->searchable()
                        ->reactive()
                        ->preload()
                        ->afterStateHydrated(function ($state, $set, $get, $record) {
                            if ($record?->id) {
                                $set('matRequests', $record->matRequests->pluck('id')->toArray());
                            }
                        })
                        ->afterStateUpdated(function ($state, $set, $get, $record) {
                            if (empty($state)) {
                                $set('items', []);
                                return;
                            }

                            $currentMR = $record?->matRequests->pluck('id')->sort()->values()->toArray() ?? [];
                            $newMR     = collect($state)->sort()->values()->toArray();

                            if ($currentMR === $newMR) return;

                            // Hanya ambil sampai unit
                            $items = matRequestItems::whereIn('mr_id', $state)->get()->map(function ($item) {
                                return [
                                    'mr_item_id' => $item->id,
                                    'itemName'   => $item->itemName ?? '',
                                    'qty'        => $item->Qty ?? 0,
                                    'unit'       => $item->satuan ?? $item->uom ?? '',
                                ];
                            })->toArray();

                            $set('items', $items);
                        }),
                ])
                ->columns(1)
                ->columnSpanFull(),

            Section::make('Purchase Order Items')
                ->schema([
                    Repeater::make('items')
                        ->relationship('items')
                        ->afterStateHydrated(function ($state, $set) {
                        })
                        ->schema([
                            Hidden::make('mr_item_id'),

                            TextInput::make('itemName')
                                ->label('Item')
                                ->disabled()
                                ->dehydrated(),

                            TextInput::make('qty')
                                ->numeric()
                                ->disabled()
                                ->dehydrated(),

                            TextInput::make('unit')
                                ->dehydrated(),

                            TextInput::make('price')
                                ->label('Harga')
                                ->suffix('Rp')
                                ->reactive()
                                ->debounce(500)
                                ->required()
                                ->afterStateUpdated(function ($state, $set, $get) {
                                    $qty   = (float) ($get('qty') ?? 0);
                                    $price = (float) preg_replace('/[^0-9]/', '', (string) $state);
                                    $amount = $qty * $price;

                                    $discountInput = $get('discount') ?? 0;
                                    $discountValue = 0;

                                    if (is_string($discountInput) && str_contains($discountInput, '%')) {
                                        $percent = (float) str_replace('%', '', $discountInput);
                                        $discountValue = ($percent / 100) * $amount;
                                    } else {
                                        $discountValue = (float) preg_replace('/[^0-9]/', '', (string) $discountInput);
                                    }

                                    $set('amount', $amount);
                                    $set('subtotal', $amount);
                                    $set('total', max($amount - $discountValue, 0));
                                })
                                ->dehydrateStateUsing(fn($state) => preg_replace('/[^0-9]/', '', (string) $state)),

                            TextInput::make('amount')
                                ->label('Jumlah')
                                ->numeric()
                                ->dehydrated(),

                            TextInput::make('subtotal')
                                ->numeric()
                                ->dehydrated(),

                            TextInput::make('discount')
                                ->label('Diskon')
                                ->type('text')
                                ->default('0')
                                ->reactive()
                                ->debounce(500)
                                ->afterStateUpdated(function ($state, $set, $get) {
                                    $subtotal = (float) ($get('subtotal') ?? 0);
                                    $discountValue = 0;

                                    if (is_string($state) && str_contains($state, '%')) {
                                        $percent = (float) str_replace('%', '', $state);
                                        $discountValue = ($percent / 100) * $subtotal;
                                    } else {
                                        $discountValue = (float) preg_replace('/[^0-9]/', '', (string) $state);
                                    }

                                    $set('total', max($subtotal - $discountValue, 0));
                                })
                                ->dehydrateStateUsing(fn($state) => $state),

                            TextInput::make('total')
                                ->numeric()
                                ->dehydrated(),
                        ])
                        ->columns(4)
                        ->columnSpanFull(),
                ])
                ->columnSpanFull(),
        ]);
    }
}
