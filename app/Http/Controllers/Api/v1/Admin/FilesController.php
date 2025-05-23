<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as ImageIntervention;
use FFMpeg;
use FFMpeg\FFProbe;

use App\Models\MediaFile;

class FilesController extends Controller
{
    protected $imageMimes = ['image/bmp', 'image/gif', 'image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'];
    protected $videoMimes = ['video/mpeg', 'video/mp4', 'video/ogg', 'video/webm', 'video/x-flv', 'video/x-msvideo'];
    protected $audioMimes = ['audio/mp4', 'audio/mpeg', 'audio/ogg', 'audio/vnd.wave', 'audio/webm'];
    protected $documents = ['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/pdf', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation'];
    protected $applicationMimes = ['application/pdf'];
    public function index(Request $request)
    {
        $query_mimes = [];
        if($request->types && in_array('image', $request->types)) {
            $query_mimes = array_merge($query_mimes, $this->imageMimes);
        }
        if($request->types && in_array('video', $request->types)) {
            $query_mimes = array_merge($query_mimes, $this->videoMimes);
        }
        if($request->types && in_array('audio', $request->types)) {
            $query_mimes = array_merge($query_mimes, $this->audioMimes);
        }
        if($request->types && in_array('documents', $request->types)) {
            $query_mimes = array_merge($query_mimes, $this->applicationMimes);
        }
        $files = new MediaFile();
        if($query_mimes) {
            $files = $files->whereIn('mime', $query_mimes);
        }
        $files = $files->orderBy('id', 'desc')->paginate(10);
        return response()->json($files);
    }
    public function create()
    {

    }
    public function store(Request $request)
    {
        //dd('mimes:' . implode(',', array_merge($this->imageMimes, $this->videoMimes, $this->audioMimes)));
        $validator = Validator::make($request->all(), [
            'upload_files' => ['required'],
            'upload_files.*' => [
                //'mimes:image/jpeg'
                'mimetypes:' . implode(',', array_merge($this->imageMimes, $this->videoMimes, $this->audioMimes, $this->documents, $this->applicationMimes))
            ],
        ], [
            'upload_files.*.*' => 'The selected files is invalid.',
        ]);

        if($validator->fails()) {
            if($validator->errors()->first('upload_files.*')) {
                $validator->errors()->add('upload_files', $validator->errors()->first('upload_files.*'));
            }
            return response()->json([
                'errors' => $validator->messages()
            ], 422);
        }

        $model_files = [];
        foreach($request->upload_files as $request_file) {
            //dd($request_file[0]);
            /*dump([
                '$request_file'=>$request_file,
                'getClientOriginalName'=>$request_file->getClientOriginalName(),
                'getClientOriginalExtension'=>$request_file->getClientOriginalExtension(),
                'getClientMimeType'=>$request_file->getClientMimeType(),
                'getClientOriginalExtension'=>$request_file->getClientOriginalExtension(),
                'extension'=>$request_file->extension(),
                'size'=>$request_file->getSize(),
            ]);*/
            $file_type = $this->getFileType($request_file->getClientMimeType());
            $file_extension = $request_file->getClientOriginalExtension();

            $uploaded_path = Storage::disk('public')->putFile('', new File($request_file));
            $uploaded_name = pathinfo($uploaded_path, PATHINFO_FILENAME);
            $uploaded_preview_path = null;
            if($file_type === 'image') {
                $uploaded_preview_path = 'previews/' . $uploaded_name . '.jpg';
                $preview = ImageIntervention::make('storage/' . $uploaded_path)
                    ->resize(240, 240, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })
                    ->encode('jpg', 100);
                Storage::disk('public')->put($uploaded_preview_path, $preview);
            } elseif($file_type === 'video') {
                $uploaded_preview_path = 'previews/' . $uploaded_name . '.jpg';
                $ffmpeg = FFMpeg\FFMpeg::create([
		 'ffmpeg.binaries'  => exec('which ffmpeg'),
    		'ffprobe.binaries' => exec('which ffprobe')
		]);
                $video = $ffmpeg->open(Storage::disk('public')->path($uploaded_path));
                $video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(2))
                    ->save(public_path('/storage/previews/') . $uploaded_name . '.jpg');
            }

            $model_file = new MediaFile();

            $model_file->user_id = Auth::user()->id;
            $model_file->type = $file_type;
            $model_file->extension = $file_extension;
            $model_file->mime = $request_file->getClientMimeType();
            $model_file->name = pathinfo($request_file->getClientOriginalName(), PATHINFO_FILENAME);
            $model_file->path = $uploaded_path;
            $model_file->preview_path = $uploaded_preview_path;
            $model_file->size = $request_file->getSize();

            $model_file->save();



            array_unshift($model_files, $model_file);
            //$model_files[] = $model_file;
        }

        return response()->json([
            'success' => 'Files uploaded.',
            'files' => $model_files
        ]);
    }
    public function show($id)
    {

    }
    public function edit($id)
    {

    }
    public function update(Request $request, $id)
    {

    }
    public function destroy($id)
    {
        $file = MediaFile::find($id);

        if(!$file) {
            return response()->json([
                'message' => '404 not found'
            ], 404);
        }
        if($file->path && Storage::disk('public')->exists($file->path)) {
            Storage::disk('public')->delete($file->path);
        }
        if(($file->type === 'image' || $file->type === 'video') && $file->preview_path && Storage::disk('public')->exists($file->preview_path)) {
            Storage::disk('public')->delete($file->preview_path);
        }
        $file->delete();

        return response()->json([
            'success' => 'File deleted.'
        ]);
    }
    protected function getFileType($mime)
    {
        if(in_array($mime, $this->imageMimes)) {
            return 'image';
        }
        if(in_array($mime, $this->audioMimes)) {
            return 'audio';
        }
        if(in_array($mime, $this->videoMimes)) {
            return 'video';
        }

        if(in_array($mime, $this->documents)) {
            return 'document';
        }

        if(in_array($mime, $this->applicationMimes)) {
            return 'application';
        }
        return 'undefined';
    }
}
