<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcessScore extends Model
{
    protected $table = 'process_scores';
    
    protected $fillable = [
        'audit_id',
        'process_name',
        'technical_score',
        'planning_score',
        'implementation_score',
        'control_score',
        'improvement_score',
        'qualitative_score',
        'numerical_score',
    ];

    public function audit()
    {
        return $this->belongsTo(Audit::class);
    }
} 