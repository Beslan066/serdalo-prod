<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use App;
use App\Models\Post;
use App\Models\PostTranslation;
use App\Models\Material;
use App\Models\MaterialTranslation;
use App\Models\Tag;
use App\Models\Category;
use App\Models\Author;
use App\Models\MediaFile;

class SearchController extends Controller
{
    protected $is_default_locale = true;

    public function index(Request $request)
    {
        $this->is_default_locale = (App::getlocale() === 'ru');

        $categories = Category::all();

        if(!$request->search) {
            if(session('frontend_version') != 'v1') {
                $view = 'frontend.v3.search.index';
            } else {
                $view = 'frontend.search.index';
            }
            return view($view, [
                'categories' => $categories,
                'subtitle' => __('Ничего не найдено'),
                'items' => [],
            ]);
        }

        $posts = Post::
        select(['id', 'type', 'slug', 'title', 'subtitle', 'file_id', 'thumb_id', 'published_at'])
        ->with(['file', 'thumb'])
        ->where('id', '>', 7500)
        ->when(!$this->is_default_locale, function($query) use($request) {
            $query->whereHas('translation', function($query) use($request) {
                    $query
                        ->where('title', 'like', '%' . $request->search . '%')
                        ->orWhere('lead', 'like', '%' . $request->search . '%')
                        ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        })
        ->when($this->is_default_locale, function($query) use($request) {
            $query->where(function($query) use($request) {
                $query
                    ->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('lead', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        });

        $materials = Material::
        select(['id', 'type', 'slug', 'title', 'subtitle', 'file_id', 'thumb_id', 'published_at'])
        ->with(['file', 'thumb'])
        ->when(!$this->is_default_locale, function($query) use($request) {
            $query->whereHas('translation', function($query) use($request) {
                $query
                    ->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('subtitle', 'like', '%' . $request->search . '%')
                    ->orWhere('lead', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        })
        ->when($this->is_default_locale, function($query) use($request) {
            $query->where(function($query) use($request) {
                $query
                    ->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('subtitle', 'like', '%' . $request->search . '%')
                    ->orWhere('lead', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        });

        $items = $materials
        ->unionAll($posts)
        ->orderBy('published_at', 'desc')
        ->paginate(14)->appends($request->all());

        if(!$this->is_default_locale) {
            $items->map(function ($item) {
                if($item->type = 'post') {
                    $translation = PostTranslation::select('id', 'post_id', 'title')->where('post_id', $item->id)->first();
                    if($translation) {
                        $item->title = $translation->title;
                    }
                } else {
                    $translation = MaterialTranslation::select('id', 'material_id', 'title', 'subtitle')->where('material_id', $item->id)->first();
                    if($translation) {
                        $item->title = $translation->title;
                        $item->subtitle = $translation->subtitle;
                    }
                }
            });
        }

        if(session('frontend_version') != 'v1') {
            $view = 'frontend.v3.search.index';
        } else {
            $view = 'frontend.search.index';
        }
        return view($view, [
            'categories' => $categories,
            'subtitle' => __('Результаты поиска по запросу:') . ' ' . $request->search,
            'items' => $items,
        ]);
    }
}
