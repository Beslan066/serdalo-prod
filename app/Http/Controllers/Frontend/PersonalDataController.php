<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class PersonalDataController extends Controller
{
    public function index() {

        $categories = Category::all();


        return view('frontend.v3.pages.personal-data', compact('categories'));

    }

    public function rules() {

        $categories = Category::all();


            $view = 'frontend.v3.material-use-rules';

        return view($view, compact('categories'));
    }

    public function about() {

        $categories = Category::all();


        $view = 'frontend.v3.pages.about';

        return view($view, compact('categories'));
    }
}
