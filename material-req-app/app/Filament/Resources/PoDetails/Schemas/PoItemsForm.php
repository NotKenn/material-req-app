<?php

namespace App\Filament\Resources\PoDetails\Schemas;

use App\Models\itemmaster;
use App\Models\MatRequestItems;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class PoItemsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            // ===============================
            // MATERIAL REQUEST SECTION
            // ===============================
            Section::make('Material Requests')
                ->schema([
                    Select::make('matRequests')
                    ->label('Pilih Material Request')
                    ->multiple()
                    ->relationship('matRequests', 'kodeRequest', function ($query) {
                        $query->whereHas('latestApproval', function ($q) {
                            $q->where('status', 'Approved');
                        });
                    // ->where('status', '!=', 'Closed');
                    })
                    ->searchable()
                    ->preload()
                    ->reactive()
                    ->dehydrated(true) // wajib biar ikut submit
                    ->afterStateHydrated(function ($state, $set, $get, $record) {
                        if ($record?->id) {
                            // set pilihan MR waktu edit
                            $set('matRequests', $record->matRequests->pluck('id')->toArray());
                        }
                    })
                    ->afterStateUpdated(function ($state, $set, $get, $record) {
                        if (empty($state)) {
                            $set('items', []);
                            // reset juga field detail kalau MR kosong
                            $set('companyName', null);
                            $set('officeAddress', null);
                            $set('contactName', null);
                            $set('phone', null);
                            return;
                        }

                        $currentMR = $record?->matRequests->pluck('id')->sort()->values()->toArray() ?? [];
                        $newMR     = collect($state)->sort()->values()->toArray();

                        // kalau MR sama → jangan regenerate items, biar harga & discount user aman
                        if ($currentMR === $newMR) return;

                        // ambil items dari MR
                        $items = MatRequestItems::whereIn('mr_id', $state)
                            ->get()
                            // ->filter(function ($item) {
                            //  // skip item yang sudah habis (remainingQty = 0 atau kurang)
                            //     // return $item->remainingQty === null || $item->remainingQty > 0;
                            // })
                            ->map(function ($item) {
                                // $qty = $item->remainingQty ?? $item->Qty;

                                return [
                                    'mr_item_id' => $item->id,
                                    'itemName'   => $item->itemName ?? '',
                                    'qty'        => $item->Qty ?? 0,
                                    'unit'       => $item->satuan ?? $item->uom ?? '',
                                    'price'      => null,
                                    'amount'     => null,
                                    'subtotal'   => null,
                                    'discount'   => '0',
                                    'total'      => null,
                                    'note'      => $item->notes ?? '',
                                ];
                            })->toArray();

                        $set('items', $items);

                        // set field detail (ambil MR pertama sebagai sumber)
                        if ($mr = \App\Models\MatRequest::with('requester')->find($state[0])) {
                            // ambil langsung dari MR
                            $set('officeAddress', $mr->requester->alamatPT ?? '');
                            $set('contactName',  $mr->requester->namaKontakPT ?? '');
                            $set('phone',        $mr->requester->noTelpKontakPT ?? '');

                            // ambil company dari relasi requester
                            $set('companyName', $mr->requester->namaPT ?? '');
                        }
                    }),
                ])
                ->columns(1)
                ->columnSpanFull(),
            // ===============================
            // PURCHASE ORDER ITEMS SECTION
            // ===============================
            Section::make('Purchase Order Items')
                ->schema([
                    Repeater::make('items')
                        ->relationship('items')
                        ->dehydrated(true) // biar ikut ke DB
                        ->schema([
                            Hidden::make('mr_item_id'),

                            Select::make('itemName')
                                ->options(itemmaster::pluck('itemName', 'itemName'))
                                ->searchable()
                                ->live()
                                ->afterStateUpdated(function ($state, Set $set) {

                                    $item = itemmaster::where('itemName', $state)->first();

                                    if ($item) {
                                        $set('note', $item->itemDesc);
                                    }

                                })
                                ->label('Item')
                                ->dehydrated(),

                            TextInput::make('qty')
                            ->numeric()
                            ->dehydrated(),

                            TextInput::make('unit')
                            ->dehydrated(),

                            Textarea::make('note')
                                ->label('Keterangan')
                                ->rows(1)
                                ->autosize(),

                            TextInput::make('price')
                                ->label('Harga / Satuan')
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
                                ->dehydrated(true),

                            TextInput::make('subtotal')
                                ->numeric()
                                ->dehydrated(true)
                                ->hidden(),

                            TextInput::make('discount')
                                ->label('Diskon')
                                ->type('text')
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
                                ->dehydrated(true),
                        ])
                        ->columns(4)
                        ->columnSpanFull(),
                ])
                ->columnSpanFull(),
        ]);
    }
}
