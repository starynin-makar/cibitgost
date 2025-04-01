<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Access;
use App\Models\Organization;
use App\Models\User;
use App\Models\Audit;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class AccessController extends Controller
{
    public function index()
    {
        Log::info('AccessController: Index method started', [
            'user' => auth()->user(),
            'is_admin' => auth()->user()?->is_admin
        ]);

        // Получаем доступы, сгруппированные по пользователям
        $groupedAccesses = Access::with(['user', 'organization', 'audits'])
            ->get()
            ->groupBy('user_id')
            ->map(function ($userAccesses) {
                return [
                    'user' => $userAccesses->first()->user,
                    'accesses' => $userAccesses->map(function ($access) {
                        return [
                            'id' => $access->id,
                            'organization' => $access->organization->name,
                            'audits' => $access->audits->pluck('title')->implode(', ')
                        ];
                    })
                ];
            });
        
        Log::info('AccessController: Accesses loaded', [
            'count' => $groupedAccesses->count()
        ]);
        
        $users = User::all();
        $organizations = Organization::all();
        
        return view('access.index', compact('groupedAccesses', 'users', 'organizations'));
    }

    public function create()
    {
        $users = User::all();
        $organizations = Organization::all();
        return view('access.create', compact('users', 'organizations'));
    }

    public function store(Request $request)
    {
        // Сначала проверяем, выбран ли существующий пользователь
        if ($request->filled('user_id')) {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'organizations' => 'required|array',
                'organizations.*' => 'exists:organizations,id',
                'audits' => 'nullable|array',
                'audits.*' => 'array'
            ]);
        } else {
            // Если создается новый пользователь
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6',
                'organizations' => 'required|array',
                'organizations.*' => 'exists:organizations,id',
                'audits' => 'nullable|array',
                'audits.*' => 'array'
            ]);
        }

        try {
            DB::beginTransaction();

            // Определяем ID пользователя
            if ($request->filled('user_id')) {
                $userId = $validated['user_id'];
                
                // Удаляем старые доступы для выбранных организаций
                Access::where('user_id', $userId)
                    ->whereIn('organization_id', $validated['organizations'])
                    ->delete();
            } else {
                // Создаем нового пользователя
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password'])
                ]);
                $userId = $user->id;
            }

            // Создаем доступы для каждой организации
            foreach ($validated['organizations'] as $organizationId) {
                $access = Access::create([
                    'user_id' => $userId,
                    'organization_id' => $organizationId
                ]);

                // Если есть аудиты для этой организации
                if (isset($validated['audits'][$organizationId]) && !empty($validated['audits'][$organizationId])) {
                    $audits = Audit::whereIn('id', $validated['audits'][$organizationId])
                        ->where('organization_id', $organizationId)
                        ->get();
                    $access->audits()->sync($audits);
                }
            }

            DB::commit();

            return redirect()->route('access.index')
                ->with('success', 'Доступы успешно добавлены');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error in AccessController@store: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Ошибка при сохранении доступов: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function storeAuditAccess(Request $request)
    {
        $request->validate([
            'access_id' => 'required|exists:accesses,id',
            'audit_ids' => 'required|array',
            'audit_ids.*' => 'exists:audits,id'
        ]);

        try {
            $access = Access::findOrFail($request->access_id);
            $access->audits()->sync($request->audit_ids);
            
            return response()->json(['message' => 'Доступы к аудитам успешно обновлены']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getOrganizationAudits(Organization $organization)
    {
        $audits = $organization->audits()->get();
        return response()->json($audits);
    }

    public function destroy(Access $access)
    {
        try {
            $access->audits()->detach();
            $access->delete();
            return redirect()->route('access.index')
                ->with('success', 'Доступ успешно удален');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Ошибка при удалении доступа: ' . $e->getMessage());
        }
    }

    public function edit(Access $access)
    {
        $users = User::all();
        $organizations = Organization::with('audits')->get();
        
        // Получаем все доступы пользователя
        $userAccesses = Access::where('user_id', $access->user_id)
            ->with(['organization', 'audits'])
            ->get();
        
        // Собираем выбранные организации и аудиты
        $selectedOrganizations = $userAccesses->pluck('organization_id')->toArray();
        $selectedAudits = [];
        
        foreach ($userAccesses as $userAccess) {
            if ($userAccess->audits->isNotEmpty()) {
                $selectedAudits[$userAccess->organization_id] = $userAccess->audits->pluck('id')->toArray();
            }
        }
        
        return view('access.edit', compact(
            'access',
            'users',
            'organizations',
            'selectedOrganizations',
            'selectedAudits'
        ));
    }

    public function update(Request $request, Access $access)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'organizations' => 'required|array',
            'organizations.*' => 'exists:organizations,id',
            'audits' => 'nullable|array',
            'audits.*' => 'array'
        ]);

        // Обновляем основные данные доступа
        $access->update([
            'user_id' => $validated['user_id'],
            'organization_id' => $validated['organizations'][0]
        ]);

        // Создаем дополнительные доступы для остальных организаций
        foreach (array_slice($validated['organizations'], 1) as $organizationId) {
            Access::updateOrCreate(
                [
                    'user_id' => $validated['user_id'],
                    'organization_id' => $organizationId
                ]
            );
        }

        // Обновляем связи с аудитами
        if (isset($validated['audits'])) {
            $auditIds = [];
            foreach ($validated['audits'] as $organizationId => $orgAudits) {
                // Проверяем, что организация принадлежит текущему доступу
                if ($access->organization_id == $organizationId) {
                    $auditIds = array_merge($auditIds, $orgAudits);
                }
            }
            $access->audits()->sync($auditIds);
        } else {
            $access->audits()->detach();
        }

        return redirect()->route('access.index')
            ->with('success', 'Доступ успешно обновлен');
    }
} 