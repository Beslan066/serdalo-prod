<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

use App;
use App\Models\Subscriber;
use Carbon\Carbon;

class SubscribeController extends Controller
{
    protected $is_default_locale = true;
    
    
    public function store(Request $request)
    {
        return response()->json([
            'success' => 'Вы успешно подписались на рассылку.',
            'status' => true
        ]);
        $validator = Validator::make($request->all(), [
            
        ]);

        if($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $subscriber = new Subscriber();

        $subscriber->email = $request->email;

        $subscriber->save();
        
        return response()->json([
            'success' => 'Вы успешно подписались на рассылку.',
            'status' => true
        ]);
    }
}
