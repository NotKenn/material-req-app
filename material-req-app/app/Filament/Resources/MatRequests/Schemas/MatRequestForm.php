<?php

namespace App\Filament\Resources\MatRequests\Schemas;

use App\Models\mrDetails;
use App\Models\matRequest;
use App\Models\requesters;
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
                    ->required(),

                Select::make('requester_id')
                    ->label('Requester')
                    ->relationship('requester', 'namaPT')
                    ->default(null)
                    ->required(),

                ToggleButtons::make('status')
                    ->label('Status')
                    ->options([
                        'New'        => 'New Request',
                        'Processing' => 'Processing',
                        'Approved'   => 'Approved',
                        'Revision'   => 'Revision',
                        'Rejected'   => 'Rejected',
                    ]) 
                    ->colors([
                        'New'        => 'info',      // biru
                        'Processing' => 'warning', // kuning
                        'Approved'   => 'success', // hijau
                        'Revision'   => Color::Slate,
                        'Rejected'   => 'danger', // merah
                    ])
                    ->inline()
                    ->columnSpanFull()
                    ->default('New'),

                FileUpload::make('po_file') //kerjaan PO nanti, ini kasih kesana di form khusus mr approved or some shit
                    ->label('PO File')
                    ->default(null),
            ])
            ->columns(1) // Semua field full width
            ->extraAttributes([
                'style' => 'border-radius:0.5rem;width:100%',
            ]),
            
    ]);
    }
}
