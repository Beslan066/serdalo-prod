<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\LitsalonArticleRepeater;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use App;
use App\Models\Post;
use App\Models\Material;
use App\Models\Tag;
use App\Models\Category;
use App\Models\Author;
use App\Models\MediaFile;
use App\Models\LitsalonArticle;

class LitSalonController extends Controller
{
    protected $is_default_locale = true;

    public function index(Request $request, $category_id = null)
    {


        $categories = Category::all();

        $oldLitArticle = LitsalonArticle::query()
            ->where('generation', 'the_older_generation')
            ->take(3)
            ->orderBy('id', 'desc')
            ->get();

        $youngerLitArticle = LitsalonArticle::query()
            ->where('generation', 'younger_generation')
            ->take(6)
            ->get();

        $middleLitArticle = LitsalonArticle::query()
            ->where('generation', 'middle_generation')
            ->orderBy('id', 'desc')
            ->take(3)
            ->get();


        return view('frontend.v3.litsalon', compact('oldLitArticle', 'categories', 'youngerLitArticle', 'middleLitArticle'));
    }

    public function single(LitsalonArticle $article) {


        $categories = Category::all();


        $publications = App\Models\LitsalonArticleRepeater::query()
            ->where('litsalon_article_id', $article->id)
            ->where('type', 'publications')
            ->get();

        $awards = App\Models\LitsalonArticleRepeater::query()
            ->where('litsalon_article_id', $article->id)
            ->where('type', 'awards')
            ->get();




        return view('frontend.v3.salonSingle', compact('article', 'publications', 'awards', 'categories'));
    }

    public function singleWorks(LitsalonArticleRepeater $work) {

        return view('frontend.v3.singleWork', compact('work'));
    }
}
