<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Stevebauman\Location\Facades\Location;
use App\Models\User;
use Symfony\Component\Mime\Header\Headers;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);

        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();
        return response()->json([
            'status' => 'success',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    function register(Request $request)
    {
        $user = new User;

        $user->name = $request->name;
        $user->bio = $request->bio;
        $user->email = $request->email;
        $user->age = $request->age;
        $user->location = $request->location;
        $user->image = $request->image;
        $user->password = Hash::make($request->password);

        if ($user->save()) {
            $token = Auth::login($user);
            return response()->json([
                'status' => 'success',
                'message' => 'User created successfully',
                'user' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);
        }
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

    public function editUser(Request $request)
    {
        $user = User::find(Auth::user()->id);

        $user->name = $request->name ? $request->name : $user->name;
        $user->bio = $request->bio ? $request->bio : $user->bio;
        $user->email = $request->email ? $request->email : $user->email;
        $user->age = $request->age ? $request->age : $user->age;
        $user->location = $request->location ? $request->location : $user->location;
        $user->image = $request->image ? $request->image : $user->image;
        $user->password = $request->password ? Hash::make($request->password) : $user->password;
        // $ip = '162.159.24.227'; /* Static IP address */
        // $currentUserInfo = Location::get($ip);    
        // print_r($currentUserInfo);          
        print_r($_SERVER['REMOTE_ADDR']);
        if ($user->save()) {
            return response()->json([
                'status' => 'success',
                'message' => 'User edited successfully',
                'user' => $user,
                'authorisation' => [
                    'token' => Auth::refresh(),
                    'type' => 'bearer',
                ]
            ]);
        }
    }
}
