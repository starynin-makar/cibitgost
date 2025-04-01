<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function saveRatings(Request $request)
    {
        $validated = $request->validate([
            'process_id' => 'required|integer',
            'process_average' => 'required|numeric',
            'subprocess_ratings' => 'required|array',
            'subprocess_ratings.*.subprocess_id' => 'required|integer',
            'subprocess_ratings.*.value' => 'required|numeric'
        ]);

        // Сохранение данных в БД
        // ...

        return response()->json(['success' => true]);
    }
} 