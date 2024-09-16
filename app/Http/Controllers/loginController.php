<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class loginController extends Controller
{
    // Show login form
    public function index()
    {
        $title = "login";
        return view('login', compact('title'));
    }

    public function createUser()
    {
        $title = "Sign-Up";
        return view('create', compact('title'));
    }


    public function createUserStore(Request $request)
    {
        // dd($request->all());
        // Hash the password before storing it
        $hashedPassword = Hash::make($request->password);

        // Create the user
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $hashedPassword,
        ]);

        // Redirect to login view or other page
        return redirect('login')->with('success', 'User created successfully. Please login.');
    }

    // Handle login logic
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->route('image-data')->with('success', 'Login successful.');
        }

        return redirect()->back()->withErrors(['email' => 'The provided credentials do not match our records.']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
