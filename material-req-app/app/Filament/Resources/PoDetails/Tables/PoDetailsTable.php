<?php

namespace App\Filament\Resources\PoDetails\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Support\Enums\IconSize;
use Filament\Tables\Columns\IconColumn;

class PoDetailsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->columns([
                TextColumn::make('companyName')
                    ->label('Nama Perusahaan')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('po_number')
                    ->label('Nomor PO')
                    ->searchable()
                    ->wrap(),
                // TextColumn::make('officeAddress')
                //     ->label('Alamat')
                //     ->searchable(),
                // TextColumn::make('contactName')
                //     ->label('Nama Kontak')
                //     ->searchable(),
                TextColumn::make('date')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->wrap()
                    ->sortable(),
                // TextColumn::make('termOfPayment')
                //     ->label('Term of Payment')
                //     ->searchable(),
                TextColumn::make('vendor.vendorName')
                    ->label('Vendor')
                    ->alignCenter()
                    ->searchable()
                    ->wrap(),
                TextColumn::make('approval_badge')
                    ->label('Supervisor Approval')
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
            ])
            ->filters([
                //
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
                    ->modalHeading('Approve this PO?')
                    ->modalDescription('Press Confirm to Approve')
                    ->tooltip('Approve this PO')
                    ->hidden(fn () => in_array(filament()->auth()->user()->role, ['Purchasing']))
                    ->disabled(fn () => in_array(filament()->auth()->user()->role, ['Purchasing']))
                    ->action(fn ($record) => $record->approvals()->create([
                        'user_id' => filament()->auth()->user()->id,
                        'status' => 'Approved',
                        'approved_at' => now(),

                        Notification::make()
                        ->title('PO approved')
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
                    ->modalHeading('Reject this PO?')
                    ->modalDescription('Press Confirm to Reject')
                    ->hidden(fn () => in_array(filament()->auth()->user()->role, ['Purchasing']))
                    ->disabled(fn () => in_array(filament()->auth()->user()->role, ['Purchasing']))
                    ->tooltip('Reject this PO')
                    ->action(fn ($record) => $record->approvals()->create([
                        'user_id' => filament()->auth()->user()->id,
                        'status' => 'Rejected',
                        'approved_at' => now(),

                    Notification::make()
                        ->title('PO rejected')
                        ->danger()
                        ->send(),
                    ])),
                EditAction::make(),
                Action::make('exportPdf')
                ->label('PDF')
                ->color(Color::Sky)
                ->icon(Heroicon::OutlinedDocumentArrowDown)
                ->visible(fn ($record) => 
                $record->approvals()->latest('approved_at')->value('status') === 'Approved'
                )
                ->action(function ($record) {
                    $pdf = Pdf::loadView('exports.record', [
                        'record' => $record,
                    ])
                    ->setPaper('a4');
                    return response()->streamDownload(fn () =>
                        print($pdf->output()), "record-{$record->id}.pdf"
                );
                }),
                ViewAction::make()
                ->hidden(fn () => in_array(filament()->auth()->user()->role, ['Purchasing']))
                ->disabled(fn () => in_array(filament()->auth()->user()->role, ['Purchasing'])),
                //nnti masukin function untuk dompdf export sesuai template, 
                //nnti juga buat template di views/exports/ gitu
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
