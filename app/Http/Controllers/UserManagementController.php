<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewUserEmail;

class UserManagementController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $users = User::all();

        return view('user.user-management', compact('users'));
    }

    public function edit()
    {
        $users = User::all();
        return view('user.edit', compact('users'));
    }

    public function create()
    {
        return view('user.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
        ]);

        Mail::to($user->email)->send(new NewUserEmail($user));

        return redirect()->route('user-management')->with('success', 'User created successfully.');
    }

    public function update(Request $request, User $user)
    {
        $usersData = $request->input('users');
        $hasChanges = false;

        foreach ($usersData as $userId => $userData) {
            $user = User::find($userId);
            if ($user->role !== $userData['role']) {
                $user->role = $userData['role'];
                $user->save();
                $hasChanges = true;
            }
        }

        if (!$hasChanges) {
            return redirect()->route('user-management')->with('warning', 'No changes were made.');
        } else {
            return redirect()->route('user-management')->with('success', 'Role updated successfully.');
        }
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('user-management')->with('success', 'User successfully deleted.');
    }
}
