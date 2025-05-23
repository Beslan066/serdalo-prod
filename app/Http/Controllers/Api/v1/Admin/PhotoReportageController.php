<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Models\PhotoReportage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use App\Models\MediaFile;

class PhotoReportageController extends Controller
{
    public function index(Request $request)
    {
        $photoreportage = PhotoReportage::query()->with('translation')->orderBy('published_at', 'desc')->paginate(10);


        //dump($request->all());

            return response()->json($photoreportage);
    }
    public function create()
    {
        /*if(!Auth::user()->hasRole('author')) {
            return response()->json([
                'error' => 'У вас нет прав для создания новостей'
            ], 403);
        }*/
        return response()->json(['reportage' => 'created']);
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

            'lead' => ['required'],
            'description' => ['required'],
            'file_id' => ['nullable', 'exists:files,id'],

        ]);

        $photoReportage = new photoReportage();


        $photoReportage->title = $request->title;
        $photoReportage->file_id = $request->file_id;
        $photoReportage->lead = $request->lead;
        $photoReportage->description = $request->description;
        $photoReportage->published_at = $request->published_at;


        $photoReportage->save();

        return response()->json([
                                    'success' => 'Reportage created.',
                                    'Reportage' => $photoReportage
                                ]);
    }
    public function show($id)
    {

    }
    public function edit($id)
    {
        $photoReportage = PhotoReportage::find($id);
        $photoReportage_file = $photoReportage->file;

        return response()->json([
                                    'post' => $photoReportage,
                                    'files' => ($photoReportage_file) ? [$photoReportage_file] : [],
                                    'published_at' => $photoReportage->published_at,
                                    'lead' => $photoReportage->lead,
                                    'photo' => $photoReportage->description,
                                ]);
    }
    public function update(Request $request, $id)
    {
        /*if(!Auth::user()->hasRole('author')) {
            return response()->json([
                'error' => 'У вас нет прав для создания новостей'
            ], 403);
        }*/
        $photoReportage = PhotoReportage::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => ['required', 'max:255'],
            'lead' => ['required'],
            'description' => ['required'],
            'file_id' => ['nullable', 'exists:files,id'],
            'published_at' => ['required'],
        ]);




        $photoReportage->title = $request->title;
        $photoReportage->file_id = $request->file_id;
        $photoReportage->lead = $request->lead;
        $photoReportage->description = $request->description;
        $photoReportage->published_at = $request->published_at;

        $photoReportage->save();



        return response()->json([
                                    'success' => 'Post created.',
                                    'post' => $photoReportage
                                ]);
    }
    public function destroy($id)
    {
        $photoreportage = PhotoReportage::find($id);

        if(!$photoreportage) {
            return response()->json([
                                        'message' => '404 not found'
                                    ], 404);
        }

        $photoreportage->delete();

        return response()->json([
                                    'success' => 'Post deleted.'
                                ]);
    }
}
