<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NormAssessment extends Model
{
    protected $fillable = [
        'audit_id',
        'norm_id',
        'score',
        'approved'
    ];

    protected $casts = [
        'score' => 'float',
        'approved' => 'boolean'
    ];

    public function audit()
    {
        return $this->belongsTo(Audit::class);
    }

    public function norm()
    {
        return $this->belongsTo(Norm::class);
    }

    // Мутатор для преобразования 'н/о' в -1 при сохранении
    public function setScoreAttribute($value)
    {
        if ($value === 'н/о') {
            $this->attributes['score'] = -1;
        } else if ($value === '0.5') {
            $this->attributes['score'] = 0.5;
        } else {
            $this->attributes['score'] = $value;
        }
    }

    // Аксессор для преобразования -1 в 'н/о' при получении
    public function getScoreAttribute($value)
    {
        if ($value === -1 || $value === '-1') {
            return 'н/о';
        }
        return $value;
    }
} 