<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $fillable = [
        'name',
        'director',
        'description',
        'address',
        'phone',
        'email',
        'user_id',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function audits()
    {
        return $this->hasMany(Audit::class);
    }

    public function accesses()
    {
        return $this->hasMany(Access::class);
    }
} 