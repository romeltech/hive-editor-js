<?php

namespace App\Http\Controllers;

use App\Models\image;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as Img;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    } 

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function dropzoneUpload(Request $request)
    {
        $fileArray = array();
        $uploadKey = Carbon::now()->format('YmdHis');

        $files = Collection::wrap(request()->file('file'));
         
        $userStorage = '/uploads';
        if (!Storage::exists($userStorage)) {
            Storage::makeDirectory($userStorage, 0755, true);
        }

        $files->each(function ($file, $key) use (&$userStorage, &$fileArray, &$uploadKey) {

            $userStorageDir = storage_path() . '/app' . $userStorage;
            $fileName = $file->getClientOriginalName();
            $title = pathinfo($fileName, PATHINFO_FILENAME);
            $extn = strtolower($file->getClientOriginalExtension());
            $slugTitle = Str::slug($title, '-');
            $path = $slugTitle."-".$uploadKey.".".$extn;
            $mime = $file->getClientMimeType();

            // File Optimization
            $img = Img::make($file);
            $img->encode($extn, 50); 
            // Save file to storage directory
            $img->save($userStorageDir . '/' . $path);

            // Setup data into array
            array_push( $fileArray, array(
                    'original_name' => $fileName,
                    'title' => $title,
                    'disk' => 'local',
                    'path' => $path, 
                    'mime' => $mime,
                    'user_id' => auth()->id(),
                    'created_at' => Carbon::now(),
            ));
        });

        // Insert into database at once
        $uploadedFiles = Image::insert($fileArray);

        return response()->json([
            'success' => true,
            'message' => 'Upload Success',
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\image  $image
     * @return \Illuminate\Http\Response
     */
    public function getMediaFiles(Request $request)
    {
        $files = Image::latest()->paginate(12);
        
        return response()->json($files, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\image  $image
     * @return \Illuminate\Http\Response
     */
    public function showFile($path)
    {
        $ext = explode(".", $path);
        $ext = end($ext);
        $ext = strtolower($ext);

        if($ext == 'pdf'){
            $mime_type = 'application/pdf';
        }else{
            $mime_type = 'image/'.$ext;
        }

        if( isset($path) ){
            $fileUrl = storage_path(). '/app/uploads/'.$path;
            return response()->file($fileUrl, array('Content-Type' => $mime_type));
        }else{
            return abort('403');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\image  $image
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, image $image)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\image  $image
     * @return \Illuminate\Http\Response
     */
    public function destroy(image $image)
    {
        //
    }
}
