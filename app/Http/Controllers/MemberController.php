<?php

namespace App\Http\Controllers;

use App\Http\Requests\Member\StoreRequest;
use App\Http\Requests\Member\UpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Member;

class MemberController extends Controller
{
    public function index(Request $request) {
        try {
            $query = $request->get('query');
            // $members = Member::with(['task_progress']);
            $members = DB::table('members');
    
            if (!is_null($query) && $query !== '') {
                $members->where('name', 'like', '%' . $query . '%')->orderBy('id', 'desc');
                return response([
                    'data' => $members->paginate(3)
                ], 200);
            }

            $paginated = $members->paginate(3);
            $output = new \Symfony\Component\Console\Output\ConsoleOutput();
            $output->writeln("<info>get page</info>");
            return response([
                'data' => $paginated
            ], 200);
        } catch (\Throwable $th) {
            $output = new \Symfony\Component\Console\Output\ConsoleOutput();
            $output->writeln("<info>" . $th->getMessage() ."</info>");
            return response([
                'message' => $th->getMessage()
            ], 422);
        }
    }

    public function store(StoreRequest $request) {
        $fields = $request->validated();

        $member = Member::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
        ]);

        return response([
            'member' => $member,
            'message' => 'member created'
            ], 200);
    }

    public function update(UpdateRequest $request) {
        $fields = $request->validated();

        $member = Member::where('id', $fields['id'],)->update([
            'name' => $fields['name'],
            'email' => $fields['email'],
        ]);

        return response([
            'member' => $member,
            'message' => 'member updated'
            ], 200);
    }
}
