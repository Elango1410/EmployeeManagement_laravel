<?php

namespace App\Http\Controllers;

use App\Models\UsersTab;
use Carbon\Carbon;
use Illuminate\Http\Request;
use StdClass;

class UsertabController extends Controller
{
    //

    public function manage_user(Request $request)
    {
        $type = $request->type;

        if ($type == 0) {
            //yet to be verified
            $verify_user = UsersTab::select('token', 'mobile_number', 'imr_number', 'status')
                ->selectRaw('date_format(created_at, "%d/%m/%Y") as date')
                ->where('status', '=', '0')->get();
            $verify_user_count = count($verify_user);
            if ($verify_user_count > 1) {
                $slno = 1;
                $verify_arr = [];
                foreach ($verify_user as $verify_user) {
                    $obj = new StdClass;
                    $obj->slno = $slno;
                    $obj->userId = $verify_user->token;
                    $obj->mobileNUmber = $verify_user->mobile_number;
                    $obj->imrNumber = $verify_user->imr_number;
                    $obj->registered_date = $verify_user->date;
                    $obj->status = $verify_user->status;
                    $slno++;
                    array_push($verify_arr, $obj);
                }
                return response()->json([
                    'status_code' => 200,
                    'title' => 'Success',
                    'message' => 'Verify List',
                    'Total count' => $verify_user_count,
                    'data' => $verify_arr
                ]);
            } else {
                return response()->json([
                    'status_code' => 400,
                    'title' => 'faile',
                    'message' => 'Verify List',
                    'Total count' => 0,
                    'data' => []
                ]);
            }
        } else if ($type == 1) {
            //active user
            $active_user = UsersTab::select('token', 'name', 'mobile_number', 'gender', 'education', 'location', 'imr_number', 'type')
                ->selectRaw('date_format(created_at, "%d/%m/%Y") as date')
                ->where('status', '=', '1')->get();
            $user_active_count = count($active_user);
            if ($user_active_count > 1) {
                $slno = 1;
                $active_arr = [];
                foreach ($active_user as $active_user) {
                    $obj = new StdClass;
                    $obj->slno = $slno;
                    $obj->userId = $active_user->token;
                    $obj->userName = $active_user->name;
                    $obj->contactNumber = $active_user->mobile_number;
                    $obj->gender = $active_user->gender;
                    $obj->education = $active_user->education;
                    $obj->location = $active_user->location;
                    $obj->imr_number = $active_user->imr_number;
                    $obj->type = $active_user->type;
                    $obj->registrationDate = $active_user->date;
                    $slno++;
                    array_push($active_arr, $obj);
                }
                return response()->json([
                    'status_code' => 200,
                    'title' => 'Success',
                    'message' => 'Active Users List',
                    'Total Count' => $user_active_count,
                    'data' => $active_arr
                ]);
            } else {
                return response()->json([
                    'status_code' => 400,
                    'title' => 'Fails',
                    'message' => 'No List',
                    'data' => []
                ]);
            }
        } else if ($type == 2) {
            //blocked user
            $blocked_user = UsersTab::select('token', 'name', 'mobile_number', 'gender', 'education', 'location', 'imr_number', 'type')
                ->selectRaw('date_format(created_at, "%d/%m/%Y") as date')
                ->where('status', '=', '2')->get();
            $block_active_count = count($blocked_user);
            if ($block_active_count > 1) {
                $slno = 1;
                $block_arr = [];
                foreach ($blocked_user as $blocked_user) {
                    $obj = new StdClass;
                    $obj->slno = $slno;
                    $obj->userId = $blocked_user->token;
                    $obj->userName = $blocked_user->name;
                    $obj->contactNumber = $blocked_user->mobile_number;
                    $obj->gender = $blocked_user->gender;
                    $obj->education = $blocked_user->education;
                    $obj->location = $blocked_user->location;
                    $obj->imr_number = $blocked_user->imr_number;
                    $obj->type = $blocked_user->type;
                    $obj->registrationDate = $blocked_user->date;
                    $slno++;
                    array_push($block_arr, $obj);
                }
                return response()->json([
                    'status_code' => 200,
                    'title' => 'Success',
                    'message' => 'Blocked Users List',
                    'Total Count' => $block_active_count,
                    'data' => $block_arr
                ]);
            } else {
                return response()->json([
                    'status_code' => 400,
                    'title' => 'Fails',
                    'message' => 'No List',
                    'data' => []
                ]);
            }
        } else if ($type == 3) {
            //inactive User
            $inactive_user = UsersTab::select('token', 'name', 'mobile_number', 'gender', 'education', 'location', 'imr_number', 'type')
                ->selectRaw('date_format(created_at, "%d/%m/%Y") as date')
                ->where('status', '=', '3')->get();
            $inactive_count = count($inactive_user);
            if ($inactive_count > 1) {
                $slno = 1;
                $inactive_arr = [];
                foreach ($inactive_user as $inactive_user) {
                    $obj = new StdClass;
                    $obj->slno = $slno;
                    $obj->userId = $inactive_user->token;
                    $obj->userName = $inactive_user->name;
                    $obj->contactNumber = $inactive_user->mobile_number;
                    $obj->gender = $inactive_user->gender;
                    $obj->education = $inactive_user->education;
                    $obj->location = $inactive_user->location;
                    $obj->imr_number = $inactive_user->imr_number;
                    $obj->type = $inactive_user->type;
                    $obj->registrationDate = $inactive_user->date;
                    $slno++;
                    array_push($inactive_arr, $obj);
                }
                return response()->json([
                    'status_code' => 200,
                    'title' => 'Success',
                    'message' => 'inactive Users List',
                    'Total Count' => $inactive_count,
                    'data' => $inactive_arr
                ]);
            } else {
                return response()->json([
                    'status_code' => 400,
                    'title' => 'Fails',
                    'message' => 'No List',
                    'data' => []
                ]);
            }
        } else if ($type == 4) {
            //recently joined
            $date = Carbon::today()->subDays(30);
            $recent_join = UsersTab::select('token', 'mobile_number', 'imr_number')
                ->selectraw('date_format(created_at, "%d/%m/%Y") as date')
                ->orderBy('created_at', 'DESC')
                ->where('created_at', '>=', $date)->get();
            // return $recent_join;
            $recent_join_count = count($recent_join);

            if ($recent_join_count > 1) {
                $slno = 1;
                $recent_arr = [];
                foreach ($recent_join as $recent_join) {
                    $obj = new StdClass;
                    $obj->slno = $slno;
                    $obj->mobile_number = $recent_join->mobile_number;
                    $obj->license_number = $recent_join->imr_number;
                    $obj->registered_date = $recent_join->date;
                    $slno++;
                    array_push($recent_arr, $obj);
                }
                return response()->json([
                    'status_code' => 200,
                    'title' => 'Success',
                    'message' => 'recent joined list',
                    'Total Count' => $recent_join_count,
                    'data' => $recent_arr
                ]);
            } else {
                return response()->json([
                    'status_code' => 400,
                    'title' => 'Fails',
                    'message' => 'No list',
                    'Total Count' => 0,
                    'data' => []
                ]);
            }
        }
    }
}
