<?php

namespace App\Http\Controllers;

use App\Http\Requests\CardList\DeleteRequest;
use App\Http\Requests\CardList\ReorderRequest;
use App\Http\Requests\CardList\StoreRequest;
use App\Http\Requests\CardList\UpdateRequest;
use App\Services\CardListService;

class CardListController extends Controller
{
    public function __construct(
        protected CardListService $cardListService
    ) {
        
    }

    public function index($projectId)
    {
        $result = $this->cardListService->index($projectId);
        return response([
            'message' => 'Ok',
            'data' => $result
        ], 200);
    }

    public function store(StoreRequest $request)
    {
        $fields = $request->validated();
        $result = $this->cardListService->store($fields['name'], $fields['projectId']);
        
        if ($result)
        {
            return response([
                'message' => 'list created'
            ], 200);
        }

        return response([
            'message' => 'failed'
        ], 400);
    }

    public function update(UpdateRequest $request)
    {
        $fields = $request->validated();

        $result = $this->cardListService->update(
            $fields['listId'],
            $fields['name'],
        );

        if ($result) {
            return response([
                'message' => 'list updated'
            ], 200);
        }

        return response([
            'message' => 'failed'
        ], 400);
    }

    public function delete(DeleteRequest $request)
    {
        $fields = $request->validated();
        $result = $this->cardListService->delete($fields['listId']);

        if ($result)
        {
            return response([
                'message' => 'list deleted'
            ], 200);
        }

        return response([
            'message' => 'failed'
        ], 400);
    }

    public function reorder(ReorderRequest $request)
    {
        $fields = $request->validated();

        $result = $this->cardListService->reorder(
            $fields['listId'],
            $fields['order'],
        );

        if ($result) {
            return response([
                'message' => 'success'
            ], 200);
        }

        return response([
            'message' => 'failed'
        ], 400);
    }
}
