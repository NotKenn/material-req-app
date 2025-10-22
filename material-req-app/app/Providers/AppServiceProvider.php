<?php

namespace App\Providers;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(Login::class, function ($event) {
        $user = $event->user;

        if (! $user->signature) {
            // Toast notifikasi (langsung muncul)
            Notification::make()
                ->title('Signature Missing')
                ->body('Please upload your signature.')
                ->danger()
                ->persistent() // biar ga auto ilang kalau mau
                ->send();

            // Database notifikasi (masuk bell)
            Notification::make()
                ->title('Signature Missing')
                ->body('Please upload your signature to continue.')
                ->danger()
                ->actions([
                    Action::make('Upload now')
                        ->button()
                        ->url('/app/signature-page'),
                ])
                ->sendToDatabase($user);
        }
    });
    }
}
