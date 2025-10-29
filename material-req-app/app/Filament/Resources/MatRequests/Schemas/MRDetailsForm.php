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

                    FileUpload::make('lampiran')
                        ->label('Lampiran')
                        ->multiple()
                        ->directory('lampiran')
                        ->reorderable()
                        ->downloadable()
                        ->openable()
                        ->preserveFilenames()
                        ->panelLayout('grid') // bisa 'grid', 'list', atau 'compact'
                        ->extraAttributes([
                                'style' => 'max-height: 200px; overflow-y: auto;',
                        ]),

                    TextInput::make('notes')
                        ->label('Notes'),
                ]),
        ]);
    }
}

