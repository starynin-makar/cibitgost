<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        Log::info('AdminMiddleware: Started', [
            'user' => auth()->user(),
            'is_admin' => auth()->user()?->is_admin,
            'route' => $request->route()->getName()
        ]);

        if (!auth()->user()) {
            Log::warning('AdminMiddleware: No authenticated user');
            return redirect()->route('dashboard')
                ->with('error', 'Необходима авторизация');
        }

        if (!auth()->user()->is_admin) {
            Log::warning('AdminMiddleware: User is not admin', [
                'user_id' => auth()->id()
            ]);
            return redirect()->route('dashboard')
                ->with('error', 'У вас нет прав администратора');
        }

        Log::info('AdminMiddleware: Access granted');
        return $next($request);
    }
} 