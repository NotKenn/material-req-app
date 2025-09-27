<?php

namespace App\Filament\Resources\MatRequests\Tables;

use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Builder;
use Filament\Notifications\Notification;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;

class MatRequestsTable
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
                    ->searchable(),
                TextColumn::make('approval_badge')
                    ->label('Approval')
                    ->badge()
                    ->getStateUsing(fn ($record) => $record->approvals()->latest('approved_at')->value('status') ?? 'pending')
                    ->colors([
                        'gray' => 'Pending',
                        'success' => 'Approved',
                        'danger' => 'Rejected',
                    ])
                    ->formatStateUsing(fn ($state) => ucfirst($state)),
                
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
                Action::make('approve')
                    ->icon('heroicon-o-check')
                    ->label('')
                    ->color('success')
                    // ->visible(function ($record) {
                    // if (! $record) return false;
                    // $last = $record->approvals()->latest('approved_at')->value('status');
                    // return strtolower($last ?? 'pending') === 'pending';
                    // })
                    ->requiresConfirmation()
                    ->modalHeading('Approve this Request?')
                    ->modalDescription('Press Confirm to Approve')
                    ->action(fn ($record) => $record->approvals()->create([
                        'user_id' => filament()->auth()->user()->id,
                        'status' => 'Approved',
                        'approved_at' => now(),

                        Notification::make()
                        ->title('Request approved')
                        ->success()
                        ->send(),
                    ])),

                Action::make('reject')
                    ->icon('heroicon-o-x-mark')
                    ->label('')
                    // ->visible(function ($record) {
                    // if (! $record) return false;
                    // $last = $record->approvals()->latest('approved_at')->value('status');
                    // return strtolower($last ?? 'pending') === 'pending';
                    // })
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Reject this Request?')
                    ->modalDescription('Press Confirm to Reject')
                    ->action(fn ($record) => $record->approvals()->create([
                        'user_id' => filament()->auth()->user()->id,
                        'status' => 'Rejected',
                        'approved_at' => now(),

                    Notification::make()
                        ->title('Request rejected')
                        ->danger()
                        ->send(),
                    ])),
                EditAction::make(), //buat kalau supervisor, kasih approve button
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
