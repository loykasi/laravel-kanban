<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskMember;
use App\Models\Member;

class TaskController extends Controller
{
    public function createTask(Request $request) {
        return DB::transaction(function () use ($request) {
            $fields = $request->all();
        
            $errors = Validator::make($fields, [
                'name'=>'required',
                'projectId'=>'required|numeric',
                'memberIds'=>'array',
                'memberIds.*' => 'numeric'
            ]);

            if ($errors->fails()) {
                return response(['message' => $errors->errors()->all()], 422);
            }

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

    public function taskNotStartedToPending(Request $request) {
        Task::changeStatus($request->taskId, Task::PENDING);
        
        return response(['message' => 'Task move to pending'], 200);
    }

    public function taskNotStartedToCompleted(Request $request) {
        Task::changeStatus($request->taskId, status: Task::COMPLETE);

        return response(['message' => 'Task move to completed'], 200);
    }

    public function taskPendingToCompleted(Request $request) {
        Task::changeStatus($request->taskId, status: Task::COMPLETE);
        
        return response(['message' => 'Task move to completed'], 200);
    }

    public function taskPendingToNotStarted(Request $request) {
        Task::changeStatus($request->taskId, status: Task::NOT_STARTED);
        
        return response(['message' => 'Task move to not started'], 200);
    }

    public function taskCompletedToNotStarted(Request $request) {
        Task::changeStatus($request->taskId, status: Task::NOT_STARTED);
        
        return response(['message' => 'Task move to not started'], 200);
    }

    public function taskCompletedToPending(Request $request) {
        Task::changeStatus($request->taskId, Task::PENDING);
        
        return response(['message' => 'Task move to pending'], 200);
    }

    public function setTaskStatus(Request $request) {
        Task::changeStatus($request->taskId, $request->status);
        Task::handleProjectProgress($request->projectId);
        
        return response(['message' => 'Set status successful'], 200);
    }
}
