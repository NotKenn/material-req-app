<?php

namespace App\Filament\Resources\MatRequests\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;

class MatRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
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
                ->default(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
