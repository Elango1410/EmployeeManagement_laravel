<?php

namespace App\Http\Controllers;

use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Notifications\EmailNotification;
use Carbon\Carbon;
use Illuminate\Auth\Events\Validated;
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
                'pass' => $request->password,
                'image' => $image
            ]);
            $auth_token = $user->createToken('registertoken')->plainTextToken;
            $user->notify(new EmailNotification($user));
            return response()->json([
                'admin' => $user,
                'token' => $auth_token,
                'Message' => 'Registered Successfully and Your Credentials sent to your Registered email '

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
            'password' => 'required|min:5|max:20',
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
                'password' => Hash::make($request->password),
                'pass' => $request->password
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


    public function forgetPassword(Request $request)
    {
        $user = User::where('email', $request->email)->get();

        if (count($user) > 0) {
            $token = Str::random(40);
            $domain = URL::to('/');
            $url = $domain . '/reset-password?token=' . $token;
            $data['url'] = $url;
            $data['email'] = $request->email;
            $data['title'] = 'Password Reset';
            $data['body'] = 'Click this link to reset your password..';

            Mail::send('forgetPasswordMail', ['data' => $data], function ($message) use ($data) {
                $message->to($data['email'])->subject($data['title']);
            });

            $datetime = Carbon::now()->format('Y-m-d H:i:s');


            PasswordReset::updateOrCreate(
                ['email' => $request->email],
                [
                    'email' =>  $request->email,
                    'token' => $token,
                    'created_at' => $datetime
                ]
            );

            return response()->json([
                'success' => true, 'msg' => 'Please check your email to reset password'
            ]);
        } else {
            return response()->json([
                'message' => 'No data found'
            ]);
        }
    }

    public function resetPasswordLoad(Request $request)
    {
        $resetData = PasswordReset::where('token', $request->token)->get();
        if (isset($request->token) && count($resetData) > 0) {
            $user = User::where('email', $resetData[0]['email'])->get();
            return view('resetPassword', compact('user'));
        }
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => '|required|string|min:3|same:confirm_password'
        ]);
        $user = User::find($request->id);
        $user->password = $request->password;
        $user->pass = $request->password;
        $user->save();

        return "<h1>Password Changed successfully.</h1>";
    }
}
