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

    public function update($cardId, $name, $listId)
    {
        $result = Card::where('id', $cardId)
            ->update([
                'name' => $name,
                'listId' => $listId,
            ]);

        return $result;
    }
    
    public function reorder($cardId, $order) {
        try {
            $targetCard = Card::where('id', $cardId)->first();
            $oldOrder = $targetCard->order;
    
            $targetCard->order = $order;
            $targetCard->save();
                
            if ($order != $oldOrder) {
                $cards = Card::where([
                    ['id', '<>', $cardId],
                ]);
                
                if ($order > $oldOrder) {
                    $this->reorderOthers($cards, $oldOrder, $order, -1);
                } else {
                    $this->reorderOthers($cards, $order, $oldOrder, 1);
                }
            }

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    private function reorderOthers($cards, $rangeA, $rangeB, $change) {
        $cards = $cards->whereBetween('order', [$rangeA, $rangeB])->get();

        foreach ($cards as $card) {
            $card->order += $change;
            $card->save();
        }
    }

    public function delete($cardId)
    {
        return Card::where('id', $cardId)->delete();
    }
}