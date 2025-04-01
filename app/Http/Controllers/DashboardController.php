<?php

namespace App\Http\Controllers;

use App\Models\Organization;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->is_admin) {
            $organizations = Organization::all();
        } else {
            $organizations = Organization::whereHas('accesses', function($query) use ($user) {
                $query->where('email', $user->email);
            })->get();
        }
        
        return view('dashboard', compact('organizations'));
    }
} 