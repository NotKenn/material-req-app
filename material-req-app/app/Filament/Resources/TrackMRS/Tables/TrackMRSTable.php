<?php

namespace App\Filament\Resources\TrackMRS\Tables;

use App\Models\matRequest;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
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
                TextColumn::make('kodeRequest')->label('Kode MR')
                    ->color(Color::Sky)
                    ->url(fn ($record) => $record->kodeRequest
                    ? route('filament.admin.resources.mat-requests.view',
                        matRequest::where('kodeRequest', $record->kodeRequest)->value('id')
                    )
                    : null)
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
                TextColumn::make('reject_note')
                    ->label('Reject Note')
                    ->wrap(),
            ])
            ->filters([
                Filter::make('myRequests')
                ->label('My Requests')
                ->query(function ($query) {
                $user = filament()->auth()->user();

                if ($user && $user->role === 'Requester') {
                    return $query->where('user_id', $user->id);
                }

                    return $query; // biarkan tanpa filter kalau bukan role User
                }),
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
                Action::make('exportPdf')
                ->openUrlInNewTab()
                ->label('PDF')
                ->color(Color::Sky)
                ->icon(Heroicon::OutlinedDocumentArrowDown)
                ->visible(fn ($record) =>
                $record->approvals()->latest('approved_at')->value('status') === 'Approved'
                )
                ->url(fn ($record) => route('mr.preview.pdf', $record))
                ->openUrlInNewTab(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
