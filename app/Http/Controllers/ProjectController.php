<?php

namespace App\Http\Controllers;

use App\Events\ProjectCreated;
use App\Http\Requests\Project\PinProjectRequest;
use App\Http\Requests\Project\StoreRequest;
use App\Http\Requests\Project\UpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
        $project = Project::with(['task_progress']);

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
        return DB::transaction(function() use($request) {
            $fields = $request->validated();

            $project = Project::create([
                'name' => $fields['name'],
                'status' => Project::NOT_STARTED,
                'startDate' => $fields['startDate'],
                'endDate' => $fields['endDate'],
                'slug' => Project::createSlug($fields['name'])
            ]);

            TaskProgress::create([
                'projectId' => $project->id,
                'pinned_on_dashboard' => TaskProgress::NOT_PINNED_ON_DASHBOARD,
                'progress' => TaskProgress::INITIAL_PROJECT_PERCENT
            ]);

            $count = Project::count();
            ProjectCreated::dispatch($count);

            return response([
                'project' => $project,
                'message' => 'project created'
                ], 200);
        });
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

    public function pinProject(PinProjectRequest $request) {
        return DB::transaction(function() use ($request) {
            $fields = $request->validated();
    
            TaskProgress::where('pinned_on_dashboard', TaskProgress::PINNED_ON_DASHBOARD)
            ->update(['pinned_on_dashboard' => TaskProgress::NOT_PINNED_ON_DASHBOARD]);
    
            TaskProgress::where('projectId', $fields['projectId'])
                ->update(['pinned_on_dashboard' => TaskProgress::PINNED_ON_DASHBOARD]);
            return response([
                'message' => 'project pinned on dashboard'
            ]);
        });
    }

    public function countProject() {
        $count = Project::count();
        return response(['count' => $count]);
    }

    public function getPinnedProject() {
        $project = DB::table('task_progress')
        ->join('projects', 'task_progress.projectId', '=', 'projects.id')
        ->select('projects.id', 'projects.name')
        ->where('task_progress.pinned_on_dashboard', TaskProgress::PINNED_ON_DASHBOARD)
        ->first();

        if (!is_null($project)) {
            return response([
                'data' => $project
            ]);
        }
        return response([
            'data' => null
        ]);
    }

    public function getProjectChartData($projectId) {
        $task = Task::where('projectId', $projectId)->get();
        $count = $task->count();

        $taskProgrss = TaskProgress::where('projectId', $projectId)
                                    ->select('progress')
                                    ->first();

        $pending = 0;
        $completed = 0;
        foreach ($task as $row) {
            if (intval($row->status) === Task::PENDING) {
                $pending++;
            }

            if (intval($row->status) === Task::COMPLETE) {
                $completed++;
            }
        }
        
        return response([
            'tasks' => [$pending / $count, $completed / $count],
            'progress' => intval($taskProgrss->progress)
        ]);
    }
}
