<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Validator;

use App\Models\VideoArticle;
use App\Models\Category;
use App\Models\MediaFile;

class VideoArticlesController extends Controller
{
    public function index()
    {
        $video_articles = VideoArticle::with('translation')->paginate(10);

        return response()->json($video_articles);
    }
    public function create()
    {
        /*if(!Auth::user()->hasRole('video_article')) {
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
        /*if(!Auth::user()->hasRole('video_article')) {
            return response()->json([
                'error' => 'У вас нет прав для создания новостей'
            ], 403);
        }*/

        

        $validator = Validator::make($request->all(), [
            'title' => ['required', 'max:255'],
            'file_id' => ['exists:files,id'],
        ]);

        if($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $video_article = new VideoArticle();

        $video_article->user_id = Auth::user()->id;
        $video_article->title = $request->title;
        $video_article->file_id = $request->file_id;
        $video_article->thumb_id = $request->thumb_id;

        $video_article->save();

        return response()->json([
            'success' => 'VideoArticle created.',
            'video_article' => $video_article
        ]);
    }
    public function show($id)
    {

    }
    public function edit($id)
    {
        $video_article = VideoArticle::find($id);
        $video_article_file = $video_article->file;

        return response()->json([
            'video_article' => $video_article,
            'files' => ($video_article_file) ? [$video_article_file] : [],
            'thumb' => $video_article->thumb,
        ]);
    }
    public function update(Request $request, $id)
    {
        /*if(!Auth::user()->hasRole('video_article')) {
            return response()->json([
                'error' => 'У вас нет прав для создания новостей'
            ], 403);
        }*/
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'max:255'],
            'file_id' => ['nullable', 'exists:files,id'],
        ]);

        if($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $video_article = VideoArticle::find($id);

        //$video_article->user_id = Auth::user()->id;
        $video_article->title = $request->title;
        $video_article->file_id = $request->file_id;
        $video_article->thumb_id = $request->thumb_id;

        $video_article->save();

        return response()->json([
            'success' => 'VideoArticle created.',
            'video_article' => $video_article
        ]);
    }
    public function destroy($id)
    {
        $video_article = VideoArticle::find($id);

        if(!$video_article) {
            return response()->json([
                'message' => '404 not found'
            ], 404);
        }

        $video_article->delete();

        return response()->json([
            'success' => 'VideoArticle deleted.'
        ]);
    }
}
