<?php

namespace App\Http\Controllers;

use App\Models\Memberships;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $member_list = Memberships::select('plan_id', 'plan_name', 'plan_duration', 'plan_amount')
            ->selectRaw('date_format(created_at, "%d %b,%Y") as date')
            ->get();
        return response()->json([
            'status_code' => 200,
            'title' => 'success',
            'message' => 'membership list',
            'data' => $member_list
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $membership = Memberships::create([
            'plan_id' => rand(100000, 999999),
            'plan_name' => $request->plan_name,
            'plan_duration' => $request->plan_duration,
            'plan_amount' => $request->plan_amount,
            'benefits' => $request->benefits,
            'expiry_date'=>$request->expiry_date
        ]);

        return response()->json([
            'status_code' => 200,
            'title' => 'Success',
            'message' => 'Plane created Successfully',
            'data' => $membership
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
        $plan_view = Memberships::select('plan_id', 'plan_name', 'plan_duration as plan_type', 'plan_amount', 'benefits')
            ->where('plan_id', $request->plan_id)->get();
        if (count($plan_view) === 1) {
            return response()->json([
                'status_code' => 200,
                'title' => 'success',
                'message' => 'plan_view',
                'data' => $plan_view
            ]);
        } else {
            return response()->json([
                'status_code' => 400,
                'title' => 'fails',
                'message' => 'No record',
                'data' => []
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {

        $plan_edit = Memberships::where('plan_id', $request->plan_id)->update([
            'plan_name' => $request->plan_name,
            'plan_duration' => $request->plan_duration,
            'plan_amount' => $request->plan_amount,
            'benefits' => $request->benefits
        ]);
        if ($plan_edit) {
            return response()->json([
                'status_code' => 200,
                'title' => 'Success',
                'message' => 'Updated Successfully',
                'data' => []
            ]);
        }
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
    public function destroy(Request $request)
    {
        //
        $plan_delete = Memberships::select()
            ->where('plan_id', $request->plan_id)->delete();

        if ($plan_delete) {
            return response()->json([
                'status_code' => 200,
                'title' => 'Success',
                'message' => 'Record Deleted Successfully',
                'data' => []
            ]);
        } else {
            return response()->json([
                'status_code' => 400,
                'title' => 'Failed',
                'message' => 'No Record Deleted',
                'data' => []
            ]);
        }
    }
}
