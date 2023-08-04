<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notifications;
use App\Models\UsersNotification;
use App\Models\UsersTab;

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
        //
        $type = $request->type;
        $notification = Notifications::create([
            'token' => rand(100000, 999999),
            'type' => $type,
            'title'=>$request->title,
            'description' => $request->description
        ]);
$notification_token=$notification->token;
        $user_token=UsersTab::select('token')->where('type',$type);


        foreach ($user_token as $userToken) {
        $user_notification=UsersNotification::create([
            'token'=>rand(100000,999999),
            'user_token'=>$userToken,
            'notification_token'=>$notification_token,

        ]);
    }

    return response()->json([
        'status_code'=>200,
        'message'=>'created successfull'
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
