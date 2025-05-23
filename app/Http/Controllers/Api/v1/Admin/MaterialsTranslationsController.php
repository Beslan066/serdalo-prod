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

use App\Models\Material;
use App\Models\MaterialTranslation;
use App\Models\Category;
use App\Models\MediaFile;

class MaterialsTranslationsController extends Controller
{
    public function index()
    {
        $material_translations = MaterialTranslation::paginate(10);

        return response()->json($material_translations);
    }
    
    public function create($material_id)
    {
        $material = Material::findOrFail($material_id);

        return response()->json([
            'material' => $material,
        ]);
    }
    public function store(Request $request, $material_id)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'max:255'],
            'subtitle' => ['required', 'max:255'],
            'slug' => [
                'nullable',
                'regex:~^[a-z0-9]+(?:(-|_)[a-z0-9]+)*$~', 'max:255',
                'unique:material_translations,slug',
            ],
            'lead' => ['required'],
            'description' => ['required'],
            'photo_title' => ['nullable'],
            'photo_description' => ['nullable'],
        ]);

        if($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $material = Material::findOrFail($material_id);
        $material_translation = new MaterialTranslation();
        $material_translation->material_id = $material->id;
        if($request->slug) {
            $material_translation->slug = $request->slug;
        } else {
            $material_translation->slug = Str::slug($request->title);
        }
        $material_translation->language = '';
        $material_translation->user_id = Auth::user()->id;
        $material_translation->title = $request->title;
        $material_translation->subtitle = $request->subtitle;
        $material_translation->lead = $request->lead;
        $material_translation->description = $request->description;
        $material_translation->photo_title = $request->photo_title;
        $material_translation->photo_description = $request->photo_description;

        $material_translation->save();

        return response()->json([
            'success' => 'Material translation created.',
            'material_translation' => $material_translation
        ]);
    }
    public function show($id)
    {

    }
    public function edit($id)
    {
        $material_translation = MaterialTranslation::findOrFail($id);
        $material = Material::find($material_translation->material_id);

        return response()->json([
            'material' => $material,
            'material_translation' => $material_translation,
        ]);
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'max:255'],
            'subtitle' => ['required', 'max:255'],
            'slug' => [
                'nullable',
                'regex:~^[a-z0-9]+(?:(-|_)[a-z0-9]+)*$~', 'max:255',
                Rule::unique('material_translations', 'slug')->ignore($id),
            ],
            'lead' => ['required'],
            'description' => ['required'],
            'photo_title' => ['nullable'],
            'photo_description' => ['nullable'],
        ]);

        if($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $material_translation = MaterialTranslation::find($id);
        if($request->slug) {
            $material_translation->slug = $request->slug;
        } else {
            $material_translation->slug = Str::slug($request->title);
        }
        $material_translation->title = $request->title;
        $material_translation->subtitle = $request->subtitle;
        $material_translation->lead = $request->lead;
        $material_translation->description = $request->description;
        $material_translation->photo_title = $request->photo_title;
        $material_translation->photo_description = $request->photo_description;

        $material_translation->save();

        return response()->json([
            'success' => 'Material translation updated.',
            'material_translation' => $material_translation
        ]);
    }
    public function destroy($id)
    {
        $material_translation = MaterialTranslation::find($id);

        if(!$material_translation) {
            return response()->json([
                'message' => '404 not found'
            ], 404);
        }

        $material_translation->delete();

        return response()->json([
            'success' => 'Material deleted.'
        ]);
    }
}
