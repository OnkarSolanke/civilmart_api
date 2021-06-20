<?php

namespace App\Http\Controllers;

use App\Address;
use App\order;
use App\User;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $auth_user = Auth::user();
        if(isset($auth_user)){
            $orders = null;
            if($auth_user->type == 'ADMIN'){
                $orders = Order::with('product','address','vendor','customer')->get();
            }else{
                $orders =  $orders = Order::with('product','address','vendor','customer')->where(['vendor_id' => $auth_user->id])->get();
            }
            return response()->json(['status' => 'success','message' => 'Data Fetched', 'orders' => $orders],200);
        }
        return response()->json(['status' => 'failed','message' => 'Unathorized'],403);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $auth_user = Auth::user();
        if(isset($auth_user)){
            $order =  $request->get('order');
            $product = $order['product'];
            if(isset($product)){
                DB::beginTransaction();
                try {
                    
                    $user = User::find($auth_user->id);
                    $user->name = $order['customerName'];
                    $user->save();
                    
                    $addres = Address::create([
                        'user_id' => $auth_user->id,
                        'address' => $order['address'],
                    ]);
                    
                    Order::create([
                        'user_id' => $auth_user->id,
                        'address_id' => $addres->id,
                        'product_id' => $product['id'],
                        'vendor_id' => $product['user_id'],
                        'quantity_required' => $order['requiredQty'],
                        'quantity_Details'  => $order['detailrequiredQty'],
                    ]);   
                    DB::commit();
                    return response()->json(['status' => 'success','message' => 'Order Placed'],200);
                } catch (\Throwable $th) {
                    DB::rollback();
                    logger($th);
                }
            }
            return response()->json(['status' => 'failed','message' => 'Something Went Wrong'],500);
        }
        return response()->json(['status' => 'failed','message' => 'Unathorized'],403);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(order $order)
    {
        //
    }

    public function orderChangeStatus(Request $request, $order_id, $status){
        $order = Order::find($order_id);
        if(isset($order)){
            $order->status = $status;
            $order->save();
            return response()->json(['status' => 'Success','message' => 'Action Taken Successfully'],200);
        }
        return response()->json(['status' => 'failed','message' => 'Order Not Found for ID'],404);
    }
}
