<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Audit extends Model
{
    protected $fillable = [
        'title',
        'description',
        'organization_id',
        'status',
        'start_date',
        'end_date',
        'user_id'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'start_date',
        'end_date'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Метод для получения информации о заполненных вкладках аудита
    public function getCompletedTabs()
    {
        // Получаем все оценки для этого аудита
        $assessments = \App\Models\NormAssessment::where('audit_id', $this->id)->get();
        
        // Получаем все нормы, сгруппированные по вкладкам
        $normsByTab = \App\Models\Norm::select('id', 'tab')->get()->groupBy('tab');
        
        $tabs = [];
        $lastCompletedTab = 0;
        
        // Для каждой вкладки считаем прогресс заполнения
        foreach ($normsByTab as $tab => $norms) {
            $normIds = $norms->pluck('id')->toArray();
            $totalNorms = count($normIds);
            $assessedNorms = $assessments->whereIn('norm_id', $normIds)->count();
            
            // Если все нормы для вкладки оценены, то вкладка считается заполненной
            $isComplete = ($totalNorms > 0) && ($assessedNorms / $totalNorms >= 0.7);
            
            $tabs[$tab] = [
                'total' => $totalNorms,
                'assessed' => $assessedNorms,
                'is_complete' => $isComplete,
                'progress' => $totalNorms > 0 ? round(($assessedNorms / $totalNorms) * 100) : 0
            ];
            
            // Запоминаем номер последней заполненной вкладки
            if ($isComplete && $tab > $lastCompletedTab) {
                $lastCompletedTab = $tab;
            }
        }
        
        return [
            'tabs' => $tabs,
            'last_completed_tab' => $lastCompletedTab
        ];
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assessments()
    {
        return $this->hasMany(Assessment::class);
    }

    public function evidences()
    {
        return $this->hasMany(Evidence::class);
    }

    public function norms()
    {
        return $this->hasManyThrough(
            Norm::class,
            NormAssessment::class,
            'audit_id',
            'id',
            'id',
            'norm_id'
        )->distinct();
    }

    public function accesses()
    {
        return $this->belongsToMany(Access::class, 'access_audit');
    }
} 