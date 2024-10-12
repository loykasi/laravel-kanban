<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\CreateRequest;
use App\Http\Requests\Task\SetStatusRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskMember;
use App\Models\Member;

class TaskController extends Controller
{
    public function createTask(CreateRequest $request) {
        return DB::transaction(function () use ($request) {
            $fields = $request->validated();

            $task = Task::create([
                'projectId' => $fields['projectId'],
                'name' => $fields['name'],
                'status' => Task::NOT_STARTED
            ]);

            $members = $fields['memberIds'];
            for ($i = 0; $i < count($members); $i++) { 
                $taskMember = TaskMember::create([
                    'projectId' => $fields['projectId'],
                    'taskId' => $task->id,
                    'memberId' => $members[$i]
                ]);
            }

            return response([
                'message' => 'Task created'
            ], 200);
        });
    }

    public function setTaskStatus(SetStatusRequest $request) {
        $fields = $request->validated();

        Task::changeStatus($fields['taskId'], $fields['status']);
        Task::handleProjectProgress($fields['projectId']);
        
        return response(['message' => 'Set status successful'], 200);
    }
}
