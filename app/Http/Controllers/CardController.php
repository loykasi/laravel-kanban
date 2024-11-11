<?php

namespace App\Http\Controllers;

use App\Http\Requests\Card\StoreRequest;
use App\Http\Requests\Card\UpdateRequest;
use App\Http\Requests\Card\DeleteRequest;
use App\Services\CardService;

class CardController extends Controller
{
    public function __construct(
        protected CardService $cardService
    ) {
        
    }

    public function index($listId)
    {
        $result = $this->cardService->index($listId);
        return response([
            'message' => 'Ok',
            'data' => $result
        ], 200);
    }

    public function store(StoreRequest $request)
    {
        $fields = $request->validated();
        $result = $this->cardService->store($fields['name'], $fields['listId']);
        
        if ($result)
        {
            return response([
                'message' => 'card created'
            ], 200);
        }

        return response([
            'message' => 'failed'
        ], 400);
    }

    public function update(UpdateRequest $request)
    {
        $fields = $request->validated();
        $result = $this->cardService->update(
            $fields['cardId'],
            $fields['name'],
            $fields['listId'],
            $fields['order']
        );

        if ($result)
        {
            return response([
                'message' => 'card updated'
            ], 200);
        }

        return response([
            'message' => 'failed'
        ], 400);
    }

    public function delete(DeleteRequest $request)
    {
        $fields = $request->validated();
        $result = $this->cardService->delete($fields['listId']);

        if ($result)
        {
            return response([
                'message' => 'card deleted'
            ], 200);
        }

        return response([
            'message' => 'failed'
        ], 400);
    }
}
