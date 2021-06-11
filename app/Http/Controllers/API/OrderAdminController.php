<?php

namespace App\Http\Controllers\API;

use App\Enums\OrderStatusType;
use App\Exports\OrderExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateOrderAdminRequest;
use App\Models\Order;
use App\Models\User;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class OrderAdminController extends Controller
{
    public function index()
    {
        $order = Order::query()->where('delivery_date', 'LIKE', '%' . request('keyword') . '%')->with(['users', 'products'])->orderBy('created_at')->get();
        return response()->json(['data' => $order]);
    }

    public function show($id)
    {
        $order = Order::with('products')->where('id', $id)->first();
        return response()->json(['data' => $order]);
    }

    public function update(UpdateOrderAdminRequest $request, $order_id)
    {
        $status=Order::query()->where('id',$order_id)->first();
        dd($status->status);
        if ($request->status === (string)OrderStatusType::WaitingForTheGoods()->value) {
            Order::query()->where('id', $order_id)->update([
                'status' => $request->status
            ]);
            return response()->json(['message' => 'Update status success. Order status is '.OrderStatusType::WaitingForTheGoods()->description]);
        } elseif ($request->status === (string)OrderStatusType::Delivering()->value) {
            Order::query()->where('id', $order_id)->update([
                'status' => $request->status
            ]);
            return response()->json(['message' => 'Update status success. Order status is '.OrderStatusType::Delivering()->description]);
        } elseif ($request->status === (string)OrderStatusType::Delivered()->value) {
            Order::query()->where('id', $order_id)->update([
                'status' => $request->status
            ]);
            return response()->json(['message' => 'Update status success. Order status is '.OrderStatusType::Delivered()->description]);
        } elseif ($request->status === (string)OrderStatusType::Cancelled()->value) {
            return response()->json(['message' => "Can't cancel order"]);
        }
        return response()->json(['message' => 'Status update failed. Check the value of status']);
    }

    public function exportCSV()
    {
        return Excel::download(new OrderExport(), 'Order.csv');
    }

    public function statistical()
    {

        $end_date = Carbon::now()->daysInMonth;
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;
        $start = $year . '/' . $month . '/1';
        $end = $year . '/' . $month . '/' . $end_date;
        $order = DB::table('order_details')
            ->join('orders', 'orders.id', '=', 'order_details.order_id')
            ->join('products', 'products.id', '=', 'order_details.product_id')
            ->join('users', 'users.id', '=', 'orders.user_id')
            ->select('products.id as id', 'products.name as product_name',
                DB::raw("sum(order_details.price)*(order_details.quantity) as price"),
                DB::raw("order_details.quantity as quantity")
                , 'users.display_name as display_name', 'orders.created_at as created_at',)
            ->where('status', OrderStatusType::Delivered())
            ->whereBetween('orders.created_at', [$start . ' 10:45:31', $end . ' 14:29:01'])
            ->groupBy('order_details.quantity', 'name', 'display_name', 'orders.created_at', 'products.id')
            ->get()->toArray();
        $test = $this->unique_multidim_array($order, 'id');
        $quantity = array_column($test, 'quantity');
        array_multisort($quantity, SORT_DESC, $test);
        $test = array_slice($test, 0, 10);
        return response()->json([
            'data' => $test
        ]);
    }

    function unique_multidim_array($array, $key)
    {
        $temp_array = array();
        $i = 0;
        $key_array = array();
        foreach ($array as $val) {
            if (!in_array($val->$key, $key_array)) {
                $key_array[$i] = $val->$key;
                $temp_array[$i] = $val;
            } else {
                $j = $this->loop_index($array, $val->$key);
                $display_name = $this->change($array, $val->id);
                $temp_array[$j]->display_name = $display_name;
                $temp_array[$j]->price += $val->price;
                $temp_array[$j]->quantity += $val->quantity;
            }
            $i++;
        }
        return $temp_array;
    }

    public function change($orders, $id)
    {
        $arr = new \stdClass();
        foreach ($orders as $order) {
            if ($order->id === $id) {
                $arr->display_name = $order->display_name;
                $arr->quantity = $order->quantity;
            }
        }
        $name = max($arr, 'quantity');
        return $name->display_name;
    }

    public function loop_index($orders, $i)
    {
        $j = 0;
        foreach ($orders as $order) {
            if ($order->id === $i) {
                return $j;
            }
            $j++;
        }
    }
}

