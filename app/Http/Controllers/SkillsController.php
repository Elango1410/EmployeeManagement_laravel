<?php

namespace App\Http\Controllers;

use App\Models\Skills;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SkillsController extends Controller
{
    //
    public function create_skills(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|array|min:1',
            'name.*' => 'required|string|min:3'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'Error' => $validate->messages()
            ]);
        } else {

            $skill = [];
            foreach ($request['name'] as $name) {
                $skills = Skills::create([
                    'name' => $name,
                    'token' => rand(100000, 999999)
                ]);

                $skill[] = $skills;
            }

            return response()->json([
                'skills' => $skill
            ]);
        }
    }

    public function list_skills()
    {
        $skills=Skills::select('name','token')->get();
        $skills_count=count($skills);
        if($skills_count>0){
            return response()->json([
                'skills_count'=>$skills_count,
                'list'=>$skills
            ]);
        }
    }


    public function remove_skill(Request $request){
        $skill_delete_count=Skills::where('token',$request->token)->count();
        if($skill_delete_count>0){
            Skills::where('token',$request->token)->delete();
            return response()->json([
                'message'=>'Skill deleted successfully'
            ]);
        }else{
            return response()->json([
                'message'=>'No Record '
            ]);
        }
    }
}
