<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Course;
use App\Http\Controllers\Api\SkillController;
// use Illuminate\Support\Facades\Hash;
// use Illuminate\Auth\Events\Registered;

Route::get('/my-courses', function () {
    return 'My Courses page (placeholder)';
})->name('my.courses');

Route::get('/accont', function () {
    return 'Profile Page (placeholder)';
})->name('accont');

use App\Http\Controllers\CoachController;

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
Route::get('/student/dashboard', function () {
    $courses = Course::all();
    $user = Auth::user();
    if($user->role !== 'student'){
        return redirect('404');
    }
    return view('student.dashboard',['courses'=>$courses,'user'=>$user]);
})->middleware(['auth', 'verified'])->name('student.dashboard');

Route::get('/coach/dashboard', function () {
    $user = Auth::user();
    if($user->role !== 'coach'){
        return redirect('404');
    }
    return view('coach.dashboard',['user'=>$user]);
})->middleware(['auth', 'verified'])->name('coach.dashboard');



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

require __DIR__.'/auth.php';






