<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;

use App\Models\Post;
use App\Models\Material;
use App\Models\Tag;
use App\Models\Category;
use App\Models\Author;
use App\Models\MediaFile;
use SimpleXMLElement;

class PostController extends Controller
{
    protected $is_default_locale = true;

    public function index(Request $request, $tag_slug = null)
    {
        $this->is_default_locale = (App::getlocale() === 'ru');

        $categories = Category::all();



        $posts = Post::
        with(['file', 'thumb'])
        ->when(!$this->is_default_locale, function($query) {
            $query->with('translation')->whereHas('translation');
        })
        ->orderBy('published_at', 'desc')
        ->paginate(14);

        if(session('frontend_version') != 'v1') {
            $view = 'frontend.v3.posts.index';
        } else {
            $view = 'frontend.posts.index';
        }




        return view($view, [
            'categories' => $categories,
            'posts' => $posts,

        ]);
    }
    public function single(Request $request, $slug, $tag_id = null)
    {
        $this->is_default_locale = (App::getlocale() === 'ru');

        $categories = Category::all();

        $post = Post::with(['file', 'thumb', 'author', 'tags', 'expert'])
            ->where('slug', $slug)
            ->when(!$this->is_default_locale, function($query) {
                $query->with('translation');
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
            $view = 'frontend.v3.posts.single';
        } else {
            $view = 'frontend.posts.single';
        }

        if ($tag_id) {
            $tag = Tag::find($tag_id);
            $tagTitle = $tag ? ($this->is_default_locale ? $tag->title : $tag->tag_translate) : '';
        } else {
            $tagTitle = $this->is_default_locale ? __('All Categories') : __('Статьи');
        }


        return view($view, [
            'categories' => $categories,
            'post' => $post,
            'posts_main' => $posts_main,
            'tagTitle' => $tagTitle,
        ]);
    }

    public function getPostsByTag($tag) {


        $this->is_default_locale = (App::getlocale() === 'ru');

        $categories = Category::all();

        $posts = Post::whereHas('tags', function($query) use ($tag) {
            $query->where('title', $tag);
        })
        ->orderBy('published_at', 'desc')
        ->paginate(14);

        $tagTitle = Tag::where('title', $tag)->value('title');



        return view('frontend.v3.posts.posts-by-tag', compact('posts', 'categories', 'tagTitle'));
    }

    public function newsSitemap()
    {
        $posts = Post::with(['file', 'thumb'])
            ->where('published_at', '>=', now()->subDays(2))
            ->orderBy('published_at', 'desc')
            ->get();

        $content = view('frontend.v3.sitemaps.news', compact('posts'))->render();

        return Response::make($content, 200, [
            'Content-Type' => 'application/xml'
        ]);
    }


}



