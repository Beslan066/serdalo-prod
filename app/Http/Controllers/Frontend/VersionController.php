<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use App\Models\Post;
use App\Models\Material;
use App\Models\Tag;
use App\Models\Category;
use App\Models\Author;
use App\Models\MediaFile;

class VersionController extends Controller
{
    public function change($version)
    {
        session()->put('frontend_version', $version);
        return redirect()->back();
    }
}
