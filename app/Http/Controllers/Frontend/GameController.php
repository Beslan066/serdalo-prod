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

class GameController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        return view('frontend.v3.game.index', compact('categories'));

    }

    public function allGames()
    {
        $categories = Category::all();

        return view('frontend.v3.game.games', compact('categories'));
    }
}
