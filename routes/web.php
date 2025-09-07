<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Course;
use App\Models\Message;
use App\Http\Controllers\Api\SkillController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Events\NewMessage;
// use Illuminate\Auth\Events\Registered;

Route::get('/my-courses', [StudentController::class, 'myCourses'])
    ->middleware(['auth', 'verified'])
    ->name('my.courses');

Route::post('/student/rate-course', [StudentController::class, 'rateCourse'])
    ->middleware(['auth', 'verified'])
    ->name('student.rate.course');

Route::get('/accont', function () {
    return 'Profile Page (placeholder)';
})->name('accont');

use App\Http\Controllers\CoachController;
use App\Http\Controllers\Auth\GoogleAuthController;
use Laravel\Socialite\Facades\Socialite;

// COACH ROUTE
Route::controller(CoachController::class)
    ->middleware(['auth', 'verified'])
    ->group(function () {
        Route::get('/inbox', 'inbox')->name('coach.inbox');
        Route::get('/accont', 'profile')->name('coach.profile');
        Route::patch('/accont', 'modify')->name('coach.profile.update');
        Route::get('/courses/index', 'coursesIndex')->name('coach.courses.index');
        Route::get('/courses/add', 'coursesAdd')->name('coach.courses.add');
        Route::post('/courses', 'store')->name('coach.courses.store');
        Route::get('/courses/{course}', 'show')->name('coach.courses.show');
        Route::get('/courses/{course}/edit', 'edit')->name('coach.courses.edit');
        Route::PATCH('/courses/{course}', 'update')->name('coach.courses.update');
        Route::delete('/courses/{course}', 'destroy')->name('coach.courses.destroy');

    });




//STUDENT ROUTE
Route::controller(StudentController::class)->middleware(['auth', 'verified'])->group(function () {
    Route::get('/student/dashboard', 'dashboard')->name('student.dashboard');
    Route::get('/student/accont', 'accont')->name('student.accont');
    Route::patch('/student/accont', 'modify')->name('student.profile.update');
    Route::get('/student/inbox', 'inbox')->name('student.inbox');
    Route::post('/payment','processPayment')->name('paymont');
});

Route::get('/cours/{course}', function ($courseId) {
    $course = Course::with(['coach', 'category', 'ratings', 'enrollments'])->findOrFail($courseId);
    
    // Get other courses from the same coach
    $relatedCourses = Course::with(['coach', 'category', 'ratings'])
        ->where('coach_id', $course->coach_id)
        ->where('id', '!=', $course->id)
        ->where('status', 'published')
        ->limit(4)
        ->get();

    // Generate course sections
    $sections = [];
    try {
        $response = Http::timeout(10)
            ->post('http://127.0.0.1:5500/generate-sections', [
                'description' => $course->description
            ]);
        
        if ($response->successful()) {
            $data = $response->json();
            $sections = $data['sections'] ?? [];
            
            // Optional: Save sections to database
            if (!empty($sections) && empty($course->sections)) {
                $course->update(['sections' => json_encode($sections)]);
            }
        } else {
            dd('Section generation failed with status:', $response->status(), 'Response:', $response->body());
            
            // Fallback to existing sections if available
            if (!empty($course->sections)) {
                $sections = json_decode($course->sections, true);
            }
        }
    } catch (\Exception $e) {
        dd('Section generation error:', $e->getMessage(), 'Trace:', $e->getTrace());
        
        // Fallback to existing sections if available
        if (!empty($course->sections)) {
            $sections = json_decode($course->sections, true);
        }
    }

    return view('coursDetails', [
        'course' => $course,
        'relatedCourses' => $relatedCourses,
        'sections' => $sections
    ]);
})->name('cours.details');


Route::get('/payment/{course}', function ($courseId) {
    $course = Course::find($courseId);

    return view('student.pyment', ['course' => $course]);
});

Route::get('/chating/{user}', function ($userId){
    $user = User::findOrFail($userId);
    $current = Auth::user(); 

    $messages = Message::where(function ($query) use ($current, $user) {
            $query->where('sender_id', $current->id)
                  ->where('receiver_id', $user->id);
        })
        ->orWhere(function ($query) use ($current, $user) {
            $query->where('sender_id', $user->id)
                  ->where('receiver_id', $current->id);
        })
        ->orderBy('created_at', 'asc')
        ->get();

    return view('chating', [
        'user' => $user,
        'current' => $current,
        'messages' => $messages
    ]);
});


