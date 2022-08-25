<?php

namespace App\Http\Controllers;

use App\Models\Post; 
use Illuminate\Http\Request;
use Image;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
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

    public function upload(Request $request){
        
       if($request->hasFile('upload')){
        $image = $request->file('upload');
        $fileStorageDir = '/uploads';
        
        if (!Storage::exists($fileStorageDir)) {
            Storage::makeDirectory($fileStorageDir, 0755, true);
        } 
        
        //get filename with extension
        $originalFile = $image->getClientOriginalName();

        //get filename without extension
        $filename = pathinfo($originalFile, PATHINFO_FILENAME);

        //get file extension
        $extension = $image->getClientOriginalExtension();

        //filename to store 
        $filename = $filename."_".time().".".$extension; 

        // Store file
        $image->storeAs($fileStorageDir, $filename);  

        return response()->json([
            'result'    => 'success',
            'msg' => 'Image Upload successful',
            'filename' => $filename
        ], 200);
    }
}
 
    public function saveData(Request $request)
    {  
        $user_id = auth()->user()->id; 
        
        $request['data'] = array_merge(array('user_id' =>$user_id), $request['data']);
        if(isset($request['id'])){ 
            $data = Post::find($request['id']);
            //$data = Post::where('id' ,'=', $request['id'])->first();
            $data->update($request['data']);
            $msg = "data has been updated";
        }else{
            $data = Post::create($request['data']);
            $msg = "data has been created";
        }

        if(@$request['image']){
            $data->images()->sync([$request['image']]);
        }

        return response()->json([
            'message' => $msg
        ], 200);
    }  

    
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $Post
     * @return \Illuminate\Http\Response
     */
    public function fetch($perPage, $search, $orderBy=null)
    {
        $field = 'id';
        $sort = "desc";
      
        if($orderBy !== '-'){
            $orderBy = explode(",", $orderBy);
            $field = $orderBy[0];
            $sort = $orderBy[1];
        }
        if($search != '-'){
            $data = Post::where('title', 'LIKE', '%'.$search.'%')->
                    orWhere('content', 'LIKE', '%'.$search.'%')->
                   orderBy($field, $sort)->with('users.profile')->paginate($perPage);
        }else{

            $data = Post::orderBy($field, $sort)->with('users.profile')->paginate($perPage);
        }
        return response()->json($data, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $Post
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Post::where('id', $id)->with('images')->first();
        return response()->json($data, 200);
    }  

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $Post
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Post::where('id', $id)->first();
        $data->delete();
        return response()->json($data, 200);
    }
}
