<?php

namespace App\Filament\Resources\PoDetails\Pages;

use App\Filament\Resources\PoDetails\PoDetailsResource;
use App\Models\PoDetails;
use App\Services\PORevisionService;
use Filament\Resources\Pages\Page;
use Filament\Forms;
// use Filament\Forms\Form;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Schema;

class CreatePoRevision extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static string $resource = PoDetailsResource::class;

    public function getView(): string
    {
        return 'filament.pages.create-po-revision';
    }

    public PoDetails $record;

    public array $data = [];

    // 1. LOAD DATA PO LAMA
    public function mount(PoDetails $record): void
    {
        $this->record = $record;

        $this->form->fill([
            'po_number' => $record->po_number,
            'revision'   => $record->revision,
            'note'       => $record->note,
        ]);
    }

    // 2. FORM
    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('po_number')
                    ->disabled(),

                Forms\Components\TextInput::make('revision')
                    ->disabled(),

                Forms\Components\Textarea::make('note')
                    ->label('Revision Note'),
            ])
            ->statePath('data');
    }

    // 3. SUBMIT = BARU CLONE DI SINI
    public function submit(): void
    {
        app(PORevisionService::class)
            ->revise($this->record, $this->data);

        $this->redirect(PoDetailsResource::getUrl('index'));
    }
}
