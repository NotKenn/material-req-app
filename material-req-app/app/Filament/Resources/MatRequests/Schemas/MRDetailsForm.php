<?php

namespace App\Filament\Resources\MatRequests\Schemas;

use App\Models\mrDetails;
use App\Models\matRequest;
use App\Models\requesters;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MRDetailsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('MR Details')
            ->relationship('mrDetails')
                ->schema([
                    DatePicker::make('tanggal')
                        ->label('Tanggal')
                        ->required(),

                    DatePicker::make('tanggalPerlu')
                        ->label('Tanggal Perlu')
                        ->required(),

                    TextInput::make('lokasiPengantaran')
                        ->label('Lokasi Pengantaran')
                        ->required(),

                    FileUpload::make('lampiran')
                        ->label('Lampiran'),

                    TextInput::make('notes')
                        ->label('Notes'),
                ]),
        ]);
    }
}

