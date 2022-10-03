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

        $currentUserInfo = Location::get('185.72.217.91');
        $user = User::find(Auth::user()->id);

        if ($currentUserInfo) {
            $user->latitude = $currentUserInfo->latitude;
            $user->longitude = $currentUserInfo->longitude;
            $user->save();
        }

        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
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

        // Uploading file to the server

        if ($request->encryptedImage) {
            $image_no = time(); //imageid
            $image = base64_decode($request->encryptedImage);
            $path = "uploads/" . $image_no . "." . $request->extension;
            file_put_contents($path, $image);
            $user->image = $path;
        }

        $user->gender = $request->gender;
        $user->intersted_in = $request->intersted_in;
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

        if ($request->encryptedImage) {
            $image_no = time(); //imageid
            $image = base64_decode($request->encryptedImage);
            $path = "uploads/" . $image_no . "." . $request->extension;
            file_put_contents($path, $image);
            $user->image = $path;
        }
        
        $user->gender = $request->gender ? $request->gender : $user->gender;
        $user->intersted_in = $request->intersted_in ? $request->intersted_in : $user->intersted_in;
        $user->invisible = $request->invisible ? $request->invisible : $user->invisible;
        $user->password = $request->password ? Hash::make($request->password) : $user->password;


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
