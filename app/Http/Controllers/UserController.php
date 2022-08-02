<?php

namespace App\Http\Controllers;

use App\Models\User;
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
            $user = User::where('id', auth()->user()->id)->first();

            if(isset($user)){
                if($user->role == 'admin'){
                    $access = "admin";
                }else{
                    $access = "staff";
                }
            }
        }
        return response()->json([
            'user' => isset($user) ? $user : null,
            'user_access' => $access,
        ], 200);
    }

    public function getAllUsers($perPage, $search)
    {
        // Fetch All Users 
        
        if($search != '-'){
            $data = User::where('role' , '!=', 'superadmin')->where('full_name', 'LIKE', '%'.$search.'%')->
                    orWhere('phone', 'LIKE', '%'.$search.'%')->
                    orWhere('email', 'LIKE', '%'.$search.'%')->
                    orWhere('role', 'LIKE', '%'.$search.'%')
                    ->orderBy('full_name', 'asc')->paginate($perPage);
        }else{ 
            $data = User::where('role' , '!=', 'superadmin')->orderBy('role', 'asc')->paginate($perPage);
        }
        return response()->json($data, 200);
    }

    public function getSingleUser($id)
    {
        $user = User::where('id', $id)->firstOrFail();
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
        $arrDetail = array();
        if(isset($request['id'])){
            if(isset($request['email'])){
                $arrDetail = array(
                    'email' => $request['email'],
                    'full_name' => $request['full_name'],
                    'phone' => $request['phone'],
                    'role' => $request['role'],
                    'status' => $request['status']
                );
            }else{
                $arrDetail = array(
                    'full_name' => $request['full_name'],
                    'phone' => $request['phone'],
                    'role' => $request['role'],
                    'status' => $request['status'],
                );
            }
            $user = User::where('id', $request['id'])->first();
            $user->update($arrDetail);
            $msg = "User has been updated";
        }else{
            $password = Hash::make($request['password']);
            $arrDetail = array(
                'email' => $request['email'],
                'full_name' => $request['full_name'],
                'password' => $password,
                'phone' => $request['phone'],
                'role' => $request['role'],
                'status' => 'active',
            );
            $users = User::create($arrDetail);
            $msg = "User has been added";
        }
        return response()->json([
            'message' => $msg
        ], $resStatus);
    }

    public function checkEmail(Request $request)
    {
        $user = User::where('email', $request['email'])->first();
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
