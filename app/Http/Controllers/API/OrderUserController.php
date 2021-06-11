<?php

namespace App\Http\Controllers\API;

use App\Enums\OrderStatusType;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;


class OrderUserController extends Controller
{
    public function index()
    {
        $id = \auth()->guard('user')->id();
        $order = Order::query()->select('*')->where('user_id', $id)->orderBy('created_at')->with('products')->get();
        return response()->json(['data' => $order]);
    }

    public function checkout()
    {
        $id = \auth()->guard('user')->id();
        $user = User::query()->where('id', $id)->first();
        return response()->json(['data' => $user]);
    }
    public function store(Request $request)
    {
        $user_id = auth('user')->id();
        $delivery_date = Carbon::now('Asia/Ho_Chi_Minh')->addDay(5)->toDayDateTimeString();
        $order_requests=$request->input('checkoutForm');
        $order =new Order();
        foreach ($order_requests as $order_request){
            $order['total_price']=$request->input('total_price');
            $order['delivery_address']=$order_request['address'];
            $order['delivery_date']=$delivery_date;
            $order['phone']=$order_request['phone'];
            $order['user_id']=$user_id;
            $this->update_address_phone($user_id,$order_request['address'],$order_request['phone']);
            $order->save();
        }

        $detail=$request->input('itemsInCart');
        foreach ($detail as $contents){
            $order_detail=new OrderDetail();
            $order_detail['quantity']=$contents['quantity_order'];
            $order_detail['price']=$contents['price'];
            $order_detail['order_id']=$order['id'];
            $order_detail['product_id']=$contents['product_id'];
            $order_detail->save();
            $this->update_quantity_product($contents['product_id'],$contents['quantity_order']);
        }
        return response()->json(['data' => $order]);
    }
    public function cancel(Request $request,$order_id){
        $check=Order::query()->where('id',$order_id)->first();
        if ($check->status==="0"){
            $status=OrderStatusType::Cancelled()->value;
            $orders=Order::with('products')->where('id',$order_id)->get()->toArray();
            foreach ($orders as $order){
                foreach ($order['products'] as $product){
                    $products=Product::query()->select('*')->where('id',$product['pivot']['product_id'])->get()->toArray();
                    foreach ($products as &$quantity){
                        $quan= $quantity['quantity']+$product['pivot']['quantity'];
                        Product::query()->where('id',$product['pivot']['product_id'])->update([
                            'quantity'=>$quan
                        ]);
                    }
                }
            }
            $order=Order::query()->where('id',$order_id)->update([
                'status'=>(string)$status
            ]);
            return response()->json(['message'=>'Cancel order successfully']);
        }
            return response()->json(['message'=>"Can't cancel order"]);
    }
    public function update_address_phone($user_id,$address,$phone){
        $user = User::query()->find($user_id);
        $user->address =$address;
        $user->phone=$phone;
        $user->save();
    }
    public function update_quantity_product($content,$quantity){
        $product=Product::query()->find($content);
        $qty= $product->quantity;
        $product->quantity=$qty-$quantity;
        $product->save();
    }
    public function show($order_id){
        $id = \auth()->guard('user')->id();
        $orders=Order::with('products')->where('user_id',$id)->orWhere('id',$order_id)->get()->toArray();
        return response()->json([
            'data'=>$orders
        ]);
    }
}
