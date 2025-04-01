<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Access extends Model
{
    use HasFactory;
    
    protected $table = 'accesses';
    
    protected $fillable = [
        'user_id',
        'organization_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function audits()
    {
        return $this->belongsToMany(Audit::class, 'access_audit');
    }
} 