<?php

namespace App\Http\Controllers;

use App\Models\department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
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

    public function fetch($perPage, $search, $orderBy=null)
    {
        $field = 'title';
        $sort = "asc";
      
        if($orderBy !== '-'){
            $orderBy = explode(",", $orderBy);
            $field = $orderBy[0];
            $sort = $orderBy[1];
        }
        
        if($search != '-'){
            $data = Department::where('title', 'LIKE', '%'.$search.'%')
            ->orderBy($field, $sort)->paginate($perPage);
        }else{ 
            $data = Department::orderBy($field, $sort)->paginate($perPage);
        }
        return response()->json($data, 200);
    }

    public function fetchNonpaginate()
    { 
        $data = Department::orderBy('title', 'asc')->get();
         
        return response()->json($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\department  $department
     * @return \Illuminate\Http\Response
     */
    public function show(department $department)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\department  $department
     * @return \Illuminate\Http\Response
     */
    public function edit(department $department)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\department  $department
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, department $department)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy(department $department)
    {
        //
    }
}
