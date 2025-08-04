<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Course;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

Route::get('/my-courses', function () {
    return 'My Courses page (placeholder)';
})->name('my.courses');

Route::get('/accont', function () {
    return 'Profile Page (placeholder)';
})->name('accont');

use App\Http\Controllers\CoachController;

Route::prefix('coach')->name('coach.')->controller(CoachController::class)->group(function () {
    Route::get('/inbox', 'inbox')->name('inbox');
    Route::get('/profile', 'profile')->name('profile');
    Route::get('/courses/index', 'coursesIndex')->name('courses.index');
    Route::get('/courses/add', 'coursesAdd')->name('courses.add');
});


Route::get('/roll', function () {
    return view('rull');
})->name('roll');


Route::patch('/roll', function (Request $request) {
   $user = Auth::user();
   if ($user instanceof User) {

       $user->role = $request->roll;
       $user->save();
   }
   return redirect('/dashboard');
})->name('roll.store');


Route::get('/', function () {
        if (Auth::check()) {
        return redirect('/dashboard');
    }
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

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




Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
