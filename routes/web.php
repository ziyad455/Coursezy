<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Course;
use App\Http\Controllers\Api\SkillController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
    // Redirect authenticated users to their role-specific dashboard
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->role === 'student') {
            return redirect()->route('student.dashboard');
        } elseif ($user->role === 'coach') {
            return redirect()->route('coach.dashboard');
        } else {
            // If user doesn't have a role, redirect to role selection
            return redirect()->route('roll');
        }
    }
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
})->middleware(['auth', 'verified'])->name('ai');

// Messaging Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/messages', [\App\Http\Controllers\MessageController::class, 'index'])->name('messages');
    Route::get('/messages/conversations', [\App\Http\Controllers\MessageController::class, 'getConversations'])->name('messages.conversations');
    Route::get('/messages/search-users', [\App\Http\Controllers\MessageController::class, 'searchUsersForNewConversation'])->name('messages.search-users');
    Route::get('/messages/{user}', [\App\Http\Controllers\MessageController::class, 'index'])->name('messages.chat');
    Route::post('/messages/send', [\App\Http\Controllers\MessageController::class, 'send'])->name('messages.send');
    Route::get('/messages/get/{user}', [\App\Http\Controllers\MessageController::class, 'getMessages'])->name('messages.get');
    Route::get('/messages/unread/count', [\App\Http\Controllers\MessageController::class, 'getUnreadCount'])->name('messages.unread');
    
    // Pusher test page
    Route::get('/pusher-test', function() {
        return view('pusher-test');
    })->name('pusher.test');
});

// AI Chat Routes
use App\Http\Controllers\AIController;

Route::middleware(['auth'])->group(function () {
    Route::post('/ai/chat', [AIController::class, 'chat'])->name('ai.chat');
    Route::get('/ai/test-connection', [AIController::class, 'testConnection'])->name('ai.test');
});

Route::get('/search', function (Request $request) {
    $query = $request->input('q');
    
    if ($query) {
        try {
    
            $response = Http::timeout(10)->get('http://127.0.0.1:5500/search_similar', [
                'description' => $query
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                $similarIds = $data['similar_descriptions_ids'] ?? [];
                
                if (!empty($similarIds)) {
                    
                    $validIds = array_map('intval', $similarIds);
                    $courses = Course::whereIn('id', $validIds)
                        ->with('coach')
                        ->paginate(8);
                } else {
                    
                    $courses = collect()->paginate(8);
                }
            } else {
                
                $courses = Course::where('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->with('coach')
                    ->paginate(8);
            }
        } catch (\Exception $e) {
            
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

// Google OAuth Debug Route
Route::get('/google-oauth-debug', function() {
    return [
        'google_client_id' => config('services.google.client_id'),
        'google_client_secret' => substr(config('services.google.client_secret'), 0, 10) . '...',
        'google_redirect' => config('services.google.redirect'),
        'app_url' => config('app.url'),
        'session_driver' => config('session.driver'),
        'session_domain' => config('session.domain'),
        'session_same_site' => config('session.same_site'),
    ];
});

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
















