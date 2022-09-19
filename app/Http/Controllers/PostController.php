<?php

namespace App\Http\Controllers;

use Image; 
use App\Models\Post;  
use App\Models\Comment;
use App\Models\PollAnswer;
use App\Models\PollChoice;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
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

    public function submitPollAnswer(Request $request){
        $user_id = auth()->id(); 
        $newData = $request['data'];
         
        if(@$newData['content']){
            $nData = ['post_id' => $newData['post_id'], 'user_id' => $user_id, 'content' => @$newData['content']];
        }else{
            $nData = ['post_id' => $newData['post_id'], 'user_id' => $user_id, 'poll_choice_id' => @$newData['poll_choice_id']];
        }
        PollAnswer::updateOrCreate(
            ['post_id' => $newData['post_id'], 'user_id' => $user_id],
            $nData);
    } 
 
    public function saveData(Request $request)
    {   
       
        $user_id = auth()->id(); 
        $data = NULL;
        $msg = "Error: kindly refresh the page/ contact administrator";
        $responseCode = 200;
        DB::beginTransaction();
        // do all your updates here
        try {

        $request['data'] = array_merge(array('user_id' =>$user_id), $request['data']);
        if(isset($request['id'])){ 
            $data = Post::find($request['id']); 
            $data->update($request['data']);             
        }else{
            $data = Post::create($request['data']); 
        }
         
        if(@$request['image']){
            $data->images()->sync([$request['image']]);
        }else{
            $data->images()->detach();
        }

        if(@$request['categories']){
            $data->categories()->sync($request['categories']);
        }else{
            $data->categories()->detach();
        }

        if(@$request['daterange']){
            $data->events()->delete();
            $data->events()->create(['date_start' => $request['daterange']['date_start'],
            'date_end' => $request['daterange']['date_end']]);
        }
       
        if(@$request['choices']){
            foreach ($request['choices'] as $k => $v) {
              
               PollChoice::updateOrCreate(
                ['id' => @$v['id'], 'post_id' => $data['id']],
                ['post_id' => $data['id'], 'content' => $v['content'] ] );
            } 
        }
            $msg = "data has been updated/created";
            DB::commit();  
                    
        } catch (\Exception $e) {
            DB::rollback();  
            $msg = $e;
            $responseCode = 500;
        }

        return response()->json([
            'result' => $data,
            'message' => $msg
        ], $responseCode);
    }  

    
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $Post
     * @return \Illuminate\Http\Response
     */
    public function fetch($posttype, $perPage, $search, $orderBy=null)
    {
        $field = 'id';
        $sort = "desc";
      
        if($orderBy !== '-'){
            $orderBy = explode(",", $orderBy);
            $field = $orderBy[0];
            $sort = $orderBy[1];
        }
        
        if($search != '-'){
            $data = Post::where('type', '=' , $posttype)->where(function($query) use ($search){
                $query->where('title', 'LIKE', '%'.$search.'%')
                ->orWhere('content', 'LIKE', '%'.$search.'%');
            })->orderBy($field, $sort)->with('users.profile','events', 'categories')->paginate($perPage);
        }else{ 
            $data = Post::where('type', '=' , $posttype)->orderBy($field, $sort)->with('users.profile','images','events', 'categories')->paginate($perPage);
        }
        return response()->json($data, 200);
    }

    public function fetchFrontend($perPage, $search, $orderBy=null)
    {
        $field = 'id';
        $sort = "desc";
      
        if($orderBy !== '-'){
            $orderBy = explode(",", $orderBy);
            $field = $orderBy[0];
            $sort = $orderBy[1];
        } 
       
        $data = Post::where('status','=','active')->with(['users.profile','images','events', 'categories', 'poll_choices','likes' => function($q){
            $q->where('user_id', auth()->id());
        }, 'comments' => function($q){
            $q->where('status', 'active');
        } , 'poll_answer' => function($q){
            $q->where('user_id', auth()->id());
        }])->withCount('likes')->orderBy('updated_at', 'DESC')->paginate($perPage);
        
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
        $data = Post::where('id', $id)->with(['images', 'categories','events', 'poll_choices', 'poll_answer.profile', 'poll_answer.choices', 'comments.profile' => function($q){
            $q->orderBy("id", "DESC");
        }])->withCount('poll_answer')->first();
       
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
