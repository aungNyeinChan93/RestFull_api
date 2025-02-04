<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CategoryController extends Controller implements HasMiddleware
{

    public static function middleware(){
        return [
            new Middleware(['admin'],except:['index'])
        ];
    }

    //index
    public function index()
    {
        return response()->json([
            'message' => 'success',
            'categories' => Category::query()->get()
        ], 200);
    }

    // store
    public function store(Request $request)
    {
        $fileds = $request->validate([
            'title' => 'required|string|min:4',
            'description' => 'nullable|string'
        ]);

        $category = Category::create($fileds);

        return response()->json([
            'message' => 'success',
            'category' => $category,
        ], 200);
    }

    // show
    public function show(Category $category)
    {
        return response()->json([
            'message' => 'success',
            'category' => $category
        ], 200);
    }

    // update
    public function update(Category $category, Request $request)
    {
        $fields = $request->validate([
            'title' => 'required|string|min:4',
            'description' => "nullable|string",
        ]);

        $category->update($fields);

        return response()->json([
            'message' => 'success',
            'category' => $category,
        ], 200);
    }

    // destroy
    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json([
            'message' => 'success',
            'category_id' => $category->id
        ], 200);
    }
}
