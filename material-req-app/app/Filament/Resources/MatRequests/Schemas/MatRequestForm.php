<?php

namespace App\Filament\Resources\MatRequests\Schemas;

use App\Models\lastNumbers;
use App\Models\mrDetails;
use App\Models\matRequest;
use App\Models\requesters;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;

class MatRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
    ->components([
        Section::make('Main MR')
            ->schema([
                TextInput::make('kodeRequest')
                    ->label('Kode Request')
                    ->default(fn () => \App\Models\LastNumbers::peek('MR'))
                    ->disabled() // biar user ga ubah manual
                    ->dehydrated(true),

                Select::make('requester_id')
                    ->label('Penerima Barang')
                    ->relationship('requester', 'namaPT')
                    ->default(null)
                    ->createOptionForm([
                        TextInput::make('namaPT')
                        ->label('Nama PT')
                        ->required(),
                        TextInput::make('alamatPT')
                        ->label('Lokasi Delivery')
                        ->required(),
                        TextInput::make('namaKontakPT')
                        ->label('Penerima')
                        ->required(),
                        TextInput::make('noTelpKontakPT')
                        ->label('Kontak Penerima')
                        ->required(),
                    ])
                    ->createOptionAction(function (Action $action) {
                        return $action
                            ->modalHeading('Create Penerima Barang');
                    })
                    ->required(),

                ToggleButtons::make('status')
                    ->label('Status')
                    ->options([
                        'New'        => 'New Request',
                        'Processing' => 'Processing',
                        'Approved'   => 'Approved',
                        'Revision'   => 'Revision',
                        'Rejected'   => 'Rejected',
                        'Closed'     => 'Closed',
                    ])
                    ->colors([
                        'New'        => 'info',      // biru
                        'Processing' => 'warning', // kuning
                        'Approved'   => 'success', // hijau
                        'Revision'   => Color::Slate,
                        'Rejected'   => 'danger', // merah
                        'Closed'     => Color::Teal,
                    ])
                    ->inline()
                    ->columnSpanFull()
                    ->hidden(fn () => in_array(filament()->auth()->user()->role, ['User', 'MRSupervisor']))
                    ->disabled(fn () => in_array(filament()->auth()->user()->role, ['User', 'MRSupervisor']))
                    ->default('New'),

                FileUpload::make('po_file') //kerjaan PO nanti, ini kasih kesana di form khusus mr approved or some shit
                    ->label('PO File')
                    ->disk('public')
                    ->hidden(fn () => in_array(filament()->auth()->user()->role, ['User', 'MRSupervisor']))
                    ->disabled(fn () => in_array(filament()->auth()->user()->role, ['User', 'MRSupervisor']))
                    ->directory('po-files')
                    ->default(null),
                TextInput::make('address')
                    ->label('Office Address')
                    ->required(),
                TextInput::make('name')
                    ->label('Contact Name')
                    ->required(),
                TextInput::make('phone')
                    ->label('Phone')
                    ->required(),
                TextInput::make('departemen')
                    ->label('Departemen')
                    ->required(),
            ])
            ->columns(1) // Semua field full width
            ->extraAttributes([
                'style' => 'border-radius:0.5rem;width:100%',
            ]),

    ]);
    }
}
