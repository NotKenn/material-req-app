<?php

namespace App\Filament\Resources\MatRequests\Schemas;

use App\Models\lastNumbers;
use App\Models\mrDetails;
use App\Models\matRequest;
use App\Models\penerima;
use App\Models\requesters;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Alignment;

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
                    ->label('Pemilik MR')
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
                        ->label('Nama Kontak')
                        ->required(),
                        TextInput::make('noTelpKontakPT')
                        ->label('Nomor Kontak')
                        ->required(),
                    ])
                    ->createOptionAction(function (Action $action) {
                        return $action
                            ->modalHeading('Create Pemilik MR');
                    })
                    ->required(),

                    Select::make('penerima_id')
                        ->label('Penerima Barang (Kosongkan Jika Penerima adalah Pemilik MR)')
                        ->relationship('penerima', 'namaPenerima')
                        ->default(null)
                        ->suffixActions([
                        Action::make('testDelete')
                            ->label('Del')
                            ->icon('heroicon-o-trash')
                            ->color('danger')
                            ->requiresConfirmation()
                            ->modalHeading('Delete?')
                            ->modalDescription('Press Confirm to Delete')
                            ->action(function ($state, callable $set) {
                                if (! $state) {
                                    Notification::make()
                                        ->title('Tidak ada data dipilih')
                                        ->warning()
                                        ->send();
                                    return;
                                }

                                $model = \App\Models\Penerima::find($state);

                                if (! $model) {
                                    Notification::make()
                                        ->title('Data tidak ditemukan')
                                        ->danger()
                                        ->send();
                                    return;
                                }

                                $model->delete();

                                // Reset dropdown ke null setelah delete
                                $set('penerima_id', null);

                                Notification::make()
                                    ->title('Penerima berhasil dihapus!')
                                    ->success()
                                    ->send();
                            })
                        ])
                        // FORM UNTUK TAMBAH BARU
                        ->createOptionForm([
                            TextInput::make('namaPenerima')
                                ->label('Nama Penerima')
                                ->required(),
                            TextInput::make('lokasiPengantaran')
                                ->label('Lokasi Pengantaran')
                                ->required(),
                            TextInput::make('nomorKontak')
                                ->label('Kontak Penerima')
                                ->required(),
                        ])
                        ->createOptionAction(function (Action $action) {
                            return $action->modalHeading('Tambah Penerima Barang');
                        })

                        // FORM UNTUK EDIT DATA YANG ADA
                        ->editOptionForm([
                            TextInput::make('namaPenerima')
                                ->label('Nama Penerima')
                                ->required(),
                            TextInput::make('lokasiPengantaran')
                                ->label('Lokasi Pengantaran')
                                ->required(),
                            TextInput::make('nomorKontak')
                                ->label('Kontak Penerima')
                                ->required(),
                        ])
                        ->editOptionAction(fn (Action $action) => $action->modalHeading('Edit Penerima Barang')->modalWidth('md')
                    ),

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
