<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Validator;

use App\Models\Author;
use App\Models\Category;
use App\Models\MediaFile;
use Illuminate\Support\Str;

class AuthorsController extends Controller
{
    public function index()
    {
        $authors = Author::with('translation')->paginate(10);

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
            'slug' => [
                'nullable',
                'regex:~^[a-z0-9]+(?:(-|_)[a-z0-9]+)*$~', 'max:255',
                'unique:materials,slug',
            ],
            'description' => ['required'],
            'file_id' => ['exists:files,id'],
        ]);

        if($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $author = new Author();

        if($request->slug) {
            $author->slug = $request->slug;
        } else {
            $author->slug = Str::slug($request->title);
        }

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
        $author = Author::find($id);
        $author_file = $author->file;

        return response()->json([
            'author' => $author,
            'files' => ($author_file) ? [$author_file] : [],
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
            'slug' => [
                'nullable',
                'regex:~^[a-z0-9]+(?:(-|_)[a-z0-9]+)*$~', 'max:255',
                'unique:materials,slug',
            ],
            'description' => ['required'],
            'file_id' => ['nullable', 'exists:files,id'],
        ]);

        if($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $author = Author::find($id);

        if($request->slug) {
            $author->slug = $request->slug;
        } else {
            $author->slug = Str::slug($request->title);
        }

        //$author->user_id = Auth::user()->id;
        $author->title = $request->title;
        $author->file_id = $request->file_id;
        $author->description = $request->description;

        $author->save();

        return response()->json([
            'success' => 'Author created.',
            'author' => $author
        ]);
    }
    public function destroy($id)
    {
        $author = Author::find($id);

        if(!$author) {
            return response()->json([
                'message' => '404 not found'
            ], 404);
        }

        $author->delete();

        return response()->json([
            'success' => 'Author deleted.'
        ]);
    }
}
