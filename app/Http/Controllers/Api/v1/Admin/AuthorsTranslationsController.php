<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;



use App\Models\Author;
use App\Models\AuthorTranslation;
use App\Models\Category;
use App\Models\MediaFile;

class AuthorsTranslationsController extends Controller
{
    public function index()
    {
        $author_translations = AuthorTranslation::paginate(10);

        return response()->json($author_translations);
    }
    public function create($author_id)
    {
        $author = Author::findOrFail($author_id);

        return response()->json([
            'author' => $author,
        ]);
    }
    public function store(Request $request, $author_id)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'max:255'],
            'slug' => [
                'nullable',
                'regex:~^[a-z0-9]+(?:(-|_)[a-z0-9]+)*$~', 'max:255',
                'unique:materials,slug',
            ],
            'description' => ['required'],
        ]);

        if($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $author = Author::findOrFail($author_id);

        $author_translation = new AuthorTranslation();

        if($request->slug) {
            $author->slug = $request->slug;
        } else {
            $author->slug = Str::slug($request->title);
        }

        $author_translation->author_id = $author->id;
        $author_translation->language = '';
        $author_translation->user_id = Auth::user()->id;
        $author_translation->title = $request->title;
        $author_translation->description = $request->description;

        $author_translation->save();

        return response()->json([
            'success' => 'Author translation created.',
            'author_translation' => $author_translation
        ]);
    }
    public function show($id)
    {

    }
    public function edit($id)
    {
        $author_translation = AuthorTranslation::findOrFail($id);
        $author = Author::find($author_translation->author_id);

        return response()->json([
            'author' => $author,
            'author_translation' => $author_translation,
        ]);
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'max:255'],
            'slug' => [
                'nullable',
                'regex:~^[a-z0-9]+(?:(-|_)[a-z0-9]+)*$~', 'max:255',
                'unique:materials,slug',
            ],
            'description' => ['nullable'],
        ]);

        if($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }


        $author_translation = AuthorTranslation::find($id);

        $author_translation->title = $request->title;
        $author_translation->description = $request->description;

        $author_translation->save();

        return response()->json([
            'success' => 'Author translation updated.',
            'author_translation' => $author_translation
        ]);
    }
    public function destroy($id)
    {
        $author_translation = AuthorTranslation::find($id);

        if(!$author_translation) {
            return response()->json([
                'message' => '404 not found'
            ], 404);
        }

        $author_translation->delete();

        return response()->json([
            'success' => 'Author deleted.'
        ]);
    }
}
