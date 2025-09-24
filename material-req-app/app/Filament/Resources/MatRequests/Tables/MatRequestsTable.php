<?php

namespace App\Filament\Resources\MatRequests\Tables;

use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Builder;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
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
                // TextColumn::make('po_file')
                //     ->url(fn ($record) => $record->po_file ? asset('storage/' . $record->po_file) : null, shouldOpenInNewTab: true)
                //     ->formatStateUsing(fn ($state) => $state ? 'Download' : '-') 
                //     ->color('info')
                //     ->searchable(),
            ]) 
            ->defaultSort('created_at', 'desc')
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
                ->default(function () {
                $user = filament()->auth()->user();

                // Kalau User → aktif by default
                return $user && $user->role === 'User';
                })
                ->visible(function () {
                    $user = filament()->auth()->user();

                    // Kalau Admin / Purchasing → sembunyikan filternya
                    return $user && $user->role === 'User';
                }),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('exportPdf')
                ->label('PDF')
                ->color(Color::Sky)
                ->icon(Heroicon::OutlinedDocumentArrowDown)
                ->action(function ($record) {
                    $pdf = Pdf::loadView('exports.request', [
                        'record' => $record,
                    ])
                    ->setPaper('a4','landscape');
                    return response()->streamDownload(fn () =>
                        print($pdf->output()), "MR-{$record->kodeRequest}.pdf"
                );
                }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
