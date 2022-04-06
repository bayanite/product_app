<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\User;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use function PHPUnit\Framework\isEmpty;


class ProductController extends Controller
{

    public function index(Request $request, Product $product)
    {
        if ($request->has('name')) {

            $data['product'] = $product->where('name', $request->input('name'))
                ->select(['name', 'url_img', 'views'])->get();

            return response()->json($data, Response::HTTP_OK);

        }
        //---------------------------------------------------

        if ($request->has('category_id')) {
            $data['productCanHasCategory'] = $product->where('category_id', $request->input('category_id'))
                ->select(['name', 'url_img', 'views'])->get();

            return response()->json($data, Response::HTTP_OK);
        }

        //-------------------------------------------------------


        if ($request->has('from') && $request->has('to')) {
            return $product->whereBetween('expire_date', array($request->input('from'), $request->input('to')))
                ->select(['name', 'url_img', 'views'])->get();
        }
        if ($request->has('from')) {
            return $product->where('expire_date', '>=', $request->input('from'))
                ->select(['name', 'url_img', 'views'])->get();
        }
        //-------------------------------------------

        Product::query()->where('expire_date', '<', (now()->format('Y-m-d')))->delete();
        $product = Product::query()->select(['name', 'url_img', 'views'])->get();
        $data['product'] = $product;
        return response()->json($data, Response::HTTP_OK);

    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'min:2'],
            'price' => ['required', 'numeric', 'min:1'],
            'description' => ['required', 'string'],
            'url_img' => ['required'],
            'expire_date' => ['required'],
            'quantity' => ['required', 'numeric', 'min:1'],
            'category_id' => ['required'],

        ]);

        if ($validator->fails()) {
            return $validator->errors()->all();
        }

        $product = Product::query()->create([

            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'url_img' => $request->file('url_img')->store('image'),
            'expire_date' => Carbon::parse($request->expire_date),
            'quantity' => $request->quantity,
            'user_id' => auth()->id(),
            'category_id' => $request->category_id,
        ]);

        foreach ($request->list_discount as $discount) {

            $product->discounts()->create([

                'date' => $discount['date'],
                'discount_percentage' => $discount['discount_percentage'],

            ]);
        }
        return response()->json($product);
    }

    public function show(Product $product)
    {

        $product->increment('views');

        $discounts = $product->discounts()->get();
        $maxDiscount = null;

        foreach ($discounts as $discount) {
            if (Carbon::parse($discount['date']) <= now()) {
                $maxDiscount = $discount;
            }
        }
        if (!is_null($maxDiscount)) {
            $value = ($product->price * $maxDiscount['discount_percentage']) / 100;

            $product['price_after_discount'] = $product->price - $value;
        }

        $product['category'] = $product->category()->get();
        $data['product'] = $product;

        return response()->json($data, Response::HTTP_OK);
    }

    public function update($id, Request $request)
    {
        $product = Product::find($id);

        if (auth()->id() == $product->user_id) {

            $validator = Validator::make($request->all(), [

                'name' => ['required', 'string', 'min:2'],
                'price' => ['required', 'numeric', 'min:1'],
                'description' => ['required', 'string'],
                'url_img' => ['required'],
                'quantity' => ['required', 'numeric', 'min:1'],
                'category_id' => ['required'],

            ]);
            if ($validator->fails()) {
                return $validator->errors()->all();
            }
            $product->update([
                'name' => $request->name,
                'price' => $request->price,
                'description' => $request->description,
                'url_img' => $request->file('url_img')->store('image'),
                'quantity' => $request->quantity,
                'user_id' => auth()->id(),
                'category_id' => $request->category_id,
            ]);

            return response()->json($product, Response::HTTP_OK);
        } else {
            return response()->json(['not update'], Response::HTTP_OK);
        }
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        if (auth()->id() == $product->user_id) {

            $product->delete();
            return response()->json(null, 204);
        } else {
            return response()->json(['error'], 200);
        }
    }

    function sort(Request $request)
    {

        if ($request->has('name') && $request->has('asc')) {
            $protect = Product::all()->sortBy('name')->values();

            return response()->json($protect, 200);
        }
        if ($request->has('name') && $request->has('desc')) {
            $protect = Product::all()->sortByDesc('name')->values();

            return response()->json($protect, 200);
        }
        if ($request->has('date') && $request->has('asc')) {
            $protect = Product::all()->sortBy('expire_date')->values();

            return response()->json($protect, 200);
        }
        if ($request->has('date') && $request->has('desc')) {
            $protect = Product::all()->sortByDesc('expire_date')->values();

            return response()->json($protect, 200);
        }
        if ($request->has('views') && $request->has('asc')) {
            $protect = Product::all()->sortBy('views')->values();

            return response()->json($protect, 200);
        }
        if ($request->has('views') && $request->has('desc')) {
            $protect = Product::all()->sortByDesc('views')->values();

            return response()->json($protect, 200);
        }
        if ($request->has('like') && $request->has('asc')) {
            $protect = Product::all()->sortBy('likes_count')->values();

            return response()->json($protect, 200);
        }
        if ($request->has('like') && $request->has('desc')) {
            $protect = Product::all()->sortByDesc('likes_count')->values();

            return response()->json($protect, 200);
        }
    }
}
