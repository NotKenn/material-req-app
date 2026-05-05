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
use Filament\Forms\Components\TextInput\Mask;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class PoItemsForm
{
    public static function updateGrandTotal($get, $set)
    {
        $items = $get('../../items') ?? [];

        $sum = collect($items)->sum(function ($item) {
            return (float) preg_replace('/[^0-9]/', '', (string) ($item['total'] ?? 0));
        });

        $set('../../grand_total', number_format($sum, 0, ',', '.'));
    }
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
                        })
                        ->where('isFulfilled', '=', '0');
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
                                ->options(fn() => itemmaster::pluck('itemName', 'itemName'))
                                ->reactive()
                                ->live()
                                ->searchable()
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
                                    return $item->itemName; // karena key kamu itemName
                                })
                                ->afterStateUpdated(function ($state, Set $set) {

                                    $item = itemmaster::where('itemName', $state)->first();

                                    if ($item) {
                                        $set('note', $item->itemDesc);
                                    }

                                })
                                ->label('Item')
                                ->reactive()
                                ->dehydrated(),
                            TextInput::make('qty')
                            ->numeric()
                            ->afterStateUpdated(function ($state, $set, $get) {
                                $price = (float) preg_replace('/[^0-9]/', '', (string) $get('price'));
                                $qty   = (float) $state;

                                $amount = $qty * $price;

                                $discountInput = $get('discount') ?? 0;
                                $discountValue = 0;

                                if (is_string($discountInput) && str_contains($discountInput, '%')) {
                                    $percent = (float) str_replace('%', '', $discountInput);
                                    $discountValue = ($percent / 100) * $amount;
                                } else {
                                    $discountValue = (float) preg_replace('/[^0-9]/', '', (string) $discountInput);
                                }

                                $total = max($amount - $discountValue, 0);

                                $set('amount', number_format($amount, 0, ',', '.'));
                                $set('subtotal', $amount);
                                $set('total', number_format($total, 0, ',', '.'));
                                self::updateGrandTotal($get, $set);
                            })
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
                                ->debounce(250)
                                ->required()
                                ->afterStateUpdated(function ($state, $set, $get) {
                                    $clean = preg_replace('/[^0-9]/', '', (string) $state);

                                    $formatted = $clean ? number_format((int) $clean, 0, ',', '.') : null;

                                    $set('price', $formatted);

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

                                    $total = max($amount - $discountValue, 0);

                                    $set('amount', number_format($amount, 0, ',', '.'));
                                    $set('subtotal', $amount);
                                    $set('total', number_format($total, 0, ',', '.'));
                                    self::updateGrandTotal($get, $set);
                                })
                                ->dehydrateStateUsing(fn($state) => preg_replace('/[^0-9]/', '', (string) $state)),

                            TextInput::make('amount')
                                ->label('Jumlah')
                                ->readOnly()
                                ->dehydrateStateUsing(fn($state)
                                => preg_replace('/[^0-9]/', '', (string) $state)
                                ),

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
                                $raw = (string) $state;

                                if (!str_contains($raw, '%')) {
                                    $clean = preg_replace('/[^0-9]/', '', $raw);
                                    $formatted = $clean ? number_format((int) $clean, 0, ',', '.') : null;
                                    $set('discount', $formatted);
                                }

                                $subtotal = (float) ($get('subtotal') ?? 0);

                                if (str_contains($raw, '%')) {
                                    $percent = (float) str_replace('%', '', $raw);
                                    $discountValue = ($percent / 100) * $subtotal;
                                } else {
                                    $discountValue = (float) preg_replace('/[^0-9]/', '', $raw);
                                }

                                $total = max($subtotal - $discountValue, 0);

                                $set('total', number_format($total, 0, ',', '.'));

                                // 🔥 INI HARUS PALING AKHIR
                                self::updateGrandTotal($get, $set);
                            })
                            ->dehydrateStateUsing(fn($state) => $state),

                            TextInput::make('total')
                                ->label('Total')
                                ->readOnly()
                                ->dehydrateStateUsing(fn($state)
                                => preg_replace('/[^0-9]/', '', (string) $state)
                                ),
                        ])
                        ->columns(4)
                        ->columnSpanFull(),
                ])
                ->columnSpanFull(),
                TextInput::make('grand_total')
                ->label('Grand Total')
                ->readOnly()
                ->reactive()
                ->dehydrateStateUsing(fn($state)
                    => preg_replace('/[^0-9]/', '', (string) $state)
                ),
        ]);
    }
}
