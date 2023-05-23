<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        return view('profile.profile', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();

        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'current_password' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!Hash::check($value, Auth::user()->password)) {
                        $fail(__('The current password is incorrect.'));
                    }
                },
            ],
        ]);

        // Memeriksa apakah ada perubahan yang dilakukan
        if ($user->name === $request->input('name') && $user->email === $request->input('email') && empty($request->input('password'))) {
            return redirect()->route('profile')->with('warning', 'No changes were made.');
        }

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        if (!empty($request->input('password'))) {
            $user->password = bcrypt($request->input('password'));
        }
        $user->save();

        return redirect()->route('profile')->with('success', 'Profile updated successfully.');
    }
}
