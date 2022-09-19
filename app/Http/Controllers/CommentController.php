<?php

namespace App\Http\Controllers;

use App\Models\comment;
use Illuminate\Http\Request;

class CommentController extends Controller
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
    
    public function submitComment(Request $request){
        $user_id = auth()->id(); 
        $newData = $request['data']; 
        
        $nData = ['post_id' => $newData['post_id'], 'user_id' => $user_id, 'content' => $newData['content']];
         
        Comment::create($nData);

        return response()->json([ 
            'message' => 'Comment has been submitted'
        ], 200 );
    }
    
    public function update(Request $request)
    {
        $data = Comment::find($request['comment']['id']);
        $data->update(array('status' =>$request['status']));
    } 
}
