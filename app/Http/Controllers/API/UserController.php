<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $email    = $request->get('email');
        $password = $request->get('password');

        if (Auth::attempt(compact('email', 'password'))) {
            $user         = auth()->user();
            $access_token = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'status'  => true,
                'message' => "User Authenticated Successfully",
                'token'   => $access_token,
                'role'    => $user->role
            ]);
        } else {
            return response()->json([
                'status'  => false,
                'message' => "Invalid Username or Password"
            ]);
        }
    }

    public function register(UserRequest $request)
    {
        $user = new User;

        $user->name     = $request->name;
        $user->phone    = $request->phone;
        $user->email    = $request->email;
        $user->password = bcrypt($request->password);
        $user->role     = $request->role;

        $user->save();

        $access_token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'status'  => true,
            'message' => "User Registered Successfully",
            'token'   => $access_token,
            'role'    => $user->role
        ]);
    }

    public function update(UserRequest $request)
    {
        $AuthUser = Auth::user();

        if ($AuthUser) {

            $user = User::find($AuthUser->id);
            // Update the user's data
            $user->name     = $request->name             ?? $user->name;
            $user->phone    = $request->phone            ?? $user->phone;
            $user->email    = $request->email            ?? $user->email;
            $user->password = bcrypt($request->password) ?? $user->password;
            $user->role     = $request->role             ?? $user->role;

            $user->save();

            return response()->json([
                'status'  => true,
                'message' => 'User updated successfully',
                'data'    => $user
            ], 200);
        } else {
            return response()->json([
                'status'  => false,
                'message' => 'Unauthorized action or invalid user ID'
            ], 401);
        }
    }

    public function delete()
    {

        $AuthUser = Auth::user();

        if ($AuthUser) {
            $user = User::find($AuthUser->id);

            $user->delete();

            return response()->json([
                'status'  => true,
                'message' => 'User deleted successfully'
            ]);
        } else {
            return response()->json([
                'status'  => false,
                'message' => 'Unauthorized action or invalid user ID'
            ]);
        }
    }

    public function getLoggedInUser()
    {
        $LoggedUser = Auth::user()->id;

        $user = User::find($LoggedUser);

        return response()->json([
            'status'  => true,
            'data' => $user
        ]);
    }
}
