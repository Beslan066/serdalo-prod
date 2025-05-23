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
use App\Models\Category;
use App\Models\Author;
use App\Models\MediaFile;
use App\Models\Newspaper;

class NewspapersController extends Controller
{
    public function index()
    {
        $newspapers = Newspaper::orderBy('release_at', 'desc')->paginate(10);

        return response()->json($newspapers);
    }
    public function create()
    {
        return response()->json([

        ]);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'max:255'],
            'file_id' => ['nullable', 'exists:files,id'],
            'release_at' => ['required', 'max:255'],
        ]);

        if($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $newspaper = new Newspaper();
        $newspaper->status = (int)(bool)$request->status;
        $newspaper->user_id = Auth::user()->id;
        $newspaper->title = $request->title;
        $newspaper->file_id = $request->file_id;
        $newspaper->release_at = $request->release_at;
        $newspaper->save();

        return response()->json([
            'success' => 'Newspaper created.',
            'newspaper' => $newspaper
        ]);
    }
    public function show($id)
    {

    }
    public function edit($id)
    {
        $newspaper = Newspaper::find($id);
        $newspaper_file = $newspaper->file;

        return response()->json([
            'newspaper' => $newspaper,
            'files' => ($newspaper_file) ? [$newspaper_file] : [],
            'thumb' => $newspaper->thumb,
        ]);
    }
    public function update(Request $request, $id)
    {
        $newspaper = Newspaper::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => ['required', 'max:255'],
            'file_id' => ['nullable', 'exists:files,id'],
            'release_at' => ['required', 'max:255'],
        ]);

        if($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $newspaper->status = (int)(bool)$request->status;
        //$newspaper->user_id = Auth::user()->id;
        $newspaper->title = $request->title;
        $newspaper->file_id = $request->file_id;
        $newspaper->release_at = $request->release_at;
        $newspaper->save();

        return response()->json([
            'success' => 'Newspaper created.',
            'newspaper' => $newspaper
        ]);
    }
    public function destroy($id)
    {
        $newspaper = Newspaper::find($id);

        if(!$newspaper) {
            return response()->json([
                'message' => '404 not found'
            ], 404);
        }

        $newspaper->delete();

        return response()->json([
            'success' => 'Newspaper deleted.'
        ]);
    }
}
