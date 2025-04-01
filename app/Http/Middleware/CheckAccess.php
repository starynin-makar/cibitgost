<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Access;
use App\Models\Organization;
use App\Models\Audit;

class CheckAccess
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        
        // Если пользователь админ, пропускаем все проверки
        if ($user->is_admin) {
            return $next($request);
        }
        
        // Проверяем доступ к организации
        if ($request->route('organization')) {
            $organization = $request->route('organization');
            $organizationId = is_object($organization) ? $organization->id : $organization;
            
            $hasAccess = Access::where('user_id', $user->id)
                ->where('organization_id', $organizationId)
                ->exists();
            
            if (!$hasAccess) {
                return redirect()->route('dashboard')
                    ->with('error', 'У вас нет доступа к этой организации');
            }
        }
        
        // Проверяем доступ к аудиту
        if ($request->route('audit')) {
            $audit = $request->route('audit');
            $auditId = is_object($audit) ? $audit->id : $audit;
            
            $hasAccess = Access::where('user_id', $user->id)
                ->whereHas('audits', function($query) use ($auditId) {
                    $query->where('audits.id', $auditId);
                })->exists();
            
            if (!$hasAccess) {
                return redirect()->route('dashboard')
                    ->with('error', 'У вас нет доступа к этому аудиту');
            }
        }
        
        // Проверка доступа к разделу управления доступами
        if ($request->is('access*') && !$user->is_admin) {
            return redirect()->route('dashboard')
                ->with('error', 'У вас нет прав для управления доступами');
        }
        
        return $next($request);
    }
} 