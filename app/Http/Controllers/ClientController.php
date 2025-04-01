<?php

namespace App\Http\Controllers;

class ClientController extends Controller
{
    public function dashboard()
    {
        return view('client.dashboard');
    }
} 