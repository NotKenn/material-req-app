<?php

namespace App\Filament\Resources\MatRequests\Tables;

use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\CheckboxList;
use Filament\Notifications\Notification;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\IconSize;
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
                    ->wrap()
                    ->searchable(),
                TextColumn::make('requester.namaPT')
                    ->label('Requester')
                    ->wrap()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->wrap()
                    ->sortable(),
                TextColumn::make('status')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('approval_badge')
                    ->label('Supervisor Approval')
                    ->wrap()
                    ->alignCenter()
                    ->badge()
                    ->getStateUsing(fn ($record) => $record->approvals()->latest('approved_at')->value('status') ?? 'pending')
                    ->colors([
                        'gray' => 'pending',
                        'success' => 'approved',
                        'danger' => 'Rejected',
                        'warning' => 'Revision',

                        ])
                    ->formatStateUsing(fn ($state) => ucfirst($state)),
                TextColumn::make('editor.name')
                    ->label('Last Edit')
                    ->alignCenter()
                    ->wrap(),
                TextColumn::make('approved_by')
                    ->label('Reviewed By')
                    ->getStateUsing(fn ($record) =>
                        $record->approvals()
                            ->latest('approved_at')
                            ->first()?->user?->name
                            ?? '-'
                    ),

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

                if ($user && $user->role === 'Requester') {
                    return $query->where('user_id', $user->id);
                }
                    return $query; // biarkan tanpa filter kalau bukan role User
                })
                ->default(function () {
                $user = filament()->auth()->user();

                // Kalau User → aktif by default
                return $user && $user->role === 'Requester';
                })
                ->visible(function () {
                    $user = filament()->auth()->user();

                    // Kalau Admin / Purchasing → sembunyikan filternya
                    return $user && $user->role === 'Requester';
                }),
            ])
            ->recordActions([
                Action::make('approve')
                    ->icon('heroicon-o-check')
                    ->iconSize(IconSize::Medium)
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
                    ->tooltip('Approve this request')
                    ->hidden(fn () => in_array(filament()->auth()->user()->role, ['Requester']))
                    ->disabled(fn () => in_array(filament()->auth()->user()->role, ['Requester']))
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
                    ->iconSize(IconSize::Medium)
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
                    ->hidden(fn () => in_array(filament()->auth()->user()->role, ['Requester']))
                    ->disabled(fn () => in_array(filament()->auth()->user()->role, ['Requester']))
                    ->tooltip('Reject this request')
                    ->action(fn ($record) => $record->approvals()->create([
                        'user_id' => filament()->auth()->user()->id,
                        'status' => 'Rejected',
                        'approved_at' => now(),

                    Notification::make()
                        ->title('Request rejected')
                        ->danger()
                        ->send(),
                    ])),
                ActionGroup::make([
                    Action::make('downloadPO')
                    ->label('Download PO')
                    ->icon(Heroicon::OutlinedDocumentArrowDown)
                    ->color(Color::Sky)
                    ->modalHeading('Download Related Purchase Orders')
                    ->form([

                        \Filament\Forms\Components\CheckboxList::make('selected_pos')
                            ->label('Pilih PO')

                            ->options(function ($record) {

                                $poIds = \Illuminate\Support\Facades\DB::table('po_mr')
                                    ->where('mr_id', $record->id)
                                    ->whereNotNull('po_id')
                                    ->pluck('po_id');

                                return \App\Models\PoDetails::query()
                                    ->whereIn('id', $poIds)
                                    ->where('isActive', 1)
                                    ->get()
                                    ->mapWithKeys(function ($po) {

                                        return [
                                            $po->id => \App\Services\PoNumberFormatter::format($po)
                                        ];
                                    })
                                    ->toArray();
                            })

                            ->columns(1)
                            ->required(),
                    ])
                    ->action(function (array $data, $record) {

                        $selectedPOs = \App\Models\PoDetails::whereIn('id', $data['selected_pos'])
                            ->get();

                        $zipFileName = 'PO-' . $record->kodeRequest . '.zip';

                        $zipPath = storage_path("app/temp_{$record->id}.zip");

                        $zip = new \ZipArchive();

                        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {

                            foreach ($selectedPOs as $po) {

                                // generate pdf
                                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
                                    'exports.record',
                                    ['record' => $po]
                                )
                                ->setPaper('a4', 'portrait')
                                ->output();

                                // formatted filename
                                $formattedPoNumber = \App\Services\PoNumberFormatter::format($po);

                                $safeFileName = str_replace('/', '-', $formattedPoNumber);

                                // add directly into zip
                                $zip->addFromString(
                                    "PO-{$safeFileName}.pdf",
                                    $pdf
                                );
                            }

                            $zip->close();
                        }

                        return response()
                            ->download($zipPath, $zipFileName)
                            ->deleteFileAfterSend(true);
                    }),
                EditAction::make(), //buat kalau supervisor, kasih approve button
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
                // ->action(function ($record) {
                //     $pdf = Pdf::loadView('exports.request', [
                //         'record' => $record,
                //     ])
                //     ->setPaper('a4','landscape');
                //     return response()->stream(function () use ($pdf) {
                //     echo $pdf->output();
                // }, 200, [
                //     'Content-Type' => 'application/pdf',
                //     'Content-Disposition' => 'inline; filename="MR-' . $record->kodeRequest . '.pdf"',
                // ]);

                //     return response()->streamDownload(fn () =>
                //         print($pdf->output()), "MR-{$record->kodeRequest}.pdf"
                // );

                // }),
                ViewAction::make()
                // ->hidden(fn () => in_array(filament()->auth()->user()->role, ['Requester']))
                // ->disabled(fn () => in_array(filament()->auth()->user()->role, ['Requester'])),
                ]),

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
