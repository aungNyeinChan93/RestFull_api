<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Storage;
use Symfony\Component\HttpFoundation\HeaderBag;

class ProductController extends Controller
{
    //index
    public function index()
    {
        $products = Product::query()
            ->with('category')
            ->filter(request(['price_min', 'price_max', 'quantity_min', 'quantity_max', 'category_id']))
            ->when(request('name'), function ($query) {
                return $query->where('name', 'like', '%' . request('name') . '%');
            })
            ->when(request('id'), function ($query) {
                return $query->where('id', request('id'));
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return response()->json([
            'message' => 'success',
            'products' => $products,
        ], 200);
    }

    // show
    public function show(Product $product)
    {
        $product->load('category');
        return response()->json([
            'message' => 'success',
            'product' => $product,
        ], 200);
    }

    // store
    public function store(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string|min:4',
            'description' => 'nullable|string|max:500',
            'price' => 'required|string',
            'quantity' => 'required|string',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:5000',
            'category_id' => 'required'
        ]);

        if ($request->hasFile('image')) {
            $fields['image'] = $request->file('image')->store('/products/', 'public');
        }

        $product = Product::create($fields);

        return response()->json([
            'message' => 'success',
            'product' => $product
        ], 200);
    }

    // update
    public function update(Request $request, Product $product)
    {
        $fields = $request->validate([
            'name' => 'required|string|min:4',
            'description' => 'nullable|string|max:500',
            'price' => 'required|string',
            'quantity' => 'required|string',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:5000',
            'category_id' => 'required'
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $fields['image'] = $request->file('image')->store('/products/', 'public');
        }

        $product->update($fields);

        return response()->json([
            'message' => 'success',
            'product' => $product
        ], 200);
    }

    // destroy
    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return response()->json([
            'message' => 'success',
            'productName' => $product->name,
        ], 200);
    }

}
