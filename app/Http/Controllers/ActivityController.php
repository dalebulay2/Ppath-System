<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;

class ActivityController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'date'  => 'required|date'
        ]);

        $activity = Activity::create([
            'title' => $validated['title'],
            'date'  => $validated['date']
        ]);

        return response()->json([
            'success' => true,
            'activity' => $activity
        ]);
    }
}