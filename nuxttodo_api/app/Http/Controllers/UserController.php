<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function allUsers()
    {
        $users = User::all();
        return response()->json([
            'users' => $users,
            'message' => 'All users'
        ], 200);
    }

    public function checkUser()
    {
        if (Auth::user()) {
            return response()->json([
                'message' => 'User authenticated'
            ], 200);
        } else {
            return response()->json([
                'message' => 'User not authenticated'
            ], 401);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();


        return response()->json([
            'message' => Auth::user()
        ]);
        // try {
        //     Auth::logout();
        //     return response()->json([
        //         'message' => 'Logged out successfully'
        //     ], 200);
        // } catch (\Throwable $th) {
        //     return response()->json([
        //         'message' => 'Failed to log out'
        //     ], 500);
        // }
    }
}
