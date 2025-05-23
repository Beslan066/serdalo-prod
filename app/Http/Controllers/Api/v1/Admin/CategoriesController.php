<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Category;

class CategoriesController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('created_at', 'desc')->paginate(10);




        return response()->json($categories);
    }
    public function create()
    {

    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'max:255'],
            'tag_translate' => ['nullable'],
        ]);



        if($validator->fails()) {
            return response()->json([
                'errors' => $validator->messages()
            ], 422);
        }

        $category = new Category();

        $category->title = $request->title;
        $category->tag_translate = $request->tag_translate;

        $category->save();

        return response()->json([
            'success' => 'Category created.',
            'category' => $category
        ]);
    }
    public function show($id)
    {

    }
    public function edit($id)
    {
        $category = Category::find($id);

        if(!$category) {
            return response()->json([
                'message' => '404 not found'
            ], 404);
        }

        return response()->json([
            'category' => $category
        ]);
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'max:255'],
            'tag_translate' => ['nullable'],
        ]);

        if($validator->fails()) {
            return response()->json([
                'errors' => $validator->messages()
            ], 422);
        }

        $category = Category::find($id);

        if(!$category) {
            return response()->json([
                'message' => '404 not found'
            ], 404);
        }

        $category->title = $request->title;
        $category->tag_translate = $request->tag_translate;


        $category->save();

        return response()->json([
            'success' => 'Category updated.',
            'category' => $category
        ]);
    }
    public function destroy($id)
    {
        $category = Category::find($id);

        if(!$category) {
            return response()->json([
                'message' => '404 not found'
            ], 404);
        }

        $category->delete();

        return response()->json([
            'success' => 'Category deleted.',
        ]);
    }
}
