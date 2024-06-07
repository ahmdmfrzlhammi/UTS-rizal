<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

use GrahamCampbell\ResultType\Success;
use GuzzleHttp\Psr7\Message;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = $this->default_response;
        try{
        $category = Category::all();

        $response['Success']=true;
        $response['data']=[
            'category'=>$category,
        ];
    }catch(\Exception $e){
        $response['message']=$e->getMessage();

    }
        return response()->json($response);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $response = $this->default_response;
        try{
            $data = $request->validated();

            $category = new Category();
            $category->name = $data['name'];
            $category->description = $data['description'];
            $category->save();  

            $response['success']=true;
            $response['data']=[
                'category'=>$category,
                ];
                $response['Message'] = "Category created Successfully";
    }catch(\Exception $e){

        $response["message"]=$e->getMessage();
    }
    return response()->json($response);
}

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        
        $response = $this->default_response;
        try{
            $category = Category::find($id);

            $response['Success']=true;
            $response['message']= "Get Category Success";
            $response['data']=[
                'Category'=> $category,
                ];
        }catch(\Exception $e){
            $response['message']=$e->getMessage();
    };
    return response()->json($response);
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, String $id)
    {
        $response = $this->default_response;
        try{
            $data = $request->validated();

            $category = Category::find($id);
            $category->name = $data['name'];
            $category->description = $data['description'];
            $category->save();  

            $response['success']=true;
            $response['data']=[
                'category'=>$category,
                ];
                $response['Message'] = "Category created Successfully";
    }catch(\Exception $e){

        $response["message"]=$e->getMessage();
    }
    return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        $response = $this->default_response;
        try{
            $category = Category::find($id);
            $category->delete();
            $response['success']=true;
            $response['message']='Category Deleted SuccessFully';

    }catch(\Exception $e){
        $response['message']=$e->getMessage();
    }
    return response()->json($response);
}
}