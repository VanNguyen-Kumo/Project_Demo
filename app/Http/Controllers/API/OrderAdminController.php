<?php

namespace App\Http\Controllers\API;

use App\Enums\OrderStatusType;
use App\Exports\OrderExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateOrderAdminRequest;
use App\Models\Order;
use Excel;

class OrderAdminController extends Controller
{
    public function index()
    {
        $order = Order::query()->where('delivery_date', 'LIKE', '%' . request('keyword') . '%')->with(['users', 'products'])->orderBy('created_at')->get();
        return response()->json(['data' => $order]);
    }

    public function show($id)
    {
        $order = Order::query()->where('id', $id)->first();
        return response()->json(['data' => $order]);
    }

    public function update(UpdateOrderAdminRequest $request, $id)
    {
        $req = $request->only('status_id');
        $req->status_id = OrderStatusType::Cancelled();
        $order = Order::query()->where('id', $id)->update($req);
        return response()->json(['message' => 'Update success']);
    }

    public function exportCSV()
    {
        return Excel::download(new OrderExport(), 'Order.csv');
    }

    public function statistical()
    {
        $order = [];
        $count_quantity = [];
        $statisticals = Order::with('order_details')->select('*')->where('status', OrderStatusType::Delivered())->get()->toArray();
        foreach ($statisticals as $statistical) {
            foreach ($statistical['order_details'] as $sta) {
                array_push($order, $sta);
            }
        }
        $count = count($order);
        $temp = 0;
        for ($i = 0; $i < $count; $i++) {
            for ($j = $i + 1; $j < $count; $j++) {
                if ($order[$i]['product_id'] === $order[$j]['product_id']) {
                    $order[$i]['quantity'] += $order[$j]['quantity'];
                    $order[$i]['price'] += $order[$j]['price'];
                    array_push($count_quantity, $order[$i]);
                    $temp = 1;
                }
            }
//            if($temp===0){
//          //      array_push($count_quantity, $order[$i]);
//                echo $order[$i]['id']."\n";
//            }
        }
        $test = $this->unique_multidim_array($order,'product_id');
        sort($test);
    }

    function unique_multidim_array($array, $key)
    {
        $temp_array = array();
        $i = 0;
        $key_array = array();
        foreach ($array as $val) {
            if (!in_array($val[$key], $key_array, true)) {
                $key_array[$i] = $val[$key];
                $temp_array[$i] = $val;
            }
            $i++;
        }
        return $temp_array;
    }

}

