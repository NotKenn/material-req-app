<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureHasSignature
{
    public function handle(Request $request, Closure $next)
    {
        $user = filament()->auth()->user();

        // Kalau belum login, lewatin aja (biar middleware Auth yang urus)
        if (!$user) {
            return $next($request);
        }

        if ($user->role === 'Admin') {
            return $next($request);
        }

        // Kalau user belum punya signature dan bukan sedang di halaman signature
        if (!$user->signature && !$request->is('app/signature-page*', 'app', 'app/logout')) {
            Notification::make()
                ->title('Signature Required')
                ->body('Silakan upload tanda tangan dulu sebelum lanjut.')
                ->danger()
                ->send();

            return redirect('/app/signature-page')->with('warning', 'Silakan upload tanda tangan dulu sebelum melanjutkan.');
        }

        return $next($request);
    }
}
