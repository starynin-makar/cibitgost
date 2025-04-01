<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NormDocument extends Model
{
    protected $fillable = [
        'norm_id',
        'audit_id',
        'file_path',
        'comment',
        'responsible_person',
        'version',
        'employee_name',
        'uploaded_by'
    ];

    protected $casts = [
        'uploaded_at' => 'datetime'
    ];

    public function norm()
    {
        return $this->belongsTo(Norm::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
} 