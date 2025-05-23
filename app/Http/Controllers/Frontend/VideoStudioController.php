<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\VideoArticle;
use Illuminate\Http\Request;

class VideoStudioController extends Controller
{

    protected $is_default_locale = true;

    public function index(Request $request, $category_id = null) {

        $categories = Category::all();


        $videoArticles = VideoArticle::all();
        

        return view('frontend.v3.videostudio', compact('videoArticles', 'categories'));
    }
}
