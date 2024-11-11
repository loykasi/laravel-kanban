<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Task;
use App\Models\TaskMember;

class TaskService
{
    public function createTask($projectId, $name, $memberIds)
    {
        return DB::transaction(function () use ($projectId, $name, $memberIds) {

            $task = Task::create([
                'projectId' => $projectId,
                'name' => $name,
                'status' => Task::NOT_STARTED
            ]);

            $members = $memberIds;
            for ($i = 0; $i < count($members); $i++) { 
                $taskMember = TaskMember::create([
                    'projectId' => $projectId,
                    'taskId' => $task->id,
                    'memberId' => $members[$i]
                ]);
            }

            return response([
                'message' => 'Task created'
            ], 200);
        });
    }

    public function setTaskStatus($taskId, $status, $projectId)
    {
        error_log($taskId . ' | ' . $status . ' | ' . $projectId);
        Task::changeStatus($taskId, $status);
        Task::handleProjectProgress($projectId);
    }
}