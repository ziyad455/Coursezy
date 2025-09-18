<?php

namespace App\Http\Controllers;
use App\Models\Course;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Support\Facades\Storage;
use App\Models\Enrollment;
use App\Models\Rating;
use App\Models\Activity;
use App\Events\NewActivity;




use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function dashboard()
    {
    $courses = Course::latest()->paginate(4);
    $user = Auth::user();
    if($user->role !== 'student'){
        return redirect('404');
    }
    return view('student.dashboard',['courses'=>$courses,'user'=>$user]);
    }

    public function accont()
    {
        $user = Auth::user();
        if($user->role !== 'student'){
            return redirect('404');
        }
         return view('student.accont',['user'=>$user]);

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

        // Check if request came from Chatify and redirect back if so
        if ($request->has('redirect_to_chatify') || str_contains($request->header('referer', ''), 'chatify')) {
            return redirect()->route('chatify')->with('status', 'profile-photo-updated');
        }

        return redirect()->route('student.accont')->with('status', 'profile-updated');
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

public function processPayment(Request $request) {
    // Debug: Check all incoming request data
    // dd('All request data:', $request->all());
    
    // Fixed validation with correct field names
    $validated = $request->validate([
        'card_number' => 'required',    // changed from card-number
        'expiry_date' => 'required',    // changed from expiry-date
        'cvc' => 'required',
        'card_name' => 'required',      // changed from card-name
        'country' => 'required',
        'terms' => 'required'           // removed |accepted since checkbox sends "on"
    ]);

    try {
        $enrollment = Enrollment::create([
            'student_id'   => Auth::id(),
            'course_id'    => $request->input('course_id'),
            'purchased_at' => now(),
        ]);
        
        // Get course details for activity
        $course = Course::find($request->input('course_id'));
        
        // Create activity for enrollment
        if ($course) {
            $activity = Activity::create([
                'type' => Activity::TYPE_ENROLLMENT,
                'description' => Auth::user()->name . ' enrolled in ' . $course->title,
                'user_id' => Auth::id(),
                'coach_id' => $course->coach_id,
                'course_id' => $course->id,
                'student_id' => Auth::id(),
            ]);
            
            // Broadcast the activity
            broadcast(new NewActivity($activity))->toOthers();
        }
        
        return redirect()->route('student.dashboard')->with('success', true);
        
    } catch (\Exception $e) {
        dd('Error:', $e->getMessage());
    }
}

    /**
     * Display the student's enrolled courses
     */
    public function myCourses()
    {
        $user = Auth::user();
        
        if($user->role !== 'student'){
            return redirect('404');
        }
        
        // Get all enrolled courses with ratings
        $enrollments = Enrollment::where('student_id', $user->id)
            ->with(['course' => function($query) {
                $query->with(['coach', 'category']);
            }])
            ->latest('purchased_at')
            ->paginate(8);
        
        // Get ratings for these courses
        $courseIds = $enrollments->pluck('course_id');
        $userRatings = Rating::where('student_id', $user->id)
            ->whereIn('course_id', $courseIds)
            ->pluck('rating', 'course_id');
        
        return view('student.my-courses', compact('enrollments', 'userRatings', 'user'));
    }
    
    /**
     * Submit or update a course rating
     */
    public function rateCourse(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000'
        ]);
        
        $user = Auth::user();
        
        // Check if user is enrolled in the course
        $enrollment = Enrollment::where('student_id', $user->id)
            ->where('course_id', $validated['course_id'])
            ->first();
        
        if (!$enrollment) {
            return response()->json(['error' => 'You are not enrolled in this course'], 403);
        }
        
        // Update or create rating
        $rating = Rating::updateOrCreate(
            [
                'student_id' => $user->id,
                'course_id' => $validated['course_id']
            ],
            [
                'rating' => $validated['rating'],
                'review' => $validated['review'] ?? null
            ]
        );
        
        // Get course for activity
        $course = Course::find($validated['course_id']);
        
        // Create activity for the rating
        if ($course) {
            $activity = Activity::create([
                'type' => Activity::TYPE_REVIEW,
                'description' => $user->name . ' rated "' . $course->title . '" ' . $validated['rating'] . ' stars',
                'user_id' => $user->id,
                'coach_id' => $course->coach_id,
                'course_id' => $course->id,
                'student_id' => $user->id,
                'rating' => $validated['rating']
            ]);
            
            // Broadcast the activity
            broadcast(new NewActivity($activity))->toOthers();
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Rating submitted successfully',
            'rating' => $rating
        ]);
    }
    // public function index()
    // {
    //     //
    // }

    // /**
    //  * Show the form for creating a new resource.
    //  */
    // public function create()
    // {
    //     //
    // }

    // /**
    //  * Store a newly created resource in storage.
    //  */
    // public function store(Request $request)
    // {
    //     //
    // }

    // /**
    //  * Display the specified resource.
    //  */
    // public function show(string $id)
    // {
    //     //
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  */
    // public function edit(string $id)
    // {
    //     //
    // }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(Request $request, string $id)
    // {
    //     //
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    // public function destroy(string $id)
    // {
    //     //
    // }
}
