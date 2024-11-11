<?php

namespace App\Http\Controllers;

use App\Events\ProjectCreated;
use App\Http\Requests\Project\PinProjectRequest;
use App\Http\Requests\Project\StoreRequest;
use App\Http\Requests\Project\UpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskProgress;

class ProjectController extends Controller
{
    public function getProject(Request $request, $slug) {
        $project = Project::with(['tasks.task_members.member', 'task_progress'])
                        ->where("slug", $slug)
                        ->first();
        return response([
            'data' => $project
        ], 200);
    }

    public function index(Request $request) {
        $query = $request->get('query');
        $userId = $request->get('userId');
        $project = Project::with(['task_progress'])->where('userId', $userId);

        if (!is_null($query) && $query !== '') {
            $project->where('name', 'like', '%' . $query . '%')->orderBy('id', 'desc');
            return response([
                'data' => $project->paginate(10)
            ], 200);
        }
        return response([
            'data' => $project->paginate(10)
        ], 200);
    }

    public function store(StoreRequest $request) {
        $fields = $request->validated();

        $project = Project::create([
            'name' => $fields['name'],
            'status' => Project::NOT_STARTED,
            'startDate' => $fields['startDate'],
            'endDate' => $fields['endDate'],
            'slug' => Project::createSlug($fields['name']),
            'userId' => $fields['userId']
        ]);

        $count = Project::count();
        ProjectCreated::dispatch($count);

        return response([
            'project' => $project,
            'message' => 'project created'
            ], 200);
    }

    public function update(UpdateRequest $request) {
        $fields = $request->validated();

        $project = Project::where('id', $fields['id'],)->update([
            'name' => $fields['name'],
            'status' => Project::NOT_STARTED,
            'startDate' => $fields['startDate'],
            'endDate' => $fields['endDate'],
            'slug' => Project::createSlug($fields['name'])
        ]);

        return response([
            'project' => $project,
            'message' => 'project updated'
            ], 200);
    }

    public function countProject() {
        $count = Project::count();
        return response(['count' => $count]);
    }
}
