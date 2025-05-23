<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

use App\Models\Document;
use App\Models\Category;
use App\Models\MediaFile;

class DocumentsController extends Controller
{
    public function index()
    {
        $documents = Document::with('translation')->orderBy('id', 'desc')->paginate(10);

        return response()->json($documents);
    }
    public function create()
    {
        /*if(!Auth::user()->hasRole('document')) {
            return response()->json([
                'error' => 'У вас нет прав для создания новостей'
            ], 403);
        }*/
        return response()->json([
            'categories' => Category::get(['id', 'title'])
        ], 200);
    }
    public function store(Request $request)
    {
        /*if(!Auth::user()->hasRole('document')) {
            return response()->json([
                'error' => 'У вас нет прав для создания новостей'
            ], 403);
        }*/
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'max:255'],
            'file_id' => ['exists:files,id'],
        ]);

        if($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $document = new Document();

        $document->user_id = Auth::user()->id;
        $document->title = $request->title;
        $document->type = $request->type;
        $document->file_id = $request->file_id;
        $document->signed_at = $request->signed_at;
        $document->published_at = $request->published_at;
        $document->enter_into_force_at = $request->enter_into_force_at;
        $document->description = $request->description;

        $document->save();
        
        return response()->json([
            'success' => 'Document created.',
            'document' => $document
        ]);
    }
    public function show($id)
    {

    }
    public function edit($id)
    {
        $document = Document::find($id);
        $document_file = $document->file;
        
        return response()->json([
            'document' => $document,
            'files' => ($document_file) ? [$document_file] : [],
        ]);
    }
    public function update(Request $request, $id)
    {
        /*if(!Auth::user()->hasRole('document')) {
            return response()->json([
                'error' => 'У вас нет прав для создания новостей'
            ], 403);
        }*/
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'max:255'],
            'file_id' => ['nullable', 'exists:files,id'],
        ]);

        if($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $document = Document::find($id);

        //$document->user_id = Auth::user()->id;
        $document->title = $request->title;
        $document->type = $request->type;
        $document->file_id = $request->file_id;
        $document->signed_at = $request->signed_at;
        $document->published_at = $request->published_at;
        $document->enter_into_force_at = $request->enter_into_force_at;
        $document->description = $request->description;

        $document->save();
        
        return response()->json([
            'success' => 'Document created.',
            'document' => $document
        ]);
    }
    public function destroy($id)
    {
        $document = Document::find($id);

        if(!$document) {
            return response()->json([
                'message' => '404 not found'
            ], 404);
        }

        $document->delete();

        return response()->json([
            'success' => 'Document deleted.'
        ]);
    }
}
