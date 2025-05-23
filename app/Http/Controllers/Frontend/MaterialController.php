<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\MaterialTranslation;
use App\Models\PhotoReportage;
use App\Models\PostTranslation;
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

class MaterialController extends Controller
{
    protected $is_default_locale = true;

    public function index(Request $request, $category_id = null)
    {
        $this->is_default_locale = (App::getlocale() === 'ru');

        if($category_id) {
            $category = Category::findOrFail($category_id);
            $title = $category->title;
        } else {
            $title = __('Статьи');
        }

        $categories = Category::all();

        $materials = Material::query()
        ->with(['file', 'thumb'])
        ->when($category_id, function($query) use($category_id) {
            $query->where('category_id', $category_id);
        })
        ->when(!$this->is_default_locale, function($query) {
                $query->with('translation')->whereHas('translation');

        })
        ->where('category_id', '!=', 43)
        ->where('category_id', '!=', 70)


        ->orderBy('published_at', 'desc')
        ->paginate(14);



        if(session('frontend_version') != 'v1') {
            $view = 'frontend.v3.materials.index';
        } else {
            $view = 'frontend.materials.index';
        }

        if(App::getLocale() !== 'ru')
            $categoryTitle = $category_id ? $category->tag_translate : __('Статьи');
        else {
            $categoryTitle = $category_id ? $category->title : __('Статьи');
        }

        return view($view, [
            'title' => $title,
            'categories' => $categories,
            'materials' => $materials,
            'categoryTitle' => $categoryTitle,
        ]);
    }
    public function single(Request $request, $slug)
    {
        $categories = Category::all();

        $this->is_default_locale = (App::getlocale() === 'ru');

        $material = Material::
        with(['file', 'thumb', 'author', 'category', 'expert'])
        ->where('slug', $slug)
        ->when(!$this->is_default_locale, function($query) {
            $query->with('translation');//->whereHas('translation');
        })
        ->firstOrFail();


        $posts_main = Post::query()
        ->with(['file', 'thumb'])
        ->when(!$this->is_default_locale, function($query) {
            $query->with('translation')->whereHas('translation');
        })
        ->orderBy('published_at', 'desc')
        ->take(14)
        ->get();

        if(session('frontend_version') != 'v1') {
            $view = 'frontend.v3.materials.single';
        } else {
            $view = 'frontend.materials.single';
        }
        return view($view, [
            'categories' => $categories,
            'material' => $material,
            'posts_main' => $posts_main,
        ]);
    }


    public function getMaterialByTag($category, $category_id = null) {


        $this->is_default_locale = (App::getlocale() === 'ru');


        $posts = Material::query()
            ->with(['file', 'thumb'])
            ->when($category_id, function($query) use($category_id) {
                $query->where('category_id', $category_id);
            })
            ->when(!$this->is_default_locale, function($query) {
                $query->with('translation')->whereHas('translation');
            })
            ->orderBy('published_at', 'desc')
            ->paginate(14);

        $categories = Category::all();

        $categoryTitle = Category::where('title', $category)->value('title');

        $posts_main = Post::query()
            ->with(['file', 'thumb'])
            ->when(!$this->is_default_locale, function($query) {
                $query->with('translation')->whereHas('translation');
            })
            ->orderBy('published_at', 'desc')
            ->take(14)
            ->get();


        return view('frontend.v3.materials.posts-by-tag', compact('posts', 'categories', 'posts_main', 'categoryTitle'));
    }

    public function getPressReleases() {


        $categories = Category::all();


        $pressRealeses = Material::query()
            ->where('category_id', '=', 43)
            ->orderBy('published_at', 'desc')
            ->paginate(14);




        return view('frontend.v3.materials.press', compact('pressRealeses', 'categories'));
    }

    public function getJournalism() {

        $categories = Category::all();

        if(App::getlocale() === 'ru') {
            $journalism = Material::query()
                ->with(['file', 'thumb'])
                ->where('category_id', '=', 70)
                ->orderBy('published_at', 'desc')
                ->paginate(14);
        }else {
            $journalism = MaterialTranslation::query()

                ->where('category_id', '=', 70)
                ->orderBy('published_at', 'desc')
                ->paginate(14);
        }


        return view('frontend.v3.materials.journalism', compact('journalism', 'categories'));
    }

    public function getJournalismSingle(Request $request, $slug) {

        $this->is_default_locale = (App::getlocale() === 'ru');

        $categories = Category::all();

        $posts_main = Post::query()
            ->with(['file', 'thumb', 'author'])
            ->when(!$this->is_default_locale, function($query) {
                $query->with('translation')->whereHas('translation');
            })
            ->orderBy('published_at', 'desc')
            ->paginate(14);




        $material = MaterialTranslation::query()

            ->where('slug', $slug)
            ->firstOrFail();


            return view('frontend.v3.materials.journalism-single', compact( 'categories', 'material', 'posts_main'));

    }
}
