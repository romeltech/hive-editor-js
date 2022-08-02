<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
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

 
    public function saveData(Request $request)
    {   
        if(isset($request['data']['id'])){

            $data = Notification::where('id' ,'=', $request['data']['id'])->first();
            $data->update($request['data']);
            $msg = "data has been updated";
        }else{
            $data = Notification::create($request['data']);
            $msg = "data has been created";
        }
        return response()->json([
            'message' => $msg
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Notification  $car
     * @return \Illuminate\Http\Response
     */
    public function fetch($perPage, $search)
    {
        if($search != '-'){
            $data = Notification::where('fullname', 'LIKE', '%'.$search.'%')->
                    orWhere('email', 'LIKE', '%'.$search.'%')
                    ->orderBy('fullname', 'asc')->paginate($perPage);
        }else{ 
            $data = Notification::orderBy('fullname', 'asc')->paginate($perPage);
        }
        return response()->json($data, 200);
    }

    public function fetchAll(){
        
        $data = Notification::where('status', 'active')->orderBy('fullname', 'asc')->get();
       
        return response()->json($data, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Notification  $car
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    { 
        
        $data = Notification::where('id', '=',$id)->first();
        return response()->json($data, 200);
    }

 

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notification $car)
    {
        //
    }
}
