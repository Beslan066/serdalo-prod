<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class VideoReportageController extends Controller
{
    public function index() {

        $this->is_default_locale = (App::getlocale() === 'ru');

        $categories = Category::all();




        return view('frontend.v3.videoreportage.index', [
            'video_articles' => $this->getVideoArticles(),
            'categories' => $categories,
        ]);
    }

    protected function getVideoArticles()
    {
        $posts = Post::
        minimalSelect()
            ->with(['file', 'thumb'])
            ->when(!$this->is_default_locale, function($query) {
                $query->with('translation')->whereHas('translation');
            })
            ->whereHas('file', function ($query) {
                $query->where('type', 'video');
            })
            ->orderBy('published_at', 'desc')
            ->paginate(14);

        return $posts;
    }
}
