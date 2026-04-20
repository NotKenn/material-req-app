<?php

namespace App\Filament\Resources\PoDetails\Schemas;

use App\Models\lastNumbers;
use App\Models\matRequest;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PoDetailsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Purchase Order Details')
                    ->schema([
                        TextInput::make('companyName')
                            ->disabled()
                            ->dehydrated(true)
                            ->required(),

                        TextInput::make('officeAddress')
                            ->disabled()
                            ->dehydrated(true)
                            ->required(),

                        TextInput::make('contactName')
                            ->disabled()
                            ->dehydrated(true)
                            ->required(),

                        TextInput::make('phone')
                            ->disabled()
                            ->dehydrated(true)
                            ->tel()
                            ->required(),

                        DatePicker::make('date')

                            ->required(),

                        TextInput::make('po_number')
                            ->label('Nomor PO')
                            ->default(fn () => lastNumbers::peek('PO'))
                            ->disabled()
                            ->dehydrated(true),

                        Select::make('vendorID')
                            ->label('Nama Vendor')
                            ->relationship('vendor', 'vendorName')
                            ->default(null)
                            ->createOptionForm([
                                TextInput::make('vendorName')
                                ->label('Nama Vendor')
                                ->required(),
                                TextInput::make('alamat')
                                ->label('Alamat')
                                ->required(),
                                TextInput::make('namaKontak')
                                ->label('Nama Kontak')
                                ->required(),
                                TextInput::make('nomorTelepon')
                                ->label('Nomor Telepon')
                                ->required(),
                            ])
                            ->required(),

                        TextInput::make('termOfPayment')
                            ->required(),
                        TextInput::make('gl_disc')
                            ->label('Global Discount (Kosongkan jika tidak ada)'),
                        Select::make('isRevised')
                        ->options([
                            'Yes'   => 'Yes',
                            'No'    => 'No'
                        ])
                        ->label('Revisi'),
                        Textarea::make('remarks')
                        ->label('Remarks')
                        ->rows(10)
                        ->columns(1)
                        ->columnSpanFull()
                        ->default('*Pembayaran akan dilakukan oleh nama PT yang tertera di "Company Name" yang tertera dibagian atas
* Setiap Delivery Order wajib dilampirkan PO, jika tidak melampirkan PO maka barang harus dikembalikan
* Pada saat pengantaran wajib mencantumkan kuantiti barang di Delivery Order yang sesuai dengan PO.
* Yang berwenang menerima barang hanya nama yang tertera diatas kolom Deliver To.

Sebelum pengantaran mohon hubungi contact person penerima terlebih dahulu
                        '),
                                ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
