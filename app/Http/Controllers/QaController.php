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
        $project->user_id = $request->userID;
        $project->project_url = $request->projectUrl;
        $project->status = "pending";
        $project->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Project submitted for QA.'
        ], 200);

    }

    public function fetchUserProjects($user_id) {
        $projects = Project::where('user_id', $user_id)->get();

        if(count($projects) > 0){
            return response()->json([
                'status' => 'success',
                'projects' => $projects
            ]);
        } else {
            return response()->json([
                'status' => 'success',
                'projects' => [],
                'message' => 'No project added'
            ]);
        }
    }

    public function fetchAllProjects() {
        $projects = Project::all();

        if(count($projects) > 0){
            return response()->json([
                'status' => 'success',
                'projects' => $projects
            ]);
        } else {
            return response()->json([
                'status' => 'success',
                'projects' => [],
                'message' => 'No project added'
            ]);
        }
    }

    public function fetchProjectDetails($id){
        $project = Project::with('qas')->where(['id' => $id])->get();

        return response()->json([
            'project' => $project
        ]);
    }
}
