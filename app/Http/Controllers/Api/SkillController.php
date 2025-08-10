<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Skill;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SkillController extends Controller
{
public function store(Request $request)
{
    try {
        $request->validate([
            'skill' => 'required|string|max:255'
        ]);

        $userId = Auth::id(); // ✅ correct method call

        // Check for duplicates
        $existingSkill = Skill::where('user_id', $userId)
                              ->where('name', $request->skill)
                              ->first();

        if ($existingSkill) {
            return response()->json(['error' => 'Skill already exists'], 422);
        }

        $skill = new Skill();
        $skill->user_id = $userId;
        $skill->name = $request->skill;
        $skill->save();

        return response()->json(['success' => true, 'skill' => $skill]);

    } catch (\Exception $e) {
        return response()->json(['error' => 'Server error: ' . $e->getMessage()], 500);
    }
}

public function destroyByName(Request $request)
{
    try {
        $request->validate([
            'skill' => 'required|string'
        ]);

        $userId = Auth::id();
        
        $skill = Skill::where('user_id', $userId)
                     ->where('name', $request->skill)
                     ->first();

        if (!$skill) {
            return response()->json(['error' => 'Skill not found'], 404);
        }

        $skill->delete();

        return response()->json(['success' => true]);

    } catch (\Exception $e) {
        return response()->json(['error' => 'Server error: ' . $e->getMessage()], 500);
    }
}


}

