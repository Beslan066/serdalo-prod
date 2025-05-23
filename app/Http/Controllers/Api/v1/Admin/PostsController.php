<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use App\Models\Post;
use App\Models\Tag;
use App\Models\Category;
use App\Models\Author;
use App\Models\MediaFile;

class PostsController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::with('translation');
        $posts = $this->setFilterQuery($posts, $request);
        $posts = $posts->orderBy('published_at', 'desc')->paginate(10);
        //dump($request->all());
        return response()->json([
            'posts' => $posts,
            'tags' => Tag::get(['id', 'title']),
            'authors' => Author::get(['id', 'title']),
        ]);
    }
    public function setFilterQuery($model, $request)
    {
        if($request->title) {
            $model = $model->where('title', 'like', "%{$request->title}%");
        }
        if($request->content) {
            $model = $model->where(function($query) use($request) {
                $query->where('lead', 'like', "%{$request->content}%")
                ->orWhere('description', 'like', "%{$request->content}%");
            });
        }
        if($request->translation === 'has') {
            $model = $model->whereHas('translation');
        }
        if($request->translation === 'no') {
            $model = $model->whereDoesntHave('translation');
        }
        if($request->status && $request->status === 'yes') {
            $model = $model->where('status', 1);
        }
        if($request->status && $request->status === 'no') {
            $model = $model->where('status', 0);
        }
        if($request->topNews && $request->topNews === 'no') {
            $model = $model->where('topNews', 1);
        }
        if($request->promote_with_file && $request->promote_with_file === 'yes') {
            $model = $model->where('promote_with_file', 1);
        }
        if($request->promote_with_file && $request->promote_with_file === 'no') {
            $model = $model->where('promote_with_file', 0);
        }
        if($request->tags && is_array($request->tags)) {
            $model = $model->whereHas('post_tags', function($query) use($request) {
                $query->whereIn('tag_id', $request->tags);
            });
        }
        if($request->authors && is_array($request->authors)) {
            $model = $model->whereIn('author_id', $request->authors);
        }
        return $model;
    }
    public function create()
    {
        /*if(!Auth::user()->hasRole('author')) {
            return response()->json([
                'error' => 'У вас нет прав для создания новостей'
            ], 403);
        }*/
        return response()->json([
            'tags' => Tag::get(['id', 'title']),
            'authors' => Author::get(['id', 'title']),
            'experts' => Expert::get(['id', 'title']),
        ]);
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
                'unique:posts,slug',
            ],
            'lead' => ['required'],
            'description' => ['required'],
            'comment' => ['nullable'],
            'post_tags' => ['nullable'],
            'post_tags.*' => ['nullable', 'exists:tags,id'],
            'author_id' => ['required', 'exists:authors,id'],
            'expert_id' => ['nullable', 'exists:experts,id'],
            'file_id' => ['nullable', 'exists:files,id'],
            'photo_title' => ['nullable'],
            'published_at' => ['required'],
            'photo_description' => ['nullable'],
        ]);

        if($validator->fails()) {
            if($validator->errors()->first('post_tags.*')) {
                $validator->errors()->add('post_tags', $validator->errors()->first('post_tags.*'));
            }
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $post = new Post();

        if($request->slug) {
            $post->slug = $request->slug;
        } else {
            $post->slug = Str::slug($request->title);
        }
        $post->status = (int)(bool)$request->status;
        $post->topNews = (int)(bool)$request->topNews;
        $post->promote = (int)(bool)$request->promote;
        $post->promote_with_file = (int)(bool)$request->promote_with_file;
        $post->user_id = Auth::user()->id;
        $post->author_id = $request->author_id;
        $post->expert_id = $request->expert_id;
        $post->title = $request->title;
        $post->comment = $request->comment;
        $post->file_id = $request->file_id;
        $post->thumb_id = $request->thumb_id;
        $post->lead = $request->lead;
        $post->description = $request->description;
        $post->published_at = $request->published_at;
        $post->photo_title = $request->photo_title;
        $post->photo_description = $request->photo_description;

        $post->save();

        $post->tags()->sync($request->post_tags ?? []);

        return response()->json([
            'success' => 'Post created.',
            'post' => $post
        ]);
    }
    public function show($id)
    {

    }
    public function edit($id)
    {
        $post = Post::find($id);
        $post_file = $post->file;

        return response()->json([
            'post' => $post,
            'post_tags' => $post->post_tags->pluck('tag_id'),
            'tags' => Tag::get(['id', 'title']),
            'authors' => Author::get(['id', 'title']),
            'experts' => Expert::get(['id', 'title']),
            'files' => ($post_file) ? [$post_file] : [],
            'thumb' => $post->thumb,
            'published_at' => $post->published_at,
            'comment' => $post->comment,
            'photo_title' => $post->photo_title,
            'photo_description' => $post->photo_description,
        ]);
    }
    public function update(Request $request, $id)
    {
        /*if(!Auth::user()->hasRole('author')) {
            return response()->json([
                'error' => 'У вас нет прав для создания новостей'
            ], 403);
        }*/
        $post = Post::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => ['required', 'max:255'],
            'photo_title' => ['nullable'],
            'slug' => [
                'nullable',
                'regex:~^[a-z0-9]+(?:(-|_)[a-z0-9]+)*$~', 'max:255',
                Rule::unique('posts', 'slug')->ignore($id),
            ],
            'lead' => ['required'],
            'description' => ['required'],
            'comment' => ['nullable'],
            'photo_description' => ['nullable'],
            'post_tags' => ['nullable'],
            'post_tags.*' => ['nullable', 'exists:tags,id'],
            'author_id' => ['required', 'exists:authors,id'],
            'expert_id' => ['nullable', 'exists:experts,id'],
            'file_id' => ['nullable', 'exists:files,id'],
            'published_at' => ['required'],
        ]);

        if($validator->fails()) {
            if($validator->errors()->first('post_tags.*')) {
                $validator->errors()->add('post_tags', $validator->errors()->first('post_tags.*'));
            }
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        if($request->slug) {
            $post->slug = $request->slug;
        } else {
            $post->slug = Str::slug($request->title);
        }
        $post->status = (int)(bool)$request->status;
        $post->topNews = (int)(bool)$request->topNews;
        $post->promote = (int)(bool)$request->promote;
        $post->promote_with_file = (int)(bool)$request->promote_with_file;
        //$post->user_id = Auth::user()->id;
        $post->author_id = $request->author_id;
        $post->expert_id = $request->expert_id;
        $post->title = $request->title;
        $post->comment = $request->comment;
        $post->photo_title = $request->photo_title;
        $post->file_id = $request->file_id;
        $post->thumb_id = $request->thumb_id;
        $post->lead = $request->lead;
        $post->description = $request->description;
        $post->photo_description = $request->photo_description;
        $post->published_at = $request->published_at;

        $post->save();

        $post->tags()->sync($request->post_tags ?? []);

        return response()->json([
            'success' => 'Post created.',
            'post' => $post
        ]);
    }
    public function destroy($id)
    {
        $post = Post::find($id);

        if(!$post) {
            return response()->json([
                'message' => '404 not found'
            ], 404);
        }

        $post->delete();

        return response()->json([
            'success' => 'Post deleted.'
        ]);
    }
}
