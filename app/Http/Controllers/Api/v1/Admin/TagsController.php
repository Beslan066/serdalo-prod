<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Tag;

class TagsController extends Controller
{
    public function index()
    {
        $tags = Tag::orderBy('id', 'desc')->paginate(5);

        return response()->json($tags);
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

        $tag = new Tag();

        $tag->title = $request->title;
        $tag->tag_translate = $request->tag_translate;


        $tag->save();

        return response()->json([
            'success' => 'Tag created.',
            'tag' => $tag
        ]);
    }
    public function show($id)
    {

    }
    public function edit($id)
    {
        $tag = Tag::find($id);

        if(!$tag) {
            return response()->json([
                'message' => '404 not found'
            ], 404);
        }

        return response()->json([
            'tag' => $tag
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

        $tag = Tag::find($id);

        if(!$tag) {
            return response()->json([
                'message' => '404 not found'
            ], 404);
        }

        $tag->title = $request->title;
        $tag->tag_translate = $request->tag_translate;

        $tag->save();

        return response()->json([
            'success' => 'Tag updated.',
            'tag' => $tag
        ]);
    }
    public function destroy($id)
    {
        $tag = Tag::find($id);

        if(!$tag) {
            return response()->json([
                'message' => '404 not found'
            ], 404);
        }

        $tag->delete();

        return response()->json([
            'success' => 'Tag deleted.',
        ]);
    }
}
