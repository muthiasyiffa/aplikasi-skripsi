<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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
