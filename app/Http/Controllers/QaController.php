<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Qa;

class QaController extends Controller
{
    public function submitProject(Request $request)
    {
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

    public function fetchUserProjects($user_id)
    {
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

    public function fetchAllProjects()
    {
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

    public function fetchProjectDetails($id)
    {
        $project = Project::with('qas')->where(['id' => $id])->get();

        return response()->json([
            'project' => $project
        ]);
    }

    public function submitQa(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'qaUrl'  => 'required|string',
            'qaComment' => 'required|string'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 406);
        }

        $qa = new Qa;
        $qa->user_id = $request->userID;
        $qa->project_id = $request->projectID;
        $qa->qa_url = $request->qaUrl;
        $qa->qa_comment = $request->qaComment;
        $qa->status = 'Pending';
        $qa->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Qa added'
        ], 200);
    }
}
