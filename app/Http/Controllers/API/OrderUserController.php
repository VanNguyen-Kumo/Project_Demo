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

//    public function store(CheckOutRequest $request)
//    {
//        $param = $request->validated();
//         $user_id = auth('user')->id();
//        $delivery_date = Carbon::now('Asia/Ho_Chi_Minh')->addDay(5)->toDayDateTimeString();
//        $param['user_id'] = $user_id;
//        $param['delivery_date'] = $delivery_date;
//        $order = Order::query()->create($param);
//        $this->update_address_phone($user_id,$param);
//        $detail=$request->input('order_details');
//
//        foreach ($detail as $contents){
//            $order_detail=new OrderDetail();
//            $order_detail['quantity']=$contents['quantity_order'];
//            $order_detail['price']=$contents['price'];
//            $order_detail['order_id']=$order->id;
//            $order_detail['product_id']=$contents['product_id'];
//            $order_detail->save();
//            $this->update_quantity_product($contents['product_id'],$contents['quantity_order']);
//        }
//
////        $user=User::query()->select('*')->where('id',$user_id)->first();
////        $details = [
////            'title' => 'Thank you for your trust and purchase from us',
////            'body' => 'Orders will be sent to \n'.'Name: '.$user->first_name.' '.$user->last_name.'\n'.'Address'
////        ];
//        return response()->json(['data' => $order]);
//    }
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
            $order->save();
            $this->update_address_phone($user_id,$order_request['address'],$order_request['phone']);
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

//        $user=User::query()->select('*')->where('id',$user_id)->first();
//        $details = [
//            'title' => 'Thank you for your trust and purchase from us',
//            'body' => 'Orders will be sent to \n'.'Name: '.$user->first_name.' '.$user->last_name.'\n'.'Address'
//        ];
        return response()->json(['data' => $order]);
    }
    public function cancel(Request $request,$order_id){
        $id = \auth()->guard('user')->id();
        $status_id=OrderStatusType::Cancelled();
        dd($status_id);
        $order=Order::query()->where('id',$order_id)->update($status_id);
        return request()->json(['data'=>$order,'message'=>'Cancel done']);
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
    public function show(){
        $id = request()->user('user')->id;
        $orders=Order::with('products')->where('user_id',$id)->get()->toArray();

        //     $orders=Order::with('products')->select('id','delivery_date','total_price','phone','user_id','status',)->where('user_id',$id)->get()->toArray();
//        foreach ($orders as &$order) {
//            $string = '';
//            foreach ($order['products'] as $products) {
//                $name = $products['name'];
//                $quantity = $products['pivot']['quantity'];
//                $price=$products['pivot']['price'];
//                $name_quantity = 'Name product: ' . $name . ', ' . 'Quantity:' . $quantity.', Price: '.$price;
//                $string = $string .'-'. ' ' . $name_quantity."\n";
//            }
//            $order['name_product'] = $string;
//            $order['price']=$order['total_price'];
//            $order['phone_user']=$order['phone'];
//
//            $users = User::query()->select('display_name')->where('id', $order['user_id'])->get()->toArray();
//            foreach ($users as $user) {
//                $order['user'] = $user['display_name'];
//                array_push($order);
//            }
//            $order['status_id']=OrderStatusType::Delivered()->key;
//            array_push($order);
//            array_splice($order, 3, 4);
//            array_splice($order, 2, 1);
//        }
//        dd($orders);
        return response()->json([
            'data'=>$orders
        ]);
    }
}
