<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\PhotoReportage;
use Illuminate\Http\Request;

class PhotoReportageController extends Controller
{
    public function index(Request $request, $category_id = null) {
        $reportages = PhotoReportage::orderBy('published_at', 'desc')->get();

        $categories = Category::all();

        return view('frontend.v3.reportages.index', compact('reportages', 'categories'));
    }

    public function single(PhotoReportage $reportage) {
        $post = PhotoReportage::find($reportage->id);

        $postDescription = $post->description;

        $postDescription = preg_replace('/<p.*?>.*?<\/p>/s', '', $postDescription);

        preg_match_all('/<img.*?>/', $postDescription, $matches);
        $photos = $matches[0];


        return view('frontend.v3.reportages.single', compact('reportage', 'photos'));
    }
    
}
