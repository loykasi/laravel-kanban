<?php

namespace App\Http\Controllers;

use App\Events\CardCommentCreated;
use App\Events\CardCommentDeleted;
use App\Models\Card;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request, string $cardId) {
        $comment = Comment::where("card_id", $cardId)->get();

        if (!$comment) {
            return response()->json(['message' => 'Not found'], 404);
        }
        
        return response()->json([
            'comments' => $comment,
        ]);
    }

    public function store(Request $request, string $cardId) {
        $request->validate([
            'userId' => 'required|string|exists:users,id',
            'content' => 'required|string',
        ]);
    
        $card = Card::find($cardId);
    
        if (!$card) {
            return response()->json(['message' => 'Card not found'], 404);
        }

        $comment = Comment::create([
            'content' => $request->input("content"),
            'card_id' => $cardId,
            'user_id' => $request->input("userId"),
            'created_at' => now(),
        ]);
    
        if (!$comment) {
            return response()->json(['message' => 'Failed to add comment'], 500);
        }

        broadcast(new CardCommentCreated($cardId, $comment))->toOthers();

        return response()->json([
            'comment' => $comment,
            'message' => 'Comment added successfully'
        ]);
    }

    public function delete(Request $request, string $commentId) {
        $comment = Comment::find($commentId);

        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }
        
        $cardId = $comment->card_id;
        broadcast(new CardCommentDeleted($cardId, $commentId))->toOthers();
        
        $comment->delete();

        return response()->json([
            'message' => 'Comment deleted successfully'
        ]);
    }
}
