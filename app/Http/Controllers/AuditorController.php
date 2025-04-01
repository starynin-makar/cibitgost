<?php

namespace App\Http\Controllers;

class AuditorController extends Controller
{
    public function dashboard()
    {
        return view('auditor.dashboard');
    }
} 