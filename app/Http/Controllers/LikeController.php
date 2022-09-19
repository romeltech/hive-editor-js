<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function postLike(Request $request){
        $user_id = auth()->id(); 

        $data = Like::where(array('post_id' => $request['post_id'], 'user_id' => $user_id)); 
        if($request['is_like'] == 0){
            $data->delete();
        }else{
            $data->create(['post_id' => $request['post_id'], 'user_id' => $user_id, 'is_like' => $request['is_like']] );
        }
    }
}