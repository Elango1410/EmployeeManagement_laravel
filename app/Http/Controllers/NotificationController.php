<?php

namespace App\Http\Controllers;

use App\Models\UsersTab;
use Illuminate\Http\Request;
use App\Models\Notifications;
use Illuminate\Support\Carbon;
use App\Models\UsersNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Notifications\Notification;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notification_list = Notifications::select('token', 'title', 'description', 'type','payment_date')->get();

        return response()->json([
            'status_code' => 200,
            'title' => 'success',
            'message' => 'Notification List',
            'data' => $notification_list
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {

        $type = $request->type;
        // return $type;
        $noti_date = Carbon::today()->addDays(7);
        // $noti_date = Carbon::today()-> addWeeks(2);
        // return $noti_date;
        $notification = Notifications::create([
            'token' => rand(100000, 999999),
            'type' => $type,
            'title' => $request->title,
            'description' => $request->description,
            'payment_date' => $noti_date
        ]);
        $notificationToken = $notification->token;
        // return $notificationToken;

        $user_token = UsersTab::select('token')->where('type', $type)->get();
        // return $user_token;

        foreach ($user_token as $userToken) {
            $user_notification = UsersNotification::create([
                'token' => rand(100000, 999999),
                'user_token' => $userToken->token,
                'notification_token' => $notificationToken,

            ]);
        }

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
    public function show(Request $request)
    {
        //
        $token = $request->token;
        $notification_view = Notifications::select('token', 'type', 'title', 'description')
            ->where('token', $token)->get();

        return response()->json([
            'status_code' => 200,
            'title' => 'Success',
            'message' => 'Notification view',
            'data' => $notification_view
        ]);
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
