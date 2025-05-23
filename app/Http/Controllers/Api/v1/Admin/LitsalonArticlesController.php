<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Validator;

use App\Models\LitsalonArticle;
use App\Models\LitsalonArticleRepeater;
use App\Models\Category;
use App\Models\MediaFile;

class LitsalonArticlesController extends Controller
{
    public function index()
    {
        $litsalon_articles = LitsalonArticle::with('translation')->paginate(10);

        return response()->json($litsalon_articles);
    }
    public function create()
    {
        /*if(!Auth::user()->hasRole('litsalon_article')) {
            return response()->json([
                'error' => 'У вас нет прав для создания новостей'
            ], 403);
        }*/
        return response()->json([
            'generations' => LitsalonArticle::$GENERATIONS,
            'publications' => [],
            'awards' => [],
        ], 200);
    }
    public function store(Request $request)
    {
        /*if(!Auth::user()->hasRole('litsalon_article')) {
            return response()->json([
                'error' => 'У вас нет прав для создания новостей'
            ], 403);
        }*/
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'max:255'],
            'generation' => ['required', 'in:' . implode(',', array_keys(LitsalonArticle::$GENERATIONS))],
            'file_id' => ['exists:files,id'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'dates' => ['nullable', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:255'],
        ]);

        if($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $litsalon_article = new LitsalonArticle();

        $litsalon_article->user_id = Auth::user()->id;
        $litsalon_article->title = $request->title;
        $litsalon_article->generation = $request->generation;
        $litsalon_article->file_id = $request->file_id;
        $litsalon_article->subtitle = $request->subtitle;
        $litsalon_article->dates = $request->dates;
        $litsalon_article->short_description = $request->short_description;
        $litsalon_article->description = $request->description;
        $litsalon_article->biography = $request->biography;
        $litsalon_article->about_creativity = $request->about_creativity;

        $litsalon_article->save();

        $this->storeRepeater($litsalon_article->id, 'publications', $request->publications ?? []);
        $this->storeRepeater($litsalon_article->id, 'awards', $request->awards ?? []);

        return response()->json([
            'success' => 'LitsalonArticle created.',
            'litsalon_article' => $litsalon_article
        ]);
    }
    public function show($id)
    {

    }
    public function edit($id)
    {
        $litsalon_article = LitsalonArticle::with(['publications.file', 'awards.file'])->find($id);
        $litsalon_article_file = $litsalon_article->file;

        return response()->json([
            'litsalon_article' => $litsalon_article,
            'generations' => LitsalonArticle::$GENERATIONS,
            'publications' => $litsalon_article->publications,
            'awards' => $litsalon_article->awards,
            'files' => ($litsalon_article_file) ? [$litsalon_article_file] : [],
        ]);
    }
    public function update(Request $request, $id)
    {
        /*if(!Auth::user()->hasRole('litsalon_article')) {
            return response()->json([
                'error' => 'У вас нет прав для создания новостей'
            ], 403);
        }*/

        $validator = Validator::make($request->all(), [
            'title' => ['required', 'max:255'],
            'generation' => ['required', 'in:' . implode(',', array_keys(LitsalonArticle::$GENERATIONS))],
            'file_id' => ['nullable', 'exists:files,id'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'dates' => ['nullable', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:255'],
        ]);

        if($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $litsalon_article = LitsalonArticle::find($id);

        //$litsalon_article->user_id = Auth::user()->id;
        $litsalon_article->title = $request->title;
        $litsalon_article->generation = $request->generation;
        $litsalon_article->file_id = $request->file_id;
        $litsalon_article->subtitle = $request->subtitle;
        $litsalon_article->dates = $request->dates;
        $litsalon_article->short_description = $request->short_description;
        $litsalon_article->description = $request->description;
        $litsalon_article->biography = $request->biography;
        $litsalon_article->about_creativity = $request->about_creativity;

        $litsalon_article->save();

        $this->storeRepeater($id, 'publications', $request->publications ?? []);
        $this->storeRepeater($id, 'awards', $request->awards ?? []);

        return response()->json([
            'success' => 'LitsalonArticle created.',
            'litsalon_article' => $litsalon_article
        ]);
    }
    public function destroy($id)
    {
        $litsalon_article = LitsalonArticle::find($id);

        if(!$litsalon_article) {
            return response()->json([
                'message' => '404 not found'
            ], 404);
        }

        LitsalonArticleRepeater::where('litsalon_article_id', $id)->delete();
        $litsalon_article->delete();

        return response()->json([
            'success' => 'LitsalonArticle deleted.'
        ]);
    }
    public function destroyPublication($id, $publication_id)
    {
        $publication = LitsalonArticleRepeater::where('id', $publication_id)->where('litsalon_article_id', $id)->first();
        if(!$publication) {
            return response()->json([
                'message' => '404 not found'
            ], 404);
        }

        $publication->delete();

        return response()->json([
            'success' => 'Publication deleted.'
        ]);
    }
    public function destroyAward($id, $award_id)
    {
        $award = LitsalonArticleRepeater::where('id', $award_id)->where('litsalon_article_id', $id)->first();
        if(!$award) {
            return response()->json([
                'message' => '404 not found'
            ], 404);
        }

        $award->delete();

        return response()->json([
            'success' => 'Award deleted.'
        ]);
    }

    protected function storeRepeater($litsalon_article_id, $type, $request_items)
    {
        foreach($request_items ?? [] as $request_item) {
            $field = null;
            if( $request_item['id'] ?? null ) {
                $field = LitsalonArticleRepeater::where('id', $request_item['id'])->where('litsalon_article_id', $litsalon_article_id)->first();
            }
            if(!$field) {
                $field = new LitsalonArticleRepeater();
                $field->litsalon_article_id = $litsalon_article_id;
                $field->type = $type;
            }
            $field->file_id = $request_item['file']['id'] ?? null;
            $field->title = $request_item['title'];
            $field->subtitle = $request_item['subtitle'];
            $field->description = $request_item['description'];
            $field->text_body = $request_item['text_body'];
            $field->save();
        }
    }
}
