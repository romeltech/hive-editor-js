<?php

namespace App\Http\Controllers;

use App\Models\Car;
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function fetch()
    {
        $data = Car::orderBy('year', 'asc')->paginate(10);
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

 

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function destroy(Car $car)
    {
        //
    }
}
