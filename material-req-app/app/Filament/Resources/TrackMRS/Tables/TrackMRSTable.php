<?php

namespace App\Filament\Resources\TrackMRS\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;

class TrackMRSTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->columns([
                TextColumn::make('kodeRequest')
                    ->searchable(),
                TextColumn::make('requester.namaPT')
                    ->label('Requester')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Proses PO')
                    ->searchable(),
                TextColumn::make('po_file')
                    ->url(fn ($record) => $record->po_file ? asset('storage/' . $record->po_file) : null, shouldOpenInNewTab: true)
                    ->formatStateUsing(fn ($state) => $state ? 'Download' : '-') 
                    ->color('info')
                    ->searchable(),
            ])
            ->filters([
                Filter::make('myRequests')
                ->label('My Requests')
                ->query(function ($query) {
                $user = filament()->auth()->user();

                if ($user && $user->role === 'User') {
                    return $query->where('user_id', $user->id);
                }

                    return $query; // biarkan tanpa filter kalau bukan role User
                })
                ,
            ])
            ->recordActions([
                EditAction::make()
                ->visible(fn ($record) 
                =>in_array(filament()->auth()->user()?->role, ['Admin', 'Purchasing'])
                )
                // || $record->user_id === filament()->auth()->id() //yg ini pindahin ke atas
                                                                    //disamping kiri tutup kurung yg melayang 
                                                                    //ni klo mau dipake
                ->label('Approving'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
