<?php

namespace App\Filament\Resources\TrackMRS\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;

class TrackMRForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
            Section::make('Main MR')
                ->schema([
                    TextInput::make('kodeRequest')
                        ->label('Kode Request')
                        ->required()
                        ->readonly(),

                    Select::make('requester_id')
                        ->label('Requester')
                        ->relationship('requester', 'namaPT')
                        ->default(null)
                        ->createOptionForm([
                            TextInput::make('namaPT')
                            ->label('Nama PT')
                            ->required(),
                            TextInput::make('alamatPT')
                            ->label('Alamat PT')
                            ->required(),
                            TextInput::make('namaKontakPT')
                            ->label('Nama Kontak PT')
                            ->required(),
                            TextInput::make('noTelpKontakPT')
                            ->label('No. Telp Kontak PT')
                            ->required(),
                        ])
                        ->required()
                        ->disabled()
                        ->dehydrated(true),

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
                        ->default('New'),

                    TextInput::make('reject_note')
                    ->label('Note Reject'),

                    FileUpload::make('po_file') //kerjaan PO nanti, ini kasih kesana di form khusus mr approved or some shit
                        ->label('PO File')
                        ->disk('public')
                        ->directory('po-files')
                        ->default(null),
                ])
                ->columns(1) // Semua field full width
                ->columnSpanFull()
                ->extraAttributes([
                    'style' => 'border-radius:0.5rem;width:100%',
                ]),

        ]);
    }
}
