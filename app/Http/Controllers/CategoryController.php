<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $category = Category::query()->get();
        $data["category"] = $category;
        return response()->json($data, Response::HTTP_OK);
    }

    public function show($id)
    {     Product::query()->where('expire_date','<',(now()->format('Y-m-d')))->delete();
        $category = Category::find($id);
        $products = $category->products() ->select(['name', 'url_img', 'views'])->get();;
        $data["products"] = $products;
        return response()->json($data, Response::HTTP_OK);
    }
}
