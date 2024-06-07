<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\updateProductsRequest;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\updateProductRequest;

use App\Models\Product;
use App\Models\Products;
use Exception;
use GuzzleHttp\Psr7\Message;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = $this->default_response;
        try{
        $product = Product::all();

        $response['Success']=true;
        $response['data']=[
            'products'=>$product
        ];
    }catch(Exception $e){
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
    public function store(StoreProductRequest $request)
    {
        $response = $this->default_response;
        try{
        $data = $request->validated();
            if($request->hasFile('image')){
                $file = $request->file('image');
                $path = $file->storeAs('project-image' , $file->hashName() ,'public');
            }
        $product = new Product();
       $product->name = $data['name'];
       $product->description = $data['description'];
       $product->stock = $data['stock'];
       $product->price = $data['price'];
       $product->image = $path ?? null;
       $product->category_id = $data['category_id'];
       $product->save();

       $response['success'] = 'true';
       $response['data']=[
        'product'=>$product->with('category')->find($product->id),
       ] ;
        
    }catch(Exception $e){
        $response['message']=$e->getMessage();

    }
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $response = $this->default_response;
        try{
        $Products = Product::with('category')->find( $id );

        $response['Success']=true;
        $response['data']=[
            'Products'=>$Products,
        ];
    }catch(Exception $e){
        $response['message']=$e->getMessage();

    }
        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, string $id)
    {
        $response = $this->default_response;
        try{
        $data = $request->validated();
            if($request->hasFile('image')){
                $file = $request->file('image');
                $path = $file->storeAs('project-image' , $file->hashName() ,'public');
            }
        $product = product::find($id);
       $product->name = $data['name'];
       $product->description = $data['description'];
       $product->stock = $data['stock'];
       $product->price = $data['price'];
       if($request->hasFile('image')) {
        if($product->image) Storage::disk('public')->delete($product->image);
        $product->image = $path ?? null;
    }
       $product->category_id = $data['category_id'];
       $product->save();

       $response['success'] = 'true';
       $response['data']=[
        'product'=>$product->with('category')->find($product->id),
       ] ;
        
    }catch(Exception $e){
        $response['message']=$e->getMessage();

    }
        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $response = $this->default_response;
        try {
            $product = product::find($id);
            $product->delete();
            $response['success'] = true;
            $response['message'] = 'Product deleted success';
        } catch (\Exception $e) {
            $response['message'] = $e->getMessage();
        }
        return response()->json($response);
    }
    }

