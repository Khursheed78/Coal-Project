<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function index(){
        return view('LoginView');
    }
    // Registration View
    public function registrationView(){
        return view('registration');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:3',
            'gender'   => 'required|in:male,female',
            'phone'    => 'required|numeric',
            'role'     => 'required|in:admin,manager',
            'profile_image'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('profile_image')) {
            $picturePath = $request->file('profile_image')->store('uploads', 'public');
        } else {
            $picturePath = null;
        }


        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'gender'   => $request->gender,
            'phone'    => $request->phone,
            'role'     => $request->role,
            'profile_image'  => $picturePath,
        ]);

        return redirect()->route('loginView')->with('success', 'Registration successful! Please login.');
    }
    // Login View

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:3',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin.dashboard')->with('success', 'Welcome, Admin!');
                case 'manager':
                    return redirect()->route('manager.dashboard')->with('success', 'Welcome, Manager!');
                default:
                    return redirect()->route('unauthorized')->with('error', 'Unauthorized access.');
            }
        }

        return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }


    public function logout()
    {
        Auth::logout();
        return redirect()->route('loginView')->with('success', 'Logged out successfully');
    }

    public function profile(){
        $user = Auth::user();
        return view('profile', compact('user'));
    }




}
