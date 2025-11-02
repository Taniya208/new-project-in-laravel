<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show the login page
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Show the registration page
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle registration logic
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);

        // Create user
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Redirect with success message
        return redirect()->route('login')->with('success', 'Registration successful! Please login.');
    }

    /**
     * Handle login logic
     */
   public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->route('contacts'); // ✅ corrected here
    }

    return back()->with('error', 'Invalid login details.');
}


    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
return redirect()->route('contacts');
