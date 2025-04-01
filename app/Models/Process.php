<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Process extends Model
{
    protected $fillable = [
        'number',
        'name',
        'technical_score',
        'planning_score',
        'implementation_score',
        'control_score',
        'improvement_score',
        'qualitative_score',
        'numerical_score',
        'violations_count',
        'audit_id'
    ];

    public function audit()
    {
        return $this->belongsTo(Audit::class);
    }
} 