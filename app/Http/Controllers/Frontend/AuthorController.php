<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use App;
use App\Models\Post;
use App\Models\Material;
use App\Models\Tag;
use App\Models\Category;
use App\Models\Author;
use App\Models\MediaFile;

class AuthorController extends Controller
{
    protected $is_default_locale = true;

    public function index(Request $request, $author_id = null)
    {
        $this->is_default_locale = (App::getlocale() === 'ru');

        $author = [];
        if($author_id) {
            $author = Author::with(['file'])->findOrFail($author_id);
            $title = $author->title;
        } else {
            $title = 'Авторы';
        }

        $categories = Category::all();


        $posts = Post::
        with(['file', 'thumb'])
        ->when($author_id, function($query) use($author_id) {
            $query->where('author_id', $author_id);
        })
        ->when(!$this->is_default_locale, function($query) {
            $query->with('translation')->whereHas('translation');
        })
        ->orderBy('published_at', 'desc')
        ->take(5)
        ->get();

        $materials = Material::
        with(['file', 'thumb'])
        ->when($author_id, function($query) use($author_id) {
            $query->where('author_id', $author_id);
        })
        ->when(!$this->is_default_locale, function($query) {
            $query->with('translation')->whereHas('translation');
        })
        ->orderBy('published_at', 'desc')
        ->paginate(10);

        if(session('frontend_version') != 'v1') {
            $view = 'frontend.v3.authors.index';
        } else {
            $view = 'frontend.authors.index';
        }
        return view($view, [
            'title' => $title,
            'author' => $author,
            'categories' => $categories,
            'materials' => $materials,
            'posts' => $posts,
        ]);
    }
    public function posts(Request $request, $author_id = null)
    {
        $this->is_default_locale = (App::getlocale() === 'ru');

        $author = [];
        if($author_id) {
            $author = Author::with(['file'])->findOrFail($author_id);
            $title = $author->title;
        } else {
            $title = 'Авторы';
        }

        $categories = Category::all();


        $materials = Material::
        with(['file', 'thumb'])
        ->when($author_id, function($query) use($author_id) {
            $query->where('author_id', $author_id);
        })
        ->when(!$this->is_default_locale, function($query) {
            $query->with('translation')->whereHas('translation');
        })
        ->orderBy('published_at', 'desc')
        ->take(5)
        ->get();

        $posts = Post::
        with(['file', 'thumb'])
        ->when($author_id, function($query) use($author_id) {
            $query->where('author_id', $author_id);
        })
        ->when(!$this->is_default_locale, function($query) {
            $query->with('translation')->whereHas('translation');
        })
        ->orderBy('id', 'desc')
        ->paginate(10);

        if(session('frontend_version') != 'v1') {
            $view = 'frontend.v3.authors.posts';
        } else {
            $view = 'frontend.authors.posts';
        }
        return view($view, [
            'title' => $title,
            'author' => $author,
            'categories' => $categories,
            'materials' => $materials,
            'posts' => $posts,
        ]);
    }

    public function news(Request $request, $author_id = null) {

        $this->is_default_locale = (App::getlocale() === 'ru');

        $author = [];
        if($author_id) {
            $author = Author::with(['file'])->findOrFail($author_id);
            $title = $author->title;
        } else {
            $title = 'Авторы';
        }

        $posts = Post::
        with(['file', 'thumb'])
        ->when($author_id, function($query) use($author_id) {
            $query->where('author_id', $author_id);
        })
        ->when(!$this->is_default_locale, function($query) {
            $query->with('translation')->whereHas('translation');
        })
        ->orderBy('published_at', 'desc')
        ->paginate(10);

        if(session('frontend_version') != 'v1') {
            $view = 'frontend.v3.authors.news';
        } else {
            $view = 'frontend.authors.news';
        }
        return view($view, [
            'title' => $title,
            'author' => $author,
            'posts' => $posts,
        ]);
    }

}
