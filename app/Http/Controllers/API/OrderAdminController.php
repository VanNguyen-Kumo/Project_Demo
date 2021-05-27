<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateOrderAdminRequest;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderAdminController extends Controller
{
    public function index(){
        $order=Order::query()->where('delivery_date', 'LIKE', '%' . request('keyword') . '%')->with('users')->orderBy('created_at')->get();
        return response()->json(['data'=>$order]);
    }
    public function show($id){
        $order=Order::query()->where('id',$id)->first();
        return response()->json(['data'=>$order]);
    }
    public function update(UpdateOrderAdminRequest $request,$id){
        $req=$request->only('status');
        $order=Order::query()->where('id',$id)->update($req);
        return response()->json(['data'=>'Update success']);
    }
}
