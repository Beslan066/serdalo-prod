<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App;

use App\Models\Author;
use App\Models\Category;
use App\Models\MediaFile;
use App\Models\Material;
use App\Models\Post;

class StatisticsController extends Controller
{
    public function index(Request $request)
    {
        $start_date = Carbon::now()->subMonths(1)->format('Y-m-d');
        if($request->start_date) {
            $start_date = Carbon::parse($request->start_date)->format('Y-m-d');
        }
        $end_date = Carbon::now()->format('Y-m-d');
        if($request->end_date) {
            $end_date = Carbon::parse($request->end_date)->format('Y-m-d');
        }
        $translation = ($request->lang && $request->lang === 'inh') ? true : false;

        if($translation) {
            $authors = Author::with([
                'posts.translation',
                'posts' => function($query) use($start_date, $end_date) {
                    $query->select(['id', 'author_id', 'title', 'lead', 'description', 'published_at'])
                        ->whereDate('published_at', '>', $start_date)
                        ->whereDate('published_at', '<', $end_date)
                        ->whereHas('translation');
                }
            ]);
        } else {
            $authors = Author::with([
                'posts' => function($query) use($start_date, $end_date) {
                    $query->select(['id', 'author_id', 'title', 'lead', 'description', 'published_at'])
                        ->whereDate('published_at', '>', $start_date)
                        ->whereDate('published_at', '<', $end_date);
                }
            ]);
        }
        $authors = $authors->paginate(5);
        $authors = $authors->toArray();
        foreach ($authors['data'] as $author_key => $author) {
            $total_count = 0;
            foreach ($author['posts'] as $item_key => $item) {
                if($translation) {
                    $authors['data'][$author_key]['posts'][$item_key]['title'] = $item['translation']['title'] ?? $item['title'];
                    $authors['data'][$author_key]['posts'][$item_key]['description'] = $item['translation']['description'] ?? '';
                }
                $total_count++;
            }
            $authors['data'][$author_key]['total_count'] = $total_count;
        }

        return response()->json([
            'authors' => $authors,
        ]);
    }
    public function materials(Request $request)
    {
        $start_date = Carbon::now()->subMonths(1)->format('Y-m-d');
        if($request->start_date) {
            $start_date = Carbon::parse($request->start_date)->format('Y-m-d');
        }
        $end_date = Carbon::now()->format('Y-m-d');
        if($request->end_date) {
            $end_date = Carbon::parse($request->end_date)->format('Y-m-d');
        }
        $translation = ($request->lang && $request->lang === 'inh') ? true : false;

        if($translation) {
            $authors = Author::with([
                'materials.translation',
                'materials' => function($query) use($start_date, $end_date) {
                    $query->select(['id', 'author_id', 'title', 'lead', 'description', 'published_at'])
                        ->whereDate('published_at', '>', $start_date)
                        ->whereDate('published_at', '<', $end_date)
                        ->whereHas('translation');
                }
            ]);
        } else {
            $authors = Author::with([
                'materials' => function($query) use($start_date, $end_date) {
                    $query->select(['id', 'author_id', 'title', 'lead', 'description', 'published_at'])
                        ->whereDate('published_at', '>', $start_date)
                        ->whereDate('published_at', '<', $end_date);
                }
            ]);
        }
        $authors = $authors->paginate(5);
        $authors = $authors->toArray();
        foreach ($authors['data'] as $author_key => $author) {
            $total_string_count = 0;
            foreach ($author['materials'] as $item_key => $item) {
                if($translation) {
                    $authors['data'][$author_key]['materials'][$item_key]['title'] = $item['translation']['title'] ?? $item['title'];
                    $authors['data'][$author_key]['materials'][$item_key]['description'] = $item['translation']['description'] ?? '';
                    $char_count = mb_strlen(strip_tags($item['translation']['description'] ?? ''), 'utf-8');
                } else {
                    $char_count = mb_strlen(strip_tags($item['description']), 'utf-8');
                }
                $string_count = (int)ceil($char_count / 81);

                $total_string_count += $string_count;
                $authors['data'][$author_key]['materials'][$item_key]['char_count'] = $char_count;
                $authors['data'][$author_key]['materials'][$item_key]['string_count'] = $string_count;
            }
            $authors['data'][$author_key]['total_string_count'] = $total_string_count;
        }

        return response()->json([
            'authors' => $authors,
        ]);
    }
}
