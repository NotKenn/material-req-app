<?php

namespace App\Filament\Resources\PurchaseLookups;

use App\Filament\Resources\PurchaseLookups\Pages\CreatePurchaseLookup;
use App\Filament\Resources\PurchaseLookups\Pages\EditPurchaseLookup;
use App\Filament\Resources\PurchaseLookups\Pages\ListPurchaseLookups;
use App\Filament\Resources\PurchaseLookups\Schemas\PurchaseLookupForm;
use App\Filament\Resources\PurchaseLookups\Tables\PurchaseLookupsTable;
use App\Models\PoItems;
use App\Models\vendor;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PurchaseLookupResource extends Resource
{
    protected static ?string $model = PoItems::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-magnifying-glass';

    protected static ?string $navigationLabel = 'Purchase Lookup';

    protected static ?string $recordTitleAttribute = 'Purchase Lookup';    

    protected static string|UnitEnum|null $navigationGroup = 'Purchasing';

    protected static ?int $navigationSort = 3; // biar urutannya jelas
    
    public static function getPluralLabel(): string
    {
        return 'Purchase Lookups';
    }

    public static function getLabel(): string
    {
        return 'Purchase Lookup';
    }
    public static function shouldRegisterNavigation(): bool
    {
        $user = filament()->auth()->user();
        return $user && in_array($user->role, ['Admin','Purchasing']);
    }

    public static function canViewAny(): bool
    {
        $user = filament()->auth()->user();
        return $user && in_array($user->role, ['Admin','Purchasing']);
    }

    public static function form(Schema $schema): Schema
    {
        return PurchaseLookupForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->recordUrl(null)
        ->query(
            PoItems::query()
                ->select([
                    'po_items.*',
                    'po_details.po_number',
                    'po_details.date as po_date',
                    'vendor.vendorName',
                ])
                ->leftJoin('po_details', 'po_items.po_id', '=', 'po_details.id')
                ->leftJoin('vendor', 'po_details.vendorID', '=', 'vendor.id')
        )
        ->columns([
            TextColumn::make('itemName')->label('Item')->searchable(),
            TextColumn::make('vendorName')->label('Vendor')->searchable(),
            TextColumn::make('po_number')->label('PO Number')->searchable(),
            TextColumn::make('po_date')->label('PO Date')->date(),
            TextColumn::make('qty')->label('Qty'),
            TextColumn::make('price')->label('Price')->money('idr'),
        ])
        ->filters([
            // SelectFilter::make('vendor_id')
            // ->label('Vendor')
            // ->options(vendor::pluck('vendorName', 'id'))
            // ->query(fn ($query, $value) =>
            //     $query->whereHas('poDetail.vendor', fn ($q) => $q->where('id', $value))
            // ),
            Filter::make('vendor')
                ->form([
                    Select::make('vendor_id')->options(\App\Models\Vendor::pluck('vendorName', 'id')),
                ])
                ->query(fn($query, $data) => $query->when($data['vendor_id'], fn($q) => $q->where('vendor.id', $data['vendor_id']))),
            Filter::make('date_range')
                ->form([
                    DatePicker::make('from'),
                    DatePicker::make('until'),
                ])
                ->query(function ($query, array $data) {
                    return $query
                        ->when($data['from'], fn ($q, $date) => $q->whereDate('po_details.date', '>=', $date))
                        ->when($data['until'], fn ($q, $date) => $q->whereDate('po_details.date', '<=', $date));
                }),
        ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPurchaseLookups::route('/'),
            'create' => CreatePurchaseLookup::route('/create'),
            'edit' => EditPurchaseLookup::route('/{record}/edit'),
        ];
    }
}
