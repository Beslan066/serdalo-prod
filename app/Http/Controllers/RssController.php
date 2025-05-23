<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Response;
use App\Utils\SimpleXMLExtended;

class RssController extends Controller
{
    public function generateRss()
    {
        $posts = Post::latest()->limit(10)->get();

        $rssFeed = view('rss', compact('posts'))->render();

        return Response::make($rssFeed, '200')->header('Content-Type', 'text/xml');
    }
}

