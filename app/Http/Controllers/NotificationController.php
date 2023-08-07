<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notifications;
use App\Models\UsersNotification;
use App\Models\UsersTab;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return 1;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $notification=[];
        // $type[] = $request->type;
        foreach($request['type'] as $type){
            $notification = Notifications::create([
                'token' => rand(100000, 999999),
                'type' => $type,
                'title' => $request->title,
                'description' => $request->description
            ]);
        $notificationToken[] = $notification->token;

        }

            return $notificationToken;


        // $user_token = UsersTab::select('token')->where('type', $type)->get();
        // // return $user_token;

        // foreach ($user_token as $userToken) {
        //     $user_notification = UsersNotification::create([
        //         'token' => rand(100000, 999999),
        //         'user_token' => $userToken->token,
        //         'notification_token' => $notificationToken,

        //     ]);
        // }

        return response()->json([
            'status_code' => 200,
            'message' => 'Created successfully',
            'title' => 'Success',
            'data' => []

        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
