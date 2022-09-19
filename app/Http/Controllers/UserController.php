<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Access;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function getAuthenticatedUser()
    {
        $access = "";
        if(Auth::guest()){
            $access = "guest";
        }else{
            $user = auth()->user()->load(['profile','images','access']);

            if(isset($user)){ 
                $access = $user->role; 
            }
        }
        return response()->json([
            'user' => isset($user) ? $user : null,
            'user_access' => $access,
        ], 200);
    }  
   
    public function fetch($perPage, $search, $orderBy=null)
    {
        // Fetch All Users 
       
        $field = 'users.id';
        $sort = "desc";
      
        if($orderBy !== '-'){
            $orderBy = explode(",", $orderBy);
            $field = $orderBy[0];
            $sort = $orderBy[1];
        }
        if($search != '-'){
            $data = User::whereHas('profile', function ($q) use ($search) { 
                                $q->where('fullname', 'LIKE', '%'.$search.'%')->
                                orWhere('position', 'LIKE', '%'.$search.'%')->
                                orWhere('ecode', 'LIKE', '%'.$search.'%');
                        })->where('role' , '!=', 'superadmin')->
                        orWhere('email', 'LIKE', '%'.$search.'%')->
                        orWhere('username', 'LIKE', '%'.$search.'%')-> 
                        orWhere('role', 'LIKE', '%'.$search.'%')
                        ->with('profile.company', 'profile.department')
                        ->paginate($perPage);
        }else{  
                 
             $data=   User::select('users.*', 'companies.title AS company', 'departments.title AS department', 'profiles.*')
                ->join('profiles', 'profiles.user_id', '=', 'users.id')
                ->join('companies', 'companies.id', '=', 'profiles.company_id')
                ->join('departments', 'departments.id', '=', 'profiles.department_id')
                ->orderBy($field, $sort)
                ->paginate($perPage);
        }
        return response()->json($data, 200);
    }

    public function getSingleUser($id){ 
        $user = User::where('id','=', $id)->with('profile', 'images', 'access')->firstOrFail();
        
        return response()->json($user, 200);
    }

    public function updateUserStatus(Request $request)
    {
        $user = User::where('id', $request['id'])->update(['status' => $request['status']]);
        return response()->json($user, 200);
    } 

    public function saveUserData(Request $request)
    {
        $resStatus = 200;
        $userData = array();
        $profileData = array();
        
        if(isset($request['id'])){
            $user = User::where('id', $request['id'])->first();             
            $user->update($request['nUser']);
            $user->profile()->update($request['nProfile']);
            
            $msg = "User has been updated";
        }else{            
            $newData = array('password' => Hash::make('gag'));
            $newData = array_merge($newData, $request['nUser']);           
            $user = User::create($newData);

            $userID = array('user_id' => $user['id']);
            $profile = array_merge($request['nProfile'], $userID);
            
            $user->profile()->insert($profile);        
            $msg = "User has been added";
        }

        if($request['access']){
            $acc = Access::where('user_id','=',$user['id'] );
            if($acc){
                $acc->delete();
            }

            $newSlug = array();
            foreach($request['access'] AS $k => $v){
                $newSlug[] = array(
                    'user_id' => $user['id'],
                    'slug' => $v
                );
            }
          
            Access::insert($newSlug);
        }else{
            $acc = Access::where('user_id','=',$user['id'] );
            if($acc){
                $acc->delete();
            }
        }

        if(@$request['image']){
            $user->images()->sync([$request['image']]);
        }else{
            $user->images()->detach();
        }
        return response()->json([
            'message' => $msg
        ], $resStatus);
    }

    public function checkEmail(Request $request)
    {
        if($request['origEmail'] == $request['email'] && $request['origEcode'] == $request['ecode']){
            return response()->json([ 
                'email_existed' => false
            ], 200);
        }
        $user = User::where(['email' => $request['email'], 'username' => $request['ecode']])->first();
         
        return response()->json([
            'type' => gettype($user),
            'email_existed' => isset($user->email) ? true : false
        ], 200);
    }

    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'password' => 'required|confirmed|min:9',
        ]);
        $user = User::where('id', $request['id'])->first();
        if($user){
            $user->update([
                'password' => Hash::make($request['password'])
            ]);
        }
        return response()->json([
            'message' => "Password has been updated"
        ], 200);
    }
}