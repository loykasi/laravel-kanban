<?php

namespace App\Services;

use App\Models\Card;
use Illuminate\Support\Facades\DB;

class CardService
{
    public function index($listId)
    {
        return Card::where('listId', $listId)->get();
    }

    public function store($name, $listId)
    {
        $cardCount = Card::where('listId', $listId)->count();
        return Card::create([
            'name' => $name,
            'listId' => $listId,
            'order' => $cardCount
        ]);
    }

    public function update($cardId, $name, $listId, $order)
    {
        return Card::where('_id', $cardId)
        ->update([
            'name' => $name,
            'listId' => $listId,
            'order' => $order
        ]);
    }

    public function delete($cardId)
    {
        return Card::where('_id', $cardId)->delete();
    }
}