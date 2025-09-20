<?php

namespace App\Filament\Resources\PoDetails\Schemas;

use App\Models\matRequest;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
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
                            ->required(),

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
                            ->label('Global Discount'),
                        Select::make('isRevised')
                        ->options([
                            'Yes'   => 'Yes',
                            'No'    => 'No'
                        ])
                        ->label('Revisi'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
