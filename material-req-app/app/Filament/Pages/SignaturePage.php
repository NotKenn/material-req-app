<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;

class SignaturePage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-pencil';
    protected static ?string $title = 'Signature';
    protected static ?string $navigationLabel = 'My Signature';
    protected static ?string $slug = 'signature-page';
    public $signature;

    // 🔑 ini penting supaya Blade kustom dipakai
    protected string $view = 'filament.pages.signature-page';

    public static function shouldRegisterNavigation(): bool
    {
        return false; // true : tampil, false : gk ada di sidebar
    }

    public function mount(): void
    {
        // pre-fill kalau user sudah ada signature
        $this->signature = filament()->auth()->user()?->signature;
        $this->form->fill([
            'signature' => filament()->auth()->user()?->signature,
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            FileUpload::make('signature')
                ->label('Upload Signature')
                ->directory('signatures')
                ->disk('public')
                ->image()
                // ->filetype('png')
                ->maxSize(1024),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $user = filament()->auth()->user();
        $user->signature = $data['signature'];
        $user->save();

        Notification::make()
            ->title('Signature saved!')
            ->success()
            ->send();

        $this->redirect(route('filament.admin.pages.dashboard'));
    }
}
