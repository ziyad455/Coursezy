<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class GoogleAuthController extends Controller
{
    /**
     * Redirect to Google authentication page
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    /**
     * Handle Google callback
     */
    public function handleGoogleCallback()
    {
        try {
            // Get user from Google
            $googleUser = Socialite::driver('google')->stateless()->user();
            
            // Check if user exists
            $user = User::where('email', $googleUser->email)->first();
            
            if ($user) {
                // User exists, update Google ID if not set
                if (!$user->google_id) {
                    $user->update([
    'google_id' => $googleUser->id,
   'profile_photo' => $user->profile_photo ?? ($googleUser->avatar ?? null)
]);
                }
                
                // Login the user
                Auth::login($user);
                
                // Redirect based on role
                if ($user->role === 'coach') {
                    return redirect()->route('coach.dashboard')->with('success', 'Welcome back, ' . $user->name . '!');
                } elseif ($user->role === 'student') {
                    return redirect()->route('student.dashboard')->with('success', 'Welcome back, ' . $user->name . '!');
                } else {
                    // If user doesn't have a proper role, redirect to role selection
                    return redirect()->route('roll')->with('info', 'Please select your role to continue.');
                }
            } else {
                // Create new user
                $newUser = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'profile_photo' => $googleUser->avatar,
                    'password' => Hash::make(Str::random(16)), // Random password for OAuth users
                    'email_verified_at' => now(),
                    'role' => 'student', // Default role
                ]);
                
                // Login the new user
                Auth::login($newUser);
                
                // Redirect to student dashboard for new users
                return redirect()->route('roll')->with('success', 'Welcome to Coursezy, ' . $newUser->name . '!');
            }
            
        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
            Log::error('Google OAuth Invalid State: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Invalid state. Please try logging in again.');
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            Log::error('Google OAuth Client Error: ' . $e->getMessage());
            $response = $e->getResponse();
            $responseBody = $response->getBody()->getContents();
            Log::error('Response body: ' . $responseBody);
            return redirect()->route('login')->with('error', 'Google authentication failed. Please check your credentials.');
        } catch (Exception $e) {
            Log::error('Google authentication error: ' . $e->getMessage());
            Log::error('Error type: ' . get_class($e));
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->route('login')->with('error', 'Unable to login with Google. Error: ' . $e->getMessage());
        }
    }
    
    /**
     * Link existing account with Google
     */
    public function linkGoogleAccount()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        return Socialite::driver('google')->redirect();
    }
    
    /**
     * Handle Google account linking callback
     */
    public function handleGoogleLink()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        try {
            $googleUser = Socialite::driver('google')->user();
            $user = Auth::user();
            
            // Check if this Google account is already linked to another user
            $existingUser = User::where('google_id', $googleUser->id)
                ->where('id', '!=', $user->id)
                ->first();
            
            if ($existingUser) {
                return redirect()->back()->with('error', 'This Google account is already linked to another user.');
            }
            
            $user->google_id = $googleUser->id;
            $user->profile_photo = $user->profile_photo ?? $googleUser->avatar;
            if ($user instanceof \Illuminate\Database\Eloquent\Model) {
                $user->save();
            } else {
                // If $user is not an Eloquent model, fetch it again
                $user = User::find($user->id);
                if ($user) {
                    $user->google_id = $googleUser->id;
                    $user->profile_photo = $user->profile_photo ?? $googleUser->avatar;
                    $user->save();
                }
            }
            
            return redirect()->back()->with('success', 'Google account linked successfully!');
            
        } catch (Exception $e) {
            Log::error('Google account linking error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to link Google account. Please try again.');
        }
    }
    
    /**
     * Unlink Google account
     */
    public function unlinkGoogleAccount()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        
        // Check if user has a password set (they need an alternative login method)
        if (!$user->password) {
            return redirect()->back()->with('error', 'You need to set a password before unlinking your Google account.');
        }
        
        $user->google_id = null;
        if ($user instanceof \App\Models\User) {
            $user->save();
        } else {
            $userModel = \App\Models\User::find($user->id);
            if ($userModel) {
                $userModel->google_id = null;
                $userModel->save();
            }
        }
        
        return redirect()->back()->with('success', 'Google account unlinked successfully!');
    }
}
