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
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('kodeRequest')->label('Kode MR')
                    ->color(Color::Sky)
                    ->url(fn ($record) => $record->kodeRequest
                    ? route('filament.admin.resources.mat-requests.view',
                        matRequest::where('kodeRequest', $record->kodeRequest)->value('id')
                    )
                    : null)
                ->searchable(),
                // TextColumn::make('requester.namaPT')
                //     ->label('PT Requester')
                //     ->sortable(),
                TextColumn::make('requester.namaKontakPT')
                    ->label('Nama Requester')
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('related_po')
                    ->label('Related PO')
                    ->color(Color::Sky)
                    ->state(fn ($record) => $record->pos
                        ->where('isActive', 1)
                        ->filter(function ($po) {

                            return optional($po->latestApproval)->status === 'Approved';

                        })
                        ->unique('id')
                        ->values()
                    )
                    ->formatStateUsing(function ($state) {

                        if (!$state instanceof \Illuminate\Support\Collection) {
                            $state = collect([$state]);
                        }

                        return $state->map(function ($po) {

                            $label = \App\Services\PoNumberFormatter::format($po);

                            $url = route('po.preview.pdf', $po->id);

                            return '<a href="'.$url.'" target="_blank">'.$label.'</a>';

                        })->join(', ');
                    })
                    ->html(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Proses PO')
                    ->searchable(),
                // TextColumn::make('po_file')
                //     ->url(fn ($record) => $record->po_file ? asset('storage/' . $record->po_file) : null, shouldOpenInNewTab: true)
                //     ->formatStateUsing(fn ($state) => $state ? 'Download' : '-')
                //     ->color('info')
                //     ->searchable(),
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
                Action::make('track_items')
                ->label('Track Items')
                ->icon('heroicon-o-clipboard-document-list')
                ->slideOver()
                ->modalWidth('7xl')
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Close')
                ->modalContent(fn ($record) => view(
                    'filament.track-mr.track-items',
                    [
                        'record' => $record,
                        'items' => $record->items,
                    ]
                )),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
