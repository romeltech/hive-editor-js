<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
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
            $data = Company::where('title', 'LIKE', '%'.$search.'%')
            ->orderBy($field, $sort)->paginate($perPage);
        }else{ 
            $data = Company::orderBy($field, $sort)->paginate($perPage);
        }
        return response()->json($data, 200);
    }

    public function fetchNonpaginate()
    { 
        $data = Company::orderBy('title', 'asc')->get();
         
        return response()->json($data, 200);
    }

     
 
}
