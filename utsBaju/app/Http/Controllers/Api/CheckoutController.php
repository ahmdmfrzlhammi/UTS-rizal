<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AddToCart;
use App\Models\Checkout;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request->all());        

        $request->validate([
            'cart_id'=>'required | exists:add_to_cart,id',
            'metode_pembayaran'=> 'required',
            'metode_pengiriman'=>'required|in:COD,JNE,Sicepat',
            'alamat'=> 'required',
        ]);
        $biaya_pengiriman = [
            'COD'=> 0,
            'JNE'=> 25000,
            'sicepat'=> 3000,
        ];

        DB::beginTransaction();
        
        $cartsQuery= AddToCart::where('customer_id' , auth()->user()->id)
        ->whereNull('checkout_id')
        ->whereIn('id', $request->cart_id)
        ->with('product');
        //->get();
        $carts = $cartsQuery->get();
        
        if($carts->count() == 0) {
            $response['message'] = 'cart not found';
            $response['Success'] = false;
            return response()->json($response, 404);
        }
        $total_harga_product = 0;
        foreach ($carts as $cart) {
            if($cart->product->stock < $cart->qty){
                $response['message'] = 'Kosong dara:' . ($cart->qty) . ',Stok tersedia:' . ($cart->product->qty) . ' . ('.$cart->product->name.')';
                $response['success'] = false;
                return response()->json($response);
            }

            $total_harga_product += $cart->total_harga;

        }

        foreach ($carts as $cart) 
        {
            # code...
            $cart->product->decrement('stock' , $cart->qty );
        }
        //simpan data checkout
        $checkout = new Checkout();
        $checkout->customer_id = auth()->user()->id;
        $checkout->total_harga_product = $total_harga_product;
        $checkout->biaya_pengiriman = $biaya_pengiriman
        [$request->metode_pengiriman];
        $checkout->metode_pembayaran = $request->metode_pembayaran;
        $checkout->metode_pengiriman = $request->metode_pengiriman;
        $checkout->alamat = $request->alamat;
        $checkout->save();

        $cartsQuery->update([
            'checkout_id'=> $checkout->id,
        ]);
        DB::commit();

        $response['data'] = $checkout;
        $response['success'] = true;
        $response['message'] = 'berhasil';
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
