<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Course;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Message;
use App\Models\User;

class   CoachController extends BaseController
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'coach') {
                return redirect()->route('dashboard')->with('error', 'Access denied. Coach role required.');
            }
            return $next($request);
        });
    }

public function inbox()
{
    $userId = Auth::id();
    $user = Auth::user();

    // get all unique user IDs who have chatted with me
    $chatUserIds = Message::where('sender_id', $userId)
        ->pluck('receiver_id')
        ->merge(
            Message::where('receiver_id', $userId)->pluck('sender_id')
        )
        ->unique();

    // fetch the users
    $users = User::whereIn('id', $chatUserIds)->get();

    return view('student.inbox', compact('users','user'));
}



    public function profile()
    {
        $user = Auth::user();
        return view('coach.accont');
    }

    public function coursesIndex()
    {
        $courses = Course::where('coach_id', Auth::id())->latest()->paginate(9);
        return view('coach.Courses.index', compact('courses'));
    }

    public function coursesAdd()
    {
        return view('coach.Courses.add');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'price' => 'required|numeric|min:0|max:9999.99',
            'category_id' => 'required|exists:categories,id',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status' => 'required|in:published,draft',
        ]);

        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
            $validated['thumbnail'] = $thumbnailPath;
        }

        $course = Course::create([
            ...$validated,
            'coach_id' => Auth::id()
        ]);

        // Create vector embedding for the course
        try {
            $response = Http::post('http://127.0.0.1:5001/create_vector', [
                'id' => $course->id,
                'description' => $validated['description']
            ]);

            if (!$response->successful()) {
                dd('Failed to create vector for course: ' . $course->id, $response->body());
            }
        } catch (\Exception $e) {
            dd('Vector creation error: ' . $e->getMessage());
        }

        return redirect()->route('coach.courses.index')->with('success', 'Course created successfully!');
    }

    public function show(Course $course)
    {
        $this->authorize('view', $course);
        return view('coach.Courses.show', compact('course'));
    }

    public function edit(Course $course)
    {
        $this->authorize('view', $course);
        return view('coach.Courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        $this->authorize('update', $course);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'price' => 'required|numeric|min:0|max:9999.99',
            'category_id' => 'required|exists:categories,id',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status' => 'required|in:published,draft',
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }
            
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
            $validated['thumbnail'] = $thumbnailPath;
        }

        $course->update($validated);

        return redirect()->route('coach.courses.index')->with('success', 'Course updated successfully!');
    }

    public function destroy(Course $course)
    {

        
        if ($course->thumbnail) {
            Storage::disk('public')->delete($course->thumbnail);
        }
        
        $course->delete();

        return redirect()->route('coach.courses.index')->with('success', 'Course deleted successfully!');
    }

    public function modify(ProfileUpdateRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $validated = $request->validated();
        
        if ($request->hasFile('profile_picture')) {
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            
            $profilePicturePath = $request->file('profile_picture')->store('profile-pictures', 'public');
            $validated['profile_photo'] = $profilePicturePath;
            unset($validated['profile_picture']); // Remove the form field name
        }
        
        if ($request->input('remove_picture') == '1') {
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $validated['profile_photo'] = null;
        }
        
        if ($request->has('about')) {
            $validated['about_you'] = $request->input('about');
            unset($validated['about']);
        }

        // Remove form-only fields that don't exist in database
        unset($validated['remove_picture']);

        if (isset($validated['email']) && $validated['email'] !== $user->email) {
            $validated['email_verified_at'] = null;
        }

        $user->update($validated);

        return redirect()->route('coach.profile')->with('status', 'profile-updated');
    }

}







