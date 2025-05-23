<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Validator;

use App\Models\LitsalonArticle;
use App\Models\LitsalonArticleTranslation;
use App\Models\LitsalonArticleRepeater;
use App\Models\LitsalonArticleRepeaterTranslation;
use App\Models\MediaFile;

class LitsalonArticlesTranslationsController extends Controller
{
    public function index()
    {
        $litsalon_article_translations = LitsalonArticleTranslation::paginate(10);

        return response()->json($litsalon_article_translations);
    }
    public function create($litsalon_article_id)
    {
        $litsalon_article = LitsalonArticle::with(['publications', 'awards'])->findOrFail($litsalon_article_id);
        
        $publications_translations = $this->getRepeaterTranslation($litsalon_article->publications ?? []);
        $awards_translations = $this->getRepeaterTranslation($litsalon_article->awards ?? []);
        
        return response()->json([
            'litsalon_article' => $litsalon_article,
            'publications_translations' => $publications_translations,
            'awards_translations' => $awards_translations,
        ]);
    }
    public function store(Request $request, $litsalon_article_id)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'dates' => ['nullable', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:255'],
        ]);

        if($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }
        
        $litsalon_article = LitsalonArticle::findOrFail($litsalon_article_id);
        
        $litsalon_article_translation = new LitsalonArticleTranslation();

        $litsalon_article_translation->litsalon_article_id = $litsalon_article->id;
        $litsalon_article_translation->language = '';
        $litsalon_article_translation->user_id = Auth::user()->id;
        $litsalon_article_translation->title = $request->title;
        $litsalon_article_translation->subtitle = $request->subtitle;
        $litsalon_article_translation->dates = $request->dates;
        $litsalon_article_translation->short_description = $request->short_description;
        $litsalon_article_translation->description = $request->description;
        $litsalon_article_translation->biography = $request->biography;
        $litsalon_article_translation->about_creativity = $request->about_creativity;
        $litsalon_article_translation->save();
        
        $this->storeRepeaterTranslation($litsalon_article_translation->id, $request->publications_translations ?? []);
        $this->storeRepeaterTranslation($litsalon_article_translation->id, $request->awards_translations ?? []);
        
        return response()->json([
            'success' => 'LitsalonArticle translation created.',
            'litsalon_article_translation' => $litsalon_article_translation
        ]);
    }
    public function show($id)
    {

    }
    public function edit($id)
    {
        $litsalon_article_translation = LitsalonArticleTranslation::findOrFail($id);
        $litsalon_article = LitsalonArticle::with(['publications.translation', 'awards.translation'])->find($litsalon_article_translation->litsalon_article_id);

        $publications_translations = $this->getRepeaterTranslation($litsalon_article->publications ?? []);
        $awards_translations = $this->getRepeaterTranslation($litsalon_article->awards ?? []);
        
        return response()->json([
            'litsalon_article' => $litsalon_article,
            'litsalon_article_translation' => $litsalon_article_translation,
            'publications_translations' => $publications_translations,
            'awards_translations' => $awards_translations,
        ]);
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'dates' => ['nullable', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:255'],
        ]);

        if($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $litsalon_article_translation = LitsalonArticleTranslation::find($id);
        $litsalon_article_translation->title = $request->title;
        $litsalon_article_translation->subtitle = $request->subtitle;
        $litsalon_article_translation->dates = $request->dates;
        $litsalon_article_translation->short_description = $request->short_description;
        $litsalon_article_translation->description = $request->description;
        $litsalon_article_translation->biography = $request->biography;
        $litsalon_article_translation->about_creativity = $request->about_creativity;
        $litsalon_article_translation->save();
        
        $this->storeRepeaterTranslation($litsalon_article_translation->id, $request->publications ?? []);
        $this->storeRepeaterTranslation($litsalon_article_translation->id, $request->awards ?? []);
        
        return response()->json([
            'success' => 'LitsalonArticle translation updated.',
            'litsalon_article_translation' => $litsalon_article_translation
        ]);
    }
    public function destroy($id)
    {
        $litsalon_article_translation = LitsalonArticleTranslation::find($id);

        if(!$litsalon_article_translation) {
            return response()->json([
                'message' => '404 not found'
            ], 404);
        }

        $litsalon_article_translation->delete();

        return response()->json([
            'success' => 'LitsalonArticle deleted.'
        ]);
    }
    protected function getRepeaterTranslation($repeaters)
    {
        $translations = [];
        foreach ($repeaters ?? [] as $repeater) {
            $repeater_translation = $repeater->translation ?? [];
            $translations[] = [
                'id' => ($repeater_translation->id ?? null) ? $repeater_translation->id : null,
                'litsalon_article_repeater_id' => $repeater->id,
                'title' => ($repeater_translation->title ?? null) ? $repeater_translation->title : null,
                'subtitle' => ($repeater_translation->subtitle ?? null) ? $repeater_translation->subtitle : null,
                'created_at' => ($repeater_translation->created_at ?? null) ? $repeater_translation->created_at : null,
            ];
        }
        return $translations;
    }
    protected function storeRepeaterTranslation($litsalon_article_repeater_id, $request_items)
    {
        foreach($request_items ?? [] as $request_item) {
            $translation = null;
            if( !($request_item['litsalon_article_repeater_id'] ?? null) ) {
                continue;
            }
            if( $request_item['id'] ?? null ) {
                $translation = LitsalonArticleRepeaterTranslation::
                    where('id', $request_item['id'])
                    ->where('litsalon_article_repeater_id', $request_item['litsalon_article_repeater_id'])
                    ->first();
            }
            if(!$translation) {
                $translation = new LitsalonArticleRepeaterTranslation();
                $translation->litsalon_article_repeater_id = $request_item['litsalon_article_repeater_id'];
                $translation->language = '';
            }
            $translation->title = $request_item['title'];
            $translation->subtitle = $request_item['subtitle'];
            $translation->save();
        }
    }
}
