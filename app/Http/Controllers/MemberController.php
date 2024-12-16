<?php

namespace App\Http\Controllers;

use App\Http\Requests\Member\StoreRequest;
use App\Http\Requests\Member\UpdateRequest;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Member;

class MemberController extends Controller
{
    public function getProjectMember(string $id, Request $request) {
        $project = Project::find($id);

        if ($project === null) {
            return response([
                'message' => 'Invalid Id'
            ], 400);
        }

        $owner = User::find($project->userId);

        $members = $project->users;
        return response([
            "owner" => $owner,
            "data" => $members
        ], 200);
    }

    public function index(string $search, Request $request) {
        $users = User::where('username', 'like', "%$search%")->get();
        
        return response([
            "data" => $users
        ], 200);
    }

    public function store(Request $request) {

        $projectId = $request->get("projectId");
        $email = $request->get("email");

        $project = Project::find($projectId);
        $member = User::where("email", $email)->first();

        if ($member === null) {
            return response([
                'message' => 'Invalid email'
            ], 400);
        }

        $project->users()->attach($member);

        // $member = Member::create([
        //     "projectId" => $projectId,
        //     "memberId" => $memberId
        // ]);
        // $project = Project::where("id", $projectId)->update(
        //     ['$push' => [
        //         "member" => [
        //             '$each' => [$memberId],
        //             '$position' => 0,
        //         ]
        //     ]],
        //     ['upsert' => true]
        // );

        return response([
            'member' => $member,
            'message' => 'Member created'
        ], 200);
    }

    public function delete(Request $request) {
        $projectId = $request->get("projectId");
        $memberId = $request->get("memberId");

        $project = Project::find($projectId);
        $member = User::find($memberId);
        $project->users()->detach($member);

        // Project::where('_id', $projectId)->update([
        //     '$pull' => ['user_ids' => $memberId]
        // ]);

        // User::where('_id', $memberId)->update([
        //     '$pull' => ['project_ids' => $projectId]
        // ]);

        return response([
            'message' => 'Member deleted'
            ], 200);
    }
}
