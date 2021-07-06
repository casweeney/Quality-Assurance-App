<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class QaController extends Controller
{
    public function submitProject(Request $request){
        $validator = \Validator::make($request->all(), [
            'projectName'  => 'required|string',
            'projectUrl' => 'required|string'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 406);
        }

        $project = new Project;
        $project->project_name = $request->projectName;
        $project->developer = $request->userName;
        $project->project_url = $request->projectUrl;
        $project->status = "pending";
        $project->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Project submitted for QA.'
        ], 200);

    }
}
