<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;
use App\Models\company;
use App\Models\department;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Select::make('company_id')
                    ->relationship('company', 'companyName')
                    ->searchable()
                    ->preload()
                    ->label('PT')
                    ->suffixAction(
                        Action::make('deleteCompany')
                            ->icon('heroicon-o-trash')
                            ->color('danger')
                            ->requiresConfirmation()
                            ->action(function ($state, callable $set) {

                                if (! $state) {
                                    Notification::make()
                                        ->title('Tidak ada data dipilih')
                                        ->warning()
                                        ->send();
                                    return;
                                }

                                $model = company::find($state);

                                if (! $model) {
                                    Notification::make()
                                        ->title('Data tidak ditemukan')
                                        ->danger()
                                        ->send();
                                    return;
                                }

                                $model->delete();

                                $set('company_id', null);

                                Notification::make()
                                    ->title('Company berhasil dihapus!')
                                    ->success()
                                    ->send();
                            })
                    )
                    ->createOptionForm([
                        TextInput::make('companyName')
                            ->label('Nama PT')
                            ->required(),
                    ])
                    ->createOptionAction(fn (Action $action) =>
                        $action
                            ->modalHeading('Tambahkan Company')
                            ->modalWidth('md')
                    )

                    ->editOptionForm([
                        TextInput::make('companyName')
                            ->label('Nama PT')
                            ->required(),
                    ])

                    ->editOptionAction(fn (Action $action) =>
                        $action
                            ->modalHeading('Edit Company')
                            ->modalWidth('md')
                    ),
                Select::make('department_id')
                    ->relationship('department', 'departmentName')
                    ->searchable()
                    ->preload()
                    ->label('Department')
                    ->suffixAction(
                        Action::make('deleteDepartment')
                            ->icon('heroicon-o-trash')
                            ->color('danger')
                            ->requiresConfirmation()
                            ->action(function ($state, callable $set) {

                                if (! $state) {
                                    Notification::make()
                                        ->title('Tidak ada data dipilih')
                                        ->warning()
                                        ->send();
                                    return;
                                }

                                $model = department::find($state);

                                if (! $model) {
                                    Notification::make()
                                        ->title('Data tidak ditemukan')
                                        ->danger()
                                        ->send();
                                    return;
                                }

                                $model->delete();

                                $set('department_id', null);

                                Notification::make()
                                    ->title('Department berhasil dihapus!')
                                    ->success()
                                    ->send();
                            })
                    )
                    ->createOptionForm([
                        TextInput::make('departmentName')
                            ->label('Nama Department')
                            ->required(),
                    ])

                    ->createOptionAction(fn (Action $action) =>
                        $action
                            ->modalHeading('Tambahkan Department')
                            ->modalWidth('md')
                    )

                    ->editOptionForm([
                        TextInput::make('departmentName')
                            ->label('Nama Department')
                            ->required(),
                    ])

                    ->editOptionAction(fn (Action $action) =>
                        $action
                            ->modalHeading('Edit Department')
                            ->modalWidth('md')
                    ),
                TextInput::make('password')
                    ->password()
                    ->revealable()
                    ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null) // hash kalau diisi
                    ->dehydrated(fn ($state) => filled($state)) // simpan cuma kalau ada isinya
                    ->required(fn ($operation) => $operation === 'create'), // wajib saat create aja
                Select::make('role')
                    ->label('Role')
                    ->options([
                        'Requester'     => 'Requester',
                        'Purchasing'    => 'Purchasing',
                        'Admin'         => 'Admin',
                        'ApproverMR'    => 'ApproverMR',
                        'ApproverPO'    => 'ApproverPO'
                    ])
                    ->required(),
            ]);
    }
}
