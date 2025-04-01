<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Process;
use App\Models\Subprocess;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Валидация входных данных
            $validated = $request->validate([
                'field_id' => 'required|integer',
                'score' => 'required|in:-1,0,1',
                'audit_id' => 'required|integer',
                'process_id' => 'required|integer'
            ]);

            // Сохраняем или обновляем оценку
            $assessment = Assessment::updateOrCreate(
                [
                    'field_id' => $validated['field_id'],
                    'audit_id' => $validated['audit_id']
                ],
                ['score' => $validated['score']]
            );

            // Получаем процесс и подпроцесс
            $process = Process::findOrFail($validated['process_id']);
            $subprocess = $assessment->field->subprocess;

            // Пересчитываем оценки
            $subprocess_score = $this->calculateSubprocessScore($subprocess->id, $validated['audit_id']);
            $process_score = $this->calculateProcessScore($process->id, $validated['audit_id']);

            return response()->json([
                'success' => true,
                'subprocess_score' => $subprocess_score,
                'process_score' => $process_score,
                'message' => 'Оценка успешно сохранена'
            ]);

        } catch (\Exception $e) {
            \Log::error('Assessment save error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при сохранении оценки: ' . $e->getMessage()
            ], 422);
        }
    }

    private function calculateSubprocessScore($subprocess_id, $audit_id)
    {
        $subprocess = Subprocess::findOrFail($subprocess_id);
        $assessments = Assessment::whereHas('field', function($query) use ($subprocess_id) {
            $query->where('subprocess_id', $subprocess_id);
        })->where('audit_id', $audit_id)->get();

        if ($assessments->isEmpty()) {
            return 0;
        }

        $validScores = $assessments->filter(function($assessment) {
            return $assessment->score !== -1;
        });

        if ($validScores->isEmpty()) {
            return 0;
        }

        return $validScores->avg('score');
    }

    private function calculateProcessScore($process_id, $audit_id)
    {
        $process = Process::findOrFail($process_id);
        $subprocessScores = [];

        foreach ($process->subprocesses as $subprocess) {
            $score = $this->calculateSubprocessScore($subprocess->id, $audit_id);
            if ($score > 0) {
                $subprocessScores[] = $score;
            }
        }

        if (empty($subprocessScores)) {
            return 0;
        }

        return array_sum($subprocessScores) / count($subprocessScores);
    }
} 