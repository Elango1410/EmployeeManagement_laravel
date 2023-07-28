<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //
    public function register_user(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string|min:3',
            'email' => 'required|email',
            'password' => 'required|string|min:4|max:15',
            'c_password' => 'required|same:password'
        ]);
        if ($validate->fails()) {
            return response()->json([
                'error' => $validate->messages()
            ]);
        } else {
            $image = "";
            if ($request->hasFile('image')) {
                $image = $request->file('image')->store('post', 'public');
            } else {
                $image = "null";
            }

            $user = User::create([
                'token' => rand(100000, 999999),
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'image' => $image
            ]);
            $auth_token = $user->createToken('registertoken')->plainTextToken;

            return response()->json([
                'admin' => $user,
                'token' => $auth_token
            ]);
        }
    }


    public function login_user(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'error' => $validate->messages()
            ]);
        } else {
            $user = User::where('email', $request->email)->first();
            if ($user && Hash::check($request->password, $user->password)) {
                $login_token = $user->createToken('loginToken')->plainTextToken;
                return response()->json([
                    'Message' => 'Login Success',
                    'login_token' => $login_token
                ]);
            } else {
                return response()->json([
                    'Message' => 'Invalid email or password'
                ]);
            }
        }
    }

    public function update_user(Request $request)
    {
        $image = "";
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('update', 'public');
        } else {
            $image = "null";
        }
        $user = User::where('token', $request->token)->update([
            'name' => $request->name,
            'image' => $image
        ]);
        return response()->json([
            'name' => $request->name,
            'image' => $image
        ]);
    }

    public function logout_user(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'Message' => 'Logout success'
        ]);
    }


    public function change_password(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'old_pass' => 'required',
            'password' => 'required|min:5|max:10',
            'c_pass' => 'required|same:password'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'Error' => $validate->messages()
            ]);
        }
        $user = $request->user();
        if (Hash::check($request->old_pass, $user->password)) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);
            return response()->json([
                'Message' => 'Password Changed'
            ]);
        } else {
            return response()->json([
                'Message' => 'Old password does not match '
            ]);
        }
    }
}
