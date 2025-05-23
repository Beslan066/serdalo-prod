<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Validator;

use App\Models\Subscriber;

class SubscribersController extends Controller
{
    public function index()
    {
        $subscribers = Subscriber::all()->paginate(10);

        return response()->json($subscribers);
    }
    public function create()
    {
        /*if(!Auth::user()->hasRole('author')) {
            return response()->json([
                'error' => 'У вас нет прав для создания новостей'
            ], 403);
        }*/
        return response()->json([], 200);
    }
    public function store(Request $request)
    {
        /*if(!Auth::user()->hasRole('author')) {
            return response()->json([
                'error' => 'У вас нет прав для создания новостей'
            ], 403);
        }*/
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
            'success' => 'Subscriber created.',
            'subscriber' => $subscriber
        ]);
    }
    public function show($id)
    {

    }
    public function edit($id)
    {
        $subscriber = Subscriber::find($id);

        return response()->json([
            'subscriber' => $subscriber,
        ]);
    }
    public function update(Request $request, $id)
    {
        /*if(!Auth::user()->hasRole('author')) {
            return response()->json([
                'error' => 'У вас нет прав для создания новостей'
            ], 403);
        }*/
        $validator = Validator::make($request->all(), [
        ]);

        if($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $subscriber = Subscriber::find($id);

        $subscriber->email = $request->email;

        $subscriber->save();
        
        return response()->json([
            'success' => 'Subscriber created.',
            'subscriber' => $subscriber
        ]);
    }
    public function destroy($id)
    {
        $subscriber = Subscriber::find($id);

        if(!$subscriber) {
            return response()->json([
                'message' => '404 not found'
            ], 404);
        }

        $subscriber->delete();

        return response()->json([
            'success' => 'Subscriber deleted.'
        ]);
    }
}
