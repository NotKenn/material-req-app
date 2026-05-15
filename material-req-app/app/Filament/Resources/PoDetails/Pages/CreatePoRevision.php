<?php

namespace App\Filament\Resources\PoDetails\Pages;

use App\Filament\Resources\PoDetails\PoDetailsResource;
use App\Models\PoDetails;
use App\Services\PORevisionService;
use Filament\Actions\Action;
use Filament\Resources\Pages\Page;
use Filament\Forms;
// use Filament\Forms\Form;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;

class CreatePoRevision extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static string $resource = PoDetailsResource::class;

    protected static string $model = \App\Models\PoDetails::class;

    public function getView(): string
    {
        return 'filament.pages.create-po-revision';
    }
    protected function getHeaderActions(): array
    {
        return [
            // EditAction::make(),
            Action::make('back')
                ->label('Back')
                ->icon(Heroicon::ArrowLeftEndOnRectangle)
                ->url('javascript:history.back()')
                ->color(Color::Orange),
        ];
    }

    public PoDetails $record;

    public array $data = [];

    // 1. LOAD DATA PO LAMA
    public function mount(PoDetails $record): void
    {
        $this->record = $record;

        $this->form->model($record);

        $this->form->fill([
            ...$record->toArray(),
            'matRequests' => $record->matRequests->pluck('id')->toArray(),
        ]);
    }

    // 2. FORM
    public function form(Schema $schema): Schema
    {
        return PoDetailsResource::form($schema)
            ->model(PoDetails::class)
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
