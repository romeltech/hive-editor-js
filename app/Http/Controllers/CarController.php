<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Jobs\ExpiryJob;
use App\Models\Incident;
use App\Models\Notification;
use Illuminate\Http\Request;

class CarController extends Controller
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

            $data = Car::where('id' ,'=', $request['data']['id'])->first();
            $data->update($request['data']);
            $msg = "data has been updated";
        }else{
            $data = Car::create($request['data']);
            $msg = "data has been created";
        }
        return response()->json([
            'message' => $msg
        ], 200);
    }

    public function saveIncidentData(Request $request)
    {  
            $data = Incident::create($request['data']);
            $msg = "data has been created";
       
        return response()->json([
            'message' => $msg
        ], 200);
    }

    public function fetchIncidents(Request $request){
       
        if($request->type){
            $where = array('type' => $request->type, 'car_id' => $request->car_id);
        }else{
            $where = array('car_id' => $request->car_id);
        }
         $data = Incident::where($where)->whereDate('created_at', '>=', $request->start_date)->whereDate('created_at', '<=', $request->end_date)->with('driver_in', 'driver_out')
         ->orderBy('id', 'DESC')->get();

        return response()->json([
            'data' => $data
        ], 200);
    }

    
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function fetch($perPage, $search, $orderBy=null)
    {
        $field = 'year';
        $sort = "asc";
      
        if($orderBy !== '-'){
            $orderBy = explode(",", $orderBy);
            $field = $orderBy[0];
            $sort = $orderBy[1];
        }
        if($search != '-'){
            $data = Car::where('plate_no', 'LIKE', '%'.$search.'%')->
                    orWhere('title', 'LIKE', '%'.$search.'%')->
                    orWhere('year', 'LIKE', '%'.$search.'%')->
                    orWhere('color', 'LIKE', '%'.$search.'%')->
                    orWhere('chassis_no', 'LIKE', '%'.$search.'%')->
                    orWhere('registration_expiry', 'LIKE', '%'.$search.'%')->
                    orWhere('km', 'LIKE', '%'.$search.'%')->orderBy($field, $sort)->paginate($perPage);
        }else{

            $data = Car::orderBy($field, $sort)->paginate($perPage);
        }
        return response()->json($data, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Car::where('id', $id)->first();
        return response()->json($data, 200);
    } 

    public function ExpiryNotification(){
        $date_expire_soon = date('Y-m-d', strtotime("+30 days"));
        $curData = date('Y-m-d');
        $expired = Car::where('status', '=', 'active')->whereDate('registration_expiry', '<=', $curData)->orWhereDate('insurance_expiry', '<=', $curData)->get();
        $expire_soon = Car::where('status', '=', 'active')->whereDate('registration_expiry', '<', $date_expire_soon)->whereDate('registration_expiry', '>', $curData)->orWhereDate('insurance_expiry', '<', $date_expire_soon)->whereDate('insurance_expiry', '>', $curData)->get();
        
        $receivers = Notification::select('email')->where('status', '=', 'active')->get();
        
        $data = array("expired" => $expired, 'expire_soon' => $expire_soon, 'receivers' => $receivers);

        ExpiryJob::dispatchAfterResponse($data);
        //return response()->json($data, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Car::where('id', $id)->first();
        $data->delete();
        return response()->json($data, 200);
    }
}
