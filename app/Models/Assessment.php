<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    protected $fillable = [
        'norm_id',
        'audit_id',
        'score'
    ];

    protected $casts = [
        'score' => 'string'
    ];

    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    public function audit()
    {
        return $this->belongsTo(Audit::class);
    }
} 