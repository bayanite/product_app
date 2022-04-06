<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class CommentController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */


    public function index($productId)
    {
        $comment = Comment::with('user')->where('product_id', $productId)->get();
        $data["comment"] = $comment;
        return response()->json($data, 200);
    }

    public function store(Request $request, $productId)
    {
        $validator = Validator::make($request->all(), [
            'comment' => ['required', 'string', 'min:1', 'max:500'],
        ]);
        if ($validator->fails()) {
            return $validator->errors()->all();
        }
        $comment = Comment::query()->create([
            'comment' => $request->comment,
            'user_id' => auth()->id(),
            'product_id' => $productId
        ]);
        $comment->save();
        return response()->json($comment, 201);
    }


    public function update(Request $request, $id)
    {
        $comment = Comment::find($id);
        if (auth()->id() == $comment->user_id) {
            $validator = Validator::make($request->all(), [
                'comment' => ['required', 'string', 'min:1', 'max:500'],
            ]);
            if ($validator->fails()) {
                return $validator->errors()->all();
            }
            $comment->update([
                'comment' => $request->comment,
                'user_id' => auth()->id(),
            ]);
            $comment->save();
        }
        return response()->json($comment, 201);
    }

    public function destroy($idComment)
    {
        $comment = Comment::find($idComment);
        if (auth()->id() == $comment->user_id) {

            $comment->delete();
            return response()->json(null, 204);
        } else {
            return response()->json(['error'], 200);
        }

    }
}




