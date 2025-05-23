<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Validator;

use App\Models\Document;
use App\Models\DocumentTranslation;
use App\Models\MediaFile;

class DocumentsTranslationsController extends Controller
{
    public function index()
    {
        $document_translations = DocumentTranslation::paginate(10);

        return response()->json($document_translations);
    }
    public function create($document_id)
    {
        $document = Document::findOrFail($document_id);

        return response()->json([
            'document' => $document,
        ]);
    }
    public function store(Request $request, $document_id)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'max:255'],
        ]);

        if($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }
        
        $document = Document::findOrFail($document_id);
        
        $document_translation = new DocumentTranslation();

        $document_translation->document_id = $document->id;
        $document_translation->language = '';
        $document_translation->user_id = Auth::user()->id;
        $document_translation->title = $request->title;
        $document_translation->description = $request->description;

        $document_translation->save();
        
        return response()->json([
            'success' => 'Document translation created.',
            'document_translation' => $document_translation
        ]);
    }
    public function show($id)
    {

    }
    public function edit($id)
    {
        $document_translation = DocumentTranslation::findOrFail($id);
        $document = Document::find($document_translation->document_id);

        return response()->json([
            'document' => $document,
            'document_translation' => $document_translation,
        ]);
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'max:255'],
        ]);

        if($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $document_translation = DocumentTranslation::find($id);
        
        $document_translation->title = $request->title;
        $document_translation->description = $request->description;

        $document_translation->save();
        
        return response()->json([
            'success' => 'Document translation updated.',
            'document_translation' => $document_translation
        ]);
    }
    public function destroy($id)
    {
        $document_translation = DocumentTranslation::find($id);

        if(!$document_translation) {
            return response()->json([
                'message' => '404 not found'
            ], 404);
        }

        $document_translation->delete();

        return response()->json([
            'success' => 'Document deleted.'
        ]);
    }
}
