<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Models\PhotoReportage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use App\Models\MediaFile;
use Intervention\Image\Facades\Image;

class PhotoReportageController extends Controller
{
    public function index(Request $request)
    {
        $photoreportage = PhotoReportage::query()->with('translation')->orderBy('published_at', 'desc')->paginate(10);


        //dump($request->all());

            return response()->json($photoreportage);
    }
    public function create()
    {
        /*if(!Auth::user()->hasRole('author')) {
            return response()->json([
                'error' => 'У вас нет прав для создания новостей'
            ], 403);
        }*/
        return response()->json(['reportage' => 'created']);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'max:255'],
            'lead' => ['required'],
            'description' => ['required'],
            'file_id' => ['nullable', 'exists:files,id'],
        ]);

        if($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $photoReportage = new PhotoReportage();
        $photoReportage->title = $request->title;
        $photoReportage->file_id = $request->file_id;
        $photoReportage->lead = $request->lead;
        $photoReportage->description = $request->description;
        $photoReportage->published_at = $request->published_at;
        $photoReportage->save();

        // Генерация WebP после сохранения фоторепортажа
        if ($request->file_id) {
            $this->generateWebPImagesForPhotoReportage($photoReportage);
        }

        return response()->json([
            'success' => 'Reportage created.',
            'Reportage' => $photoReportage
        ]);
    }

    private function generateWebPImagesForPhotoReportage(PhotoReportage $photoReportage)
    {
        // Генерация для основного файла
        if ($photoReportage->file && $photoReportage->file->type === 'image') {
            $this->createWebPVersion($photoReportage->file);
        }
    }

// Метод createWebPVersion уже должен быть в контроллере, если нет - добавьте:
    private function createWebPVersion(MediaFile $mediaFile)
    {
        try {
            $originalPath = storage_path('app/public/' . $mediaFile->path);

            if (!file_exists($originalPath)) {
                return;
            }

            $image = Image::make($originalPath);

            // Генерация WebP версии
            $webpPath = pathinfo($mediaFile->path, PATHINFO_DIRNAME) . '/' .
                pathinfo($mediaFile->path, PATHINFO_FILENAME) . '.webp';

            $webpFullPath = storage_path('app/public/' . $webpPath);

            // Сохраняем WebP с качеством 80%
            $image->encode('webp', 80)->save($webpFullPath);

        } catch (\Exception $e) {
            \Log::error('WebP generation error for file ' . $mediaFile->id . ': ' . $e->getMessage());
        }
    }
    public function show($id)
    {

    }
    public function edit($id)
    {
        $photoReportage = PhotoReportage::find($id);
        $photoReportage_file = $photoReportage->file;

        return response()->json([
                                    'post' => $photoReportage,
                                    'files' => ($photoReportage_file) ? [$photoReportage_file] : [],
                                    'published_at' => $photoReportage->published_at,
                                    'lead' => $photoReportage->lead,
                                    'photo' => $photoReportage->description,
                                ]);
    }
    public function update(Request $request, $id)
    {
        /*if(!Auth::user()->hasRole('author')) {
            return response()->json([
                'error' => 'У вас нет прав для создания новостей'
            ], 403);
        }*/
        $photoReportage = PhotoReportage::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => ['required', 'max:255'],
            'lead' => ['required'],
            'description' => ['required'],
            'file_id' => ['nullable', 'exists:files,id'],
            'published_at' => ['required'],
        ]);




        $photoReportage->title = $request->title;
        $photoReportage->file_id = $request->file_id;
        $photoReportage->lead = $request->lead;
        $photoReportage->description = $request->description;
        $photoReportage->published_at = $request->published_at;

        $photoReportage->save();



        return response()->json([
                                    'success' => 'Post created.',
                                    'post' => $photoReportage
                                ]);
    }
    public function destroy($id)
    {
        $photoreportage = PhotoReportage::find($id);

        if(!$photoreportage) {
            return response()->json([
                                        'message' => '404 not found'
                                    ], 404);
        }

        $photoreportage->delete();

        return response()->json([
                                    'success' => 'Post deleted.'
                                ]);
    }
}
