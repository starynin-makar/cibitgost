<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->is_admin) {
            // Администратор видит все организации
            $organizations = Organization::orderBy('name')->get();
        } else {
            // Обычные пользователи видят только организации, к которым у них есть доступ
            $organizations = Organization::whereHas('accesses', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->orderBy('name')->get();
        }
        
        return view('organizations.index', compact('organizations'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'director' => 'required|string|max:255',
                'description' => 'nullable|string',
                'address' => 'required|string|max:255',
                'phone' => 'required|string|max:255',
                'email' => 'required|email|max:255',
            ]);

            $validated['user_id'] = auth()->id();
            $validated['status'] = 1;

            Organization::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Организация успешно добавлена'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при сохранении организации',
                'errors' => $e->getMessage()
            ], 422);
        }
    }

    public function update(Request $request, Organization $organization)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'director' => 'required|string|max:255',
                'description' => 'nullable|string',
                'address' => 'required|string|max:255',
                'phone' => 'required|string|max:255',
                'email' => 'required|email|max:255',
            ]);

            $organization->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Организация успешно обновлена'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при обновлении организации: ' . $e->getMessage()
            ], 422);
        }
    }

    public function destroy(Organization $organization)
    {
        $organization->delete();
        return redirect()->route('organizations.index')
            ->with('success', 'Организация успешно удалена');
    }

    public function edit(Organization $organization)
    {
        return response()->json($organization);
    }
} 