// sort the message in the database
Route::post('/messages', function (Request $request) {
    try {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'receiver_id' => 'required|integer|exists:users,id',
        ]);

        $senderId = Auth::id() ?? 1;

        $message = Message::create([
            'sender_id'   => $senderId,
            'receiver_id' => $validated['receiver_id'],
            'content'     => $validated['content'],
        ]);

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);

    broadcast(new NewMessage($message));

    return response()->json($message);

    } catch (\Throwable $e) {
        return response()->json([
            'success' => false,
            'error'   => $e->getMessage(),
        ], 500);
    }
});

Route::get('/messages/check-new/{receiver_id}', function ($receiver_id) {
    $currentUserId = Auth::id() ?? 1;
    $lastId = request()->query('after', 0);

    // هات الرسائل الجديدة من الشخص التاني ليا
    $newMessages = Message::where('sender_id', $receiver_id)
        ->where('receiver_id', $currentUserId)
        ->where('id', '>', $lastId)
        ->orderBy('id', 'asc')
        ->get();

    // لو مفيش رسائل جديدة
    if ($newMessages->isEmpty()) {
        return response()->json([
            'status' => 'no_new_messages',
            'messages' => []
        ]);
    }

    // لو فيه رسائل جديدة
    return response()->json([
        'status' => 'new_messages',
        'messages' => $newMessages
    ]);
});







Route::get('/roll', function () {
    return view('rull');
})->name('roll')->middleware(['auth', 'verified']);


Route::patch('/roll', function (Request $request) {
   $user = Auth::user();
   if ($user instanceof User) {

       $user->role = $request->roll;
       $user->save();
   }
   if($user->role === 'student'){
    return redirect(route('student.dashboard', absolute: false));
   }
   if($user->role === 'coach'){
    return redirect(route('coach.dashboard', absolute: false));
   }

})->name('roll.store');


Route::get('/', function () {
        if (Auth::check()) {
            if(Auth::user()->role === 'student'){
                return redirect(route('student.dashboard', absolute: false));
            }
            if(Auth::user()->role === 'coach'){
                return redirect(route('coach.dashboard', absolute: false));
            }
        ;
    }
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



//STUDENT ROUTE


Route::get('/coach/dashboard', [CoachController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('coach.dashboard');



//API ROUTE
Route::post('/api/skills', [SkillController::class, 'store'])->middleware('auth');
Route::delete('/api/skills/delete-by-name', [SkillController::class, 'destroyByName'])->middleware('auth');

//PROFILE ROUTE
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/ai', function () {
    return view('AiPage',['user'=>Auth::user()]);
})->name('ai');

Route::get('/search', function (Request $request) {
    $query = $request->input('q');
    
    if ($query) {
        try {
    
            $response = Http::timeout(10)->get('http://127.0.0.1:5001/search_similar', [
                'description' => $query
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                $similarIds = $data['similar_descriptions_ids'] ?? [];
                
                if (!empty($similarIds)) {
                    // تحويل IDs إلى integers وجلب الكورسات
                    $validIds = array_map('intval', $similarIds);
                    $courses = Course::whereIn('id', $validIds)
                        ->with('coach')
                        ->paginate(8);
                } else {
                    // لا توجد نتائج مشابهة
                    $courses = collect()->paginate(8);
                }
            } else {
                // البحث العادي إذا فشل AI
                $courses = Course::where('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->with('coach')
                    ->paginate(8);
            }
        } catch (\Exception $e) {
            // البحث العادي في حالة الخطأ
            $courses = Course::where('title', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%")
                ->with('coach')
                ->paginate(8);
        }
    } else {
        $courses = Course::latest()->paginate(8);
    }
    
    return view('student.dashboard', [
        'courses' => $courses,
        'user' => Auth::user(),
        'searchQuery' => $query
    ]);
})->name('student.search');

// Test routes for Google OAuth
Route::get('/google-test', function () {
    return view('google-test');
})->name('google.test');

Route::get('/oauth-debug', function () {
    return view('oauth-debug');
})->name('oauth.debug');

Route::get('/google-diagnostic', function () {
    return view('google-diagnostic');
})->name('google.diagnostic');

Route::get('/verify-google', function () {
    return view('verify-google');
})->name('verify.google');

// Google Authentication Routes
Route::controller(GoogleAuthController::class)->group(function () {
    Route::get('/auth/google/redirect', 'redirectToGoogle')->name('google.redirect');
    Route::get('/auth/google/callback', 'handleGoogleCallback')->name('google.callback');
    
    // Google account linking (for logged in users)
    Route::middleware('auth')->group(function () {
        Route::get('/auth/google/link', 'linkGoogleAccount')->name('google.link');
        Route::get('/auth/google/link/callback', 'handleGoogleLink')->name('google.link.callback');
        Route::post('/auth/google/unlink', 'unlinkGoogleAccount')->name('google.unlink');
    });
});

require __DIR__.'/auth.php';
















