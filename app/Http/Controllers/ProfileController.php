<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Show user profile
     */
    public function show()
    {
        $user = Auth::user();
        
        // Get user statistics
        $stats = [
            'total_ratings' => $user->ratings()->count(),
            'total_comments' => $user->comments()->count(),
            'reading_progress' => $user->readingProgress()->count(),
        ];

        return view('profile.show', compact('user', 'stats'));
    }

    /**
     * Show edit profile form
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'username' => [
                'required',
                'string',
                'max:50',
                'unique:users,username,' . $user->user_id . ',user_id',
                'alpha_dash',
            ],
            'display_name' => [
                'nullable',
                'string',
                'max:100',
            ],
            'avatar' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif',
                'max:2048', // 2MB
            ],
        ], [
            'username.unique' => 'Username already taken by another user.',
            'username.alpha_dash' => 'Username can only contain letters, numbers, dashes and underscores.',
            'avatar.image' => 'Avatar must be an image.',
            'avatar.mimes' => 'Avatar must be a file of type: jpeg, png, jpg, gif.',
            'avatar.max' => 'Avatar size must not exceed 2MB.',
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && \Storage::disk('public')->exists(str_replace('/storage/', '', $user->avatar))) {
                \Storage::disk('public')->delete(str_replace('/storage/', '', $user->avatar));
            }

            // Upload new avatar
            $file = $request->file('avatar');
            $filename = $user->user_id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('avatars', $filename, 'public');
            $validated['avatar'] = '/storage/' . $path;
        }

        $user->update($validated);

        return redirect()->route('profile.show')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => 'required',
            'new_password' => [
                'required',
                'confirmed',
                Password::min(6)
                    ->letters()
                    ->numbers(),
            ],
        ], [
            'current_password.required' => 'Current password is required.',
            'new_password.required' => 'New password is required.',
            'new_password.confirmed' => 'New password confirmation does not match.',
        ]);

        // Check if current password is correct
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors([
                'current_password' => 'Current password is incorrect.',
            ])->withInput();
        }

        // Update password
        $user->update([
            'password' => Hash::make($validated['new_password']),
        ]);

        return redirect()->route('profile.show')
            ->with('success', 'Password changed successfully!');
    }

    /**
     * Delete avatar
     */
    public function deleteAvatar()
    {
        $user = Auth::user();

        // Delete avatar file if exists
        if ($user->avatar && \Storage::disk('public')->exists(str_replace('/storage/', '', $user->avatar))) {
            \Storage::disk('public')->delete(str_replace('/storage/', '', $user->avatar));
        }

        // Set avatar to null (will use initials avatar)
        $user->update(['avatar' => null]);

        return redirect()->route('profile.edit')
            ->with('success', 'Avatar removed successfully! Using default initials avatar.');
    }
}