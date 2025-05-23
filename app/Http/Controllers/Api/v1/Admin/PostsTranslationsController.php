<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use App\Models\Post;
use App\Models\PostTranslation;
use App\Models\Category;
use App\Models\MediaFile;

class PostsTranslationsController extends Controller
{
    public function index()
    {
        $post_translations = PostTranslation::paginate(10);

        return response()->json($post_translations);
    }
    public function create($post_id)
    {
        $post = Post::findOrFail($post_id);

        return response()->json([
            'post' => $post,
        ]);
    }
    public function store(Request $request, $post_id)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'max:255'],
            'slug' => [
                'nullable',
                'regex:~^[a-z0-9]+(?:(-|_)[a-z0-9]+)*$~', 'max:255',
                'unique:post_translations,slug',
            ],
            'lead' => ['required'],
            'description' => ['required'],
            'image_title' => ['nullable'],
            'image_description' => ['nullable'],
        ]);

        if($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $post = Post::findOrFail($post_id);
        $post_translation = new PostTranslation();

        $post_translation->post_id = $post->id;
        if($request->slug) {
            $post_translation->slug = $request->slug;
        } else {
            $post_translation->slug = Str::slug($request->title);
        }
        $post_translation->language = '';
        $post_translation->user_id = Auth::user()->id;
        $post_translation->title = $request->title;
        $post_translation->lead = $request->lead;
        $post_translation->description = $request->description;
        $post_translation->image_title = $request->image_title;
        $post_translation->image_description = $request->image_description;

        $post_translation->save();

        return response()->json([
            'success' => 'Post translation created.',
            'post_translation' => $post_translation
        ]);
    }
    public function show($id)
    {

    }
    public function edit($id)
    {
        $post_translation = PostTranslation::findOrFail($id);
        $post = Post::find($post_translation->post_id);

        return response()->json([
            'post' => $post,
            'post_translation' => $post_translation,

        ]);
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'max:255'],
            'slug' => [
                'nullable',
                'regex:~^[a-z0-9]+(?:(-|_)[a-z0-9]+)*$~', 'max:255',
                Rule::unique('post_translations', 'slug')->ignore($id),
            ],
            'lead' => ['required'],
            'description' => ['required'],
            'image_title' => ['nullable'],
            'image_description' => ['nullable'],
        ]);

        if($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $post_translation = PostTranslation::find($id);

        if($request->slug) {
            $post_translation->slug = $request->slug;
        } else {
            $post_translation->slug = Str::slug($request->title);
        }
        $post_translation->title = $request->title;
        $post_translation->image_title = $request->image_title;
        $post_translation->lead = $request->lead;
        $post_translation->description = $request->description;
        $post_translation->image_description = $request->image_description;

        $post_translation->save();

        return response()->json([
            'success' => 'Post translation updated.',
            'post_translation' => $post_translation
        ]);
    }
    public function destroy($id)
    {
        $post_translation = PostTranslation::find($id);

        if(!$post_translation) {
            return response()->json([
                'message' => '404 not found'
            ], 404);
        }

        $post_translation->delete();

        return response()->json([
            'success' => 'Post deleted.'
        ]);
    }
}
