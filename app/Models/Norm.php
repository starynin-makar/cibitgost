<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Norm extends Model
{
    protected $fillable = [
        'code',
        'description',
        'process_name',
        'subprocess_name',
        'implementation_type',
        'tab',
        'order'
    ];

    public function assessments()
    {
        return $this->hasMany(NormAssessment::class);
    }

    public function documents()
    {
        return $this->hasMany(NormDocument::class);
    }

    public function evidences()
    {
        return $this->hasMany(Evidence::class, 'norm_id');
    }
} 