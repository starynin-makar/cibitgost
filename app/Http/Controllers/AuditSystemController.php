<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use Illuminate\Http\Request;

class AuditSystemController extends Controller
{
    public function create(Request $request)
    {
        $audit = Audit::findOrFail($request->audit_id);
        $level = $request->level_id;
        
        return view('auditsystems.create', compact('audit', 'level'));
    }

    public function store(Request $request)
    {
        // Логика сохранения системы аудита
    }

    public function edit(AuditSystem $auditSystem)
    {
        return view('auditsystems.edit', compact('auditSystem'));
    }
} 