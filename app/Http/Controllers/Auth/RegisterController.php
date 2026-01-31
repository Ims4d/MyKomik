<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /**
     * Show registration form
     */
    public function showRegistrationForm()
    {
        if (Auth::check()) {
            return redirect('/home');
        }

        return view('auth.register');
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => [
                'required',
                'string',
                'max:50',
                'unique:users,username',
                'alpha_dash', // only letters, numbers, dashes and underscores
            ],
            'display_name' => [
                'nullable',
                'string',
                'max:100',
            ],
            'password' => [
                'required',
                'confirmed',
                Password::min(6)
                    ->letters()
                    ->numbers(),
            ],
        ], [
            'username.required' => 'Username is required.',
            'username.unique' => 'Username already taken.',
            'username.alpha_dash' => 'Username can only contain letters, numbers, dashes and underscores.',
            'password.required' => 'Password is required.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        // Create user with 'user' role by default
        $user = User::create([
            'username' => $validated['username'],
            'display_name' => $validated['display_name'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'user', // Default role
        ]);

        // Auto login after registration
        Auth::login($user);

        return redirect('/home')->with('success', 'Registration successful! Welcome to Comic Reader.');
    }
}