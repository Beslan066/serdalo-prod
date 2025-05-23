<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Models\MaterialTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use App\Models\Material;
use App\Models\Category;
use App\Models\Author;
use App\Models\MediaFile;

class MaterialsIngController extends Controller
{
    public function index(Request $request)
    {

        $materials = MaterialTranslation::orderBy('id', 'desc')->paginate(10);



        return response()->json($materials);

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
        if($request->translation && $request->translation === 'has') {
            $model = $model->whereHas('translation');
        }
        if($request->translation && $request->translation === 'no') {
            $model = $model->whereDoesntHave('translation');
        }
        if($request->category && is_array($request->category)) {
            $model = $model->whereIn('category_id', $request->category);
        }
        if($request->status && $request->status === 'yes') {
            $model = $model->where('status', 1);
        }
        if($request->status && $request->status === 'no') {
            $model = $model->where('status', 0);
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
            'categories' => Category::get(['id', 'title']),
            'authors' => Author::get(['id', 'title']),
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
            'subtitle' => ['required', 'max:255'],
            'slug' => [
                'nullable',
                'regex:~^[a-z0-9]+(?:(-|_)[a-z0-9]+)*$~', 'max:255',
                'unique:materials,slug',
            ],
            'status' => ['nullable'],
            'material_id' => ['nullable'],
            'promote' => ['nullable'],
            'promote_with_file' => ['nullable'],
            'popular' => ['nullable'],
            'sticky' => ['nullable'],
            'user_id' => ['nullable'],
            'thumb_id' => ['nullable'],
            'category_id' => ['required', 'exists:categories,id'],
            'lead' => ['required'],
            'description' => ['required'],
            'author_id' => ['required', 'exists:authors,id'],
            'file_id' => ['nullable', 'exists:files,id'],
            'photo_title' => ['nullable'],
            'published_at' => ['required'],
            'photo_description' => ['nullable'],
        ]);

        if($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $material = new MaterialTranslation();
        if($request->slug) {
            $material->slug = $request->slug;
        } else {
            $material->slug = Str::slug($request->title);
        }
        $material->status = (int)(bool)$request->status;
        $material->promote = (int)(bool)$request->promote;
        $material->promote_with_file = (int)(bool)$request->promote_with_file;
        $material->sticky = (int)(bool)$request->sticky;
        $material->popular = (int)(bool)$request->popular;
        $material->user_id = Auth::user()->id;
        $material->category_id = $request->category_id;
        $material->author_id = $request->author_id;
        $material->title = $request->title;
        $material->subtitle = $request->subtitle;
        $material->file_id = $request->file_id;
        $material->thumb_id = $request->thumb_id;
        $material->lead = $request->lead;
        $material->description = $request->description;
        $material->published_at = $request->published_at;
        $material->photo_title = $request->photo_title;
        $material->photo_description = $request->photo_description;
        $material->save();

        return response()->json([
            'success' => 'Material created.',
            'material' => $material
        ]);
    }
    public function show($id)
    {

    }
    public function edit($id)
    {
        $material = MaterialTranslation::find($id);
        $material_file = $material->file;

        return response()->json([
            'material' => $material,
            'categories' => Category::get(['id', 'title']),
            'authors' => Author::get(['id', 'title']),
            'files' => ($material_file) ? [$material_file] : [],
            'thumb' => $material->thumb,
            'published_at' => $material->published_at,
            'photo_title' => $material->photo_title,
            'photo_description' => $material->photo_description,
            'description' => $material->description,
        ]);
    }
    public function update(Request $request, $id)
    {
        /*if(!Auth::user()->hasRole('author')) {
            return response()->json([
                'error' => 'У вас нет прав для создания новостей'
            ], 403);
        }*/
        $material = MaterialTranslation::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => ['required', 'max:255'],
            'photo_title' => ['nullable'],
            'subtitle' => ['required', 'max:255'],
            'slug' => [
                'nullable',
                'regex:~^[a-z0-9]+(?:(-|_)[a-z0-9]+)*$~', 'max:255',
                Rule::unique('materials', 'slug')->ignore($id),
            ],
            'lead' => ['required'],
            'category_id' => ['nullable', 'exists:categories,id'],//required
            'author_id' => ['required', 'exists:authors,id'],
            'file_id' => ['nullable', 'exists:files,id'],
            'published_at' => ['required'],
            'photo_description' => ['nullable'],
            'description' => ['required'],
        ]);

        if($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        if($request->slug) {
            $material->slug = $request->slug;
        } else {
            $material->slug = Str::slug($request->title);
        }
        $material->status = (int)(bool)$request->status;
        $material->promote = (int)(bool)$request->promote;
        $material->promote_with_file = (int)(bool)$request->promote_with_file;
        $material->sticky = (int)(bool)$request->sticky;
        $material->popular = (int)(bool)$request->popular;
        //$material->user_id = Auth::user()->id;
        $material->category_id = $request->category_id;
        $material->author_id = $request->author_id;
        $material->title = $request->title;
        $material->photo_title = $request->photo_title;
        $material->subtitle = $request->subtitle;
        $material->file_id = $request->file_id;
        $material->thumb_id = $request->thumb_id;
        $material->lead = $request->lead;
        $material->photo_description = $request->photo_description;
        $material->description = $request->description;
        $material->published_at = $request->published_at;
        $material->save();

        return response()->json([
            'success' => 'Material created.',
            'material' => $material
        ]);
    }
    public function destroy($id)
    {
        $material = MaterialTranslation::find($id);

        if(!$material) {
            return response()->json([
                'message' => '404 not found'
            ], 404);
        }

        $material->delete();

        return response()->json([
            'success' => 'Material deleted.'
        ]);
    }
}
