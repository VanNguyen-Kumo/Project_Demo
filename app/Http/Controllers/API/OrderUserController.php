<?php

namespace App\Http\Controllers\API;

use App\Enums\OrderStatusType;
use App\Http\Controllers\Controller;
use App\Http\Requests\CheckOutRequest;
use App\Http\Requests\UpdateOrderUserRequest;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


class OrderUserController extends Controller
{
    public function index()
    {
        $id = request()->user('user')->id;
        $order = Order::query()->select('*')->where('user_id', $id)->orderBy('created_at')->with('products')->get();
        return response()->json(['data' => $order]);
    }

    public function checkout()
    {
        $id = request()->user('user')->id;
        $user = User::query()->where('id', $id)->first();
        return response()->json(['data' => $user]);
    }

    public function store(CheckOutRequest $request)
    {
        $param = $request->validated();
         $user_id = auth('user')->id();
        $delivery_date = Carbon::now('Asia/Ho_Chi_Minh')->addDay(5)->toDayDateTimeString();
        $param['user_id'] = $user_id;
        $param['delivery_date'] = $delivery_date;
        $order = Order::query()->create($param);
        $this->update_address_phone($user_id,$param);
        $detail=$request->input('order_detail');

        foreach ($detail as $contents){
            $order_detail=new OrderDetail();
            $order_detail['quantity']=$contents['quantity'];
            $order_detail['price']=$contents['price'];
            $order_detail['order_id']=$order->id;
            $order_detail['product_id']=$contents['product_id'];
            $order_detail->save();
            $this->update_quantity_product($contents['product_id'],$contents['quantity']);
        }

//        $user=User::query()->select('*')->where('id',$user_id)->first();
//        $details = [
//            'title' => 'Thank you for your trust and purchase from us',
//            'body' => 'Orders will be sent to \n'.'Name: '.$user->first_name.' '.$user->last_name.'\n'.'Address'
//        ];
        return response()->json(['data' => $order]);
    }
    public function cancel(UpdateOrderUserRequest $request, $id){
        $req=$request->validated();
        $req->status_id=OrderStatusType::Cancelled();
        $order=Order::query()->where('id',$id)->update($req);
        return request()->json(['data'=>$order,'message'=>'Cancel done']);
    }
    public function update_address_phone($user_id,$param){
        $user = User::query()->find($user_id);
        $user->address =$param['delivery_address'];
        $user->phone=$param['phone'];
        $user->save();
    }
    public function update_quantity_product($content,$quantity){
        $product=Product::query()->find($content);
        $qty= $product->quantity;
        $product->quantity=$qty-$quantity;
        $product->save();
    }
}
