<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Models\PhotoReportage;
use App\Models\PhotoReportageTranslate;
use App\Models\Post;
use App\Models\PostTranslation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PhotoReportageTranslateController extends Controller
{
    public function index()
    {
        $photoReportageTranslations = PhotoReportageTranslate::paginate(10);

        return response()->json($photoReportageTranslations);
    }
    public function create($reportage_id)
    {
        $photoReportage = PhotoReportage::findOrFail($reportage_id);

        return response()->json([
                                    'post' => $photoReportage,
                                ]);
    }
    public function store(Request $request, $reportage_id)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'max:255'],
            'lead' => ['required'],
            'description' => ['required'],
        ]);

        if($validator->fails()) {
            return response()->json([
                                        'errors' => $validator->errors()
                                    ], 422);
        }

        $photoReportage = PhotoReportage::findOrFail($reportage_id);
        $photoReportageTranslation = new PhotoReportageTranslate();

        $photoReportageTranslation->post_id = $reportage_id;
        $photoReportageTranslation->language = '';
        $photoReportageTranslation->user_id = Auth::user()->id;
        $photoReportageTranslation->title = $request->title;
        $photoReportageTranslation->lead = $request->lead;
        $photoReportageTranslation->description = $request->description;

        $photoReportageTranslation->save();

        return response()->json([
                                    'success' => 'Post translation created.',
                                    'post_translation' => $photoReportageTranslation
                                ]);
    }
    public function show($id)
    {

    }
    public function edit($id)
    {
        $photoReportageTranslation = PhotoReportageTranslate::findOrFail($id);
        $photoReportage = PhotoReportage::find($photoReportageTranslation->post_id);

        return response()->json([
                                    'post' => $photoReportage,
                                    'post_translation' => $photoReportageTranslation,
                                ]);
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'max:255'],

            'lead' => ['required'],
            'description' => ['required'],
        ]);

        if($validator->fails()) {
            return response()->json([
                                        'errors' => $validator->errors()
                                    ], 422);
        }

        $photoReportageTranslation = PhotoReportageTranslate::find($id);

        $photoReportageTranslation->title = $request->title;
        $photoReportageTranslation->lead = $request->lead;
        $photoReportageTranslation->description = $request->description;

        $photoReportageTranslation->save();

        return response()->json([
                                    'success' => 'Post translation updated.',
                                    'post_translation' => $photoReportageTranslation
                                ]);
    }
    public function destroy($id)
    {
        $photoReportageTranslation = PhotoReportageTranslate::find($id);

        if(!$photoReportageTranslation) {
            return response()->json([
                                        'message' => '404 not found'
                                    ], 404);
        }

        $photoReportageTranslation->delete();

        return response()->json([
                                    'success' => 'Post deleted.'
                                ]);
    }
}
