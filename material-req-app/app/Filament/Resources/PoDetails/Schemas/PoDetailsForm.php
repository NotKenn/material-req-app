<?php

namespace App\Filament\Resources\PoDetails\Schemas;

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
                            ->required(),

                        TextInput::make('officeAddress')
                            ->required(),

                        TextInput::make('contactName')
                            ->required(),

                        TextInput::make('phone')
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
                            ->required(),
                                
                        TextInput::make('termOfPayment')
                            ->required(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
