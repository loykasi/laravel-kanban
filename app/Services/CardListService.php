<?php

namespace App\Services;

use App\Models\CardList;
use Illuminate\Support\Facades\DB;

class CardListService
{
    public function store($name, $projectId)
    {
        $listCount = CardList::where('projectId', $projectId)->count();
        return CardList::create([
            'name' => $name,
            'projectId' => $projectId,
            'order' => $listCount
        ]);
    }

    public function update($listId, $name, $projectId, $order)
    {
        return CardList::where('_id', $listId)
        ->update([
            'name' => $name,
            'projectId' => $projectId,
            'order' => $order
        ]);
    }

    public function delete($listId)
    {
        return CardList::where('_id', $listId)->delete();
    }
}