<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
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
            $data = Category::where('title', 'LIKE', '%'.$search.'%')
            ->orderBy($field, $sort)->paginate($perPage);
        }else{ 
            $data = Category::orderBy($field, $sort)->paginate($perPage);
        }
        return response()->json($data, 200);
    }

    public function fetchNonpaginate()
    { 
        $data = Category::orderBy('title', 'asc')->get();
         
        return response()->json($data, 200);
    }

    public function edit($id)
    {
        $data = Category::where('id', $id)->firstOrFail();
        return response()->json($data, 200);
    }
}
