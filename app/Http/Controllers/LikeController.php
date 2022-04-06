<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($productId)
    {
        $like = Like::with('user')->where('product_id', $productId)->get();
        $data["like"] = $like;
        return response()->json($data, 200);
    }

    public function store($productId)
    {
        $like = Like::query()->where('user_id', Auth::id())
            ->where('product_id', $productId)->exists();

        if ($like) {
            Like::query()->where('user_id', Auth::id())->where('product_id', $productId)->delete();
            return response()->json('The delete is removed');

        } else {
            Like::query()->create([
                'is_like' => true,
                'product_id' => $productId,
                'user_id' => Auth::id(),

            ]);
        }
        return response()->json('The like add success',200);
    }

}
