<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evidence extends Model
{
    protected $table = 'audit_evidences';
    
    protected $fillable = [
        'audit_id',
        'norm_id',
        'user_id',
        'file_path',
        'description'
    ];

    public function audit()
    {
        return $this->belongsTo(Audit::class);
    }

    public function norm()
    {
        return $this->belongsTo(Norm::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 