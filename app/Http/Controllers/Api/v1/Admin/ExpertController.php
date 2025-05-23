<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Validator;

use App\Models\Author;
use App\Models\Category;
use App\Models\MediaFile;
use Illuminate\Support\Str;

class ExpertController extends Controller
{
    public function index()
    {
        $authors = Expert::paginate(10);

        return response()->json($authors);
    }
    public function create()
    {
        /*if(!Auth::user()->hasRole('author')) {
            return response()->json([
                'error' => 'У вас нет прав для создания новостей'
            ], 403);
        }*/
        return response()->json([
            'categories' => Category::get(['id', 'title'])
        ], 200);
    }
    public function store(Request $request)
    {
        /*if(!Auth::user()->hasRole('author')) {
            return response()->json([
                'error' => 'У вас нет прав для создания новостей'
            ], 403);
        }*/
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'max:255'],
            'description' => ['required'],
            'file_id' => ['exists:files,id'],
        ]);

        if($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $author = new Expert();


        $author->user_id = Auth::user()->id;
        $author->title = $request->title;
        $author->file_id = $request->file_id;
        $author->description = $request->description;

        $author->save();

        return response()->json([
            'success' => 'Author created.',
            'author' => $author
        ]);
    }
    public function show($id)
    {

    }
    public function edit($id)
    {
        $expert = Expert::find($id);
        $expert_file = $expert->file;

        return response()->json([
            'expert' => $expert,
            'files' => ($expert_file) ? [$expert_file] : [],
        ]);
    }
    public function update(Request $request, $id)
    {
        /*if(!Auth::user()->hasRole('author')) {
            return response()->json([
                'error' => 'У вас нет прав для создания новостей'
            ], 403);
        }*/
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'max:255'],

            'description' => ['required'],
            'file_id' => ['nullable', 'exists:files,id'],
        ]);

        if($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $expert = Expert::find($id);


        //$author->user_id = Auth::user()->id;
        $expert->title = $request->title;
        $expert->file_id = $request->file_id;
        $expert->description = $request->description;

        $expert->save();

        return response()->json([
            'success' => 'Expert was created.',
            'author' => $expert
        ]);
    }
    public function destroy($id)
    {
        $expert = Expert::find($id);

        if(!$expert) {
            return response()->json([
                'message' => '404 not found'
            ], 404);
        }

        $expert->delete();

        return response()->json([
            'success' => 'Expert was deleted.'
        ]);
    }
}
