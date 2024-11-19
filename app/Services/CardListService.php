<?php

namespace App\Services;

use App\Models\CardList;
use Illuminate\Support\Facades\DB;

class CardListService
{
    public function index($projectId)
    {
        return CardList::with(['cards'])->where('projectId', $projectId)->get();
    }

    public function store($name, $projectId)
    {
        $listCount = CardList::where('projectId', $projectId)->count();
        return CardList::create([
            'name' => $name,
            'projectId' => $projectId,
            'order' => $listCount
        ]);
    }

    public function update($listId, $name)
    {        
        $result = CardList::where('id', $listId)
            ->update([
                'name' => $name,
            ]);

        return $result;
    }

    public function reorder($listId, $order) {
        try {
            $targetCardList = CardList::where('id', $listId)->first();
            $oldOrder = $targetCardList->order;
    
            $targetCardList->order = $order;
            $targetCardList->save();
                
            if ($order != $oldOrder) {
                $cardLists = CardList::where([
                    ['id', '<>', $listId],
                ]);
                
                if ($order > $oldOrder) {
                    $this->reorderOthers($cardLists, $oldOrder, $order, -1);
                } else {
                    $this->reorderOthers($cardLists, $order, $oldOrder, 1);
                }
            }

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    private function reorderOthers($cardLists, $rangeA, $rangeB, $change) {
        $cardLists = $cardLists->whereBetween('order', [$rangeA, $rangeB])->get();

        foreach ($cardLists as $cardList) {
            $cardList->order += $change;
            $cardList->save();
        }
    }

    public function delete($listId)
    {
        return CardList::where('id', $listId)->delete();
    }
}