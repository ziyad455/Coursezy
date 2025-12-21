<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingsController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index(): View
    {
        $user = Auth::user();
        
        return view('settings.index', [
            'user' => $user,
            'hasPassword' => !is_null($user->password),
            'hasGoogleAccount' => !is_null($user->google_id),
        ]);
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $hasPassword = !is_null($user->password);

        $rules = [
            'new_password' => ['required', 'string', Password::defaults(), 'confirmed'],
        ];

        // If user already has a password, require current password validation
        if ($hasPassword) {
            $rules['current_password'] = ['required', 'string'];
        }

        $request->validate($rules);

        // Verify current password if user has one
        if ($hasPassword && !Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'The provided password does not match your current password.'
            ]);
        }

        // Update the password
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('status', $hasPassword ? 'Password updated successfully.' : 'Password created successfully.');
    }

    /**
     * Update the user's appearance preferences.
     */
    public function updateAppearance(Request $request): RedirectResponse
    {
        $request->validate([
            'dark_mode' => ['required', 'boolean'],
        ]);

        $darkMode = (bool) $request->dark_mode;

        // Update user preference in database
        Auth::user()->update([
            'dark_mode' => $darkMode
        ]);

        // Store in session for immediate effect across all pages
        session(['dark_mode' => $darkMode]);

        return back()->with('status', 'Appearance preferences updated successfully.');
    }
}