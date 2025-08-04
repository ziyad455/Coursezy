<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\User;
use GuzzleHttp\Psr7\Uri;

class CoachController extends Controller
{
    public function inbox()
    {
        return 'Inbox Page (placeholder)';
    }

    public function profile()
    {
        return 'Profile Page (placeholder)';
    }

    public function coursesIndex()
    {
        $user = Auth::user();
         // or coach_id
        return view('coach.Courses.index');
    }

    public function coursesAdd()
    {
        return view('coach.Courses.add');
    }

public function create(Request $request, User $user)
{
    // ✅ Step 1: Validate the request
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric',
        // add other fields as needed
    ]);

    // ✅ Step 2: Create the course for this user
    $user->courses()->create($validated);

    // ✅ Step 3: Redirect
    return redirect()->route('coach.courses.index');
}
}

