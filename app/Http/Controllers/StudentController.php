<?php

namespace App\Http\Controllers;
use App\Models\Course;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Support\Facades\Storage;
use App\Models\Enrollment;




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
        
return redirect()->route('student.dashboard')->with('success', true);


        
    } catch (\Exception $e) {
        dd('Error:', $e->getMessage());
    }
    

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
