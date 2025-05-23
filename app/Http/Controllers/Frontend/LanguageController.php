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

class LanguageController extends Controller
{
    public function change($language)
    {
        if(!in_array($language, ['ru', 'inh'])) {
            return abort(404);
        }
        
        $base = url('/');
        $back_uri = str_replace($base, "", back()->getTargetUrl());
        $back_uri = preg_replace("~/ru$~", "", $back_uri);
        $back_uri = str_replace("/ru/", "", $back_uri);
        $back_uri = preg_replace("~/inh$~", "", $back_uri);
        $back_uri = str_replace("/inh/", "", $back_uri);
        if($language === 'ru') {
            return redirect("{$base}/{$back_uri}");
        }
        return redirect("{$base}/inh{$back_uri}");
        
    }
}
