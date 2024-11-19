<?php

namespace App\Http\Controllers;

use App\Events\ProjectCreated;
use App\Http\Requests\Project\DeleteRequest;
use App\Http\Requests\Project\GetUserProjectRequest;
use App\Http\Requests\Project\IndexRequest;
use App\Http\Requests\Project\PinProjectRequest;
use App\Http\Requests\Project\StoreRequest;
use App\Http\Requests\Project\UpdateRequest;
use App\Services\ProjectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskProgress;

class ProjectController extends Controller
{
    public function __construct(private ProjectService $projectService) {}

    // public function index(Request $request) {
    //     $query = $request->get('query');
    //     $userId = $request->get('userId');
    //     $project = Project::with(['task_progress'])->where('userId', $userId);

    //     if (!is_null($query) && $query !== '') {
    //         $project->where('name', 'like', '%' . $query . '%')->orderBy('id', 'desc');
    //         return response([
    //             'data' => $project->paginate(10)
    //         ], 200);
    //     }
    //     return response([
    //         'data' => $project->paginate(10)
    //     ], 200);
    // }

    public function index() {
        $projects = $this->projectService->index();

        if ($projects) {
            return response([
                'data' => $projects
            ], 200);
        }

        return response([
            'message' => 'not found'
        ], 404);
    }

    public function getUserProject($userId) {
        $projects = $this->projectService->getUserProject($userId);

        if ($projects) {
            return response([
                'data' => $projects
            ], 200);
        }

        return response([
            'message' => 'not found'
        ], 404);
    }

    public function store(StoreRequest $request) {
        $fields = $request->validated();

        $project = $this->projectService->store($fields['name'], $fields['userId']);

        $count = Project::count();
        ProjectCreated::dispatch($count);

        return response([
            'project' => $project,
            'message' => 'project created'
            ], 200);
    }

    public function update(UpdateRequest $request) {
        $fields = $request->validated();

        $result = $this->projectService->update($fields['id'], $fields['name']);

        if ($result) {
            return response([
                'message' => 'project updated'
                ], 200);
        }

        return response([
            'message' => 'not found'
        ], 404);
    }

    public function delete(DeleteRequest $request) {
        $fields = $request->validated();

        $result = $this->projectService->delete($fields['id']);

        if ($result) {
            return response([
                'message' => 'project updated'
                ], 200);
        }

        return response([
            'message' => 'not found'
        ], 404);
    }

    public function countProject() {
        $count = Project::count();
        return response(['count' => $count]);
    }

    public function getProjectDetail($projectId) {
        $project = $this->projectService->getProjectDetail($projectId);
        
        return response([
            'data' => $project
        ], 200);
    }
}
