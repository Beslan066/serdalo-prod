<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Validator;

use App\Models\VideoArticle;
use App\Models\VideoArticleTranslation;
use App\Models\MediaFile;

class VideoArticlesTranslationsController extends Controller
{
    public function index()
    {
        $video_article_translations = VideoArticleTranslation::paginate(10);

        return response()->json($video_article_translations);
    }
    public function create($video_article_id)
    {
        $video_article = VideoArticle::findOrFail($video_article_id);

        return response()->json([
            'video_article' => $video_article,
        ]);
    }
    public function store(Request $request, $video_article_id)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'max:255'],
        ]);

        if($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }
        
        $video_article = VideoArticle::findOrFail($video_article_id);
        
        $video_article_translation = new VideoArticleTranslation();

        $video_article_translation->video_article_id = $video_article->id;
        $video_article_translation->language = '';
        $video_article_translation->user_id = Auth::user()->id;
        $video_article_translation->title = $request->title;

        $video_article_translation->save();
        
        return response()->json([
            'success' => 'VideoArticle translation created.',
            'video_article_translation' => $video_article_translation
        ]);
    }
    public function show($id)
    {

    }
    public function edit($id)
    {
        $video_article_translation = VideoArticleTranslation::findOrFail($id);
        $video_article = VideoArticle::find($video_article_translation->video_article_id);

        return response()->json([
            'video_article' => $video_article,
            'video_article_translation' => $video_article_translation,
        ]);
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'max:255'],
        ]);

        if($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $video_article_translation = VideoArticleTranslation::find($id);
        
        $video_article_translation->title = $request->title;

        $video_article_translation->save();
        
        return response()->json([
            'success' => 'VideoArticle translation updated.',
            'video_article_translation' => $video_article_translation
        ]);
    }
    public function destroy($id)
    {
        $video_article_translation = VideoArticleTranslation::find($id);

        if(!$video_article_translation) {
            return response()->json([
                'message' => '404 not found'
            ], 404);
        }

        $video_article_translation->delete();

        return response()->json([
            'success' => 'VideoArticle deleted.'
        ]);
    }
}
