<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcessEvaluation extends Model
{
    protected $fillable = [
        'audit_id',
        'process_number',
        'process_name',
        'organizational_technical_score',
        'planning_score',
        'implementation_score',
        'control_score',
        'improvement_score',
        'qualitative_score',
        'numerical_score',
        'violations_count',
        'final_score'
    ];

    public function audit()
    {
        return $this->belongsTo(Audit::class);
    }
} 