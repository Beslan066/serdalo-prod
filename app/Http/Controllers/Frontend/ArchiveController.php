<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

use App;
use App\Models\User;
use App\Models\Post;
use App\Models\Material;
use App\Models\Tag;
use App\Models\Category;
use App\Models\Author;
use App\Models\MediaFile;
use App\Models\Payment;
use App\Models\PaymentResult;
use App\Models\Newspaper;
use Carbon\Carbon;

class ArchiveController extends Controller
{
    public function index(Request $request)
    {
        $newspapers = Newspaper::with('file')->where('status', 1);

        if($request->years && (int)$request->years >= 1970) {
            $newspapers = $newspapers->whereYear('release_at', $request->years);
        }
        if($request->months && (int)$request->months >= 1 && (int)$request->months <= 12) {
            $newspapers = $newspapers->whereMonth('release_at', $request->months);
        }
        $newspapers = $newspapers->orderBy('release_at', 'desc')->paginate(8);

        if($request->years || $request->months) {
            $newspapers = $newspapers->appends($request->all());
        }
        $categories = Category::all();

        $view = 'frontend.v3.archive.index';

        return view($view, [
            'categories' => $categories,
            'newspapers' => $newspapers,
        ]);
    }
}

