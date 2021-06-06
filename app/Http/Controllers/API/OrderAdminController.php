<?php

namespace App\Http\Controllers\API;

use App\Enums\OrderStatusType;
use App\Exports\OrderExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateOrderAdminRequest;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
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
        $order=[];
        $statisticals=  Order::with('order_details')->select('*')->where('status', OrderStatusType::Delivered())->get()->toArray();
        foreach ($statisticals as $statistical){
            foreach ($statistical['order_details'] as $sta) {
//               if($sta['product_id']==='c0687640-b789-11eb-bc20-65f51afca8c5'){
//                   array_push($order,$sta);
//               }
                echo $sta['product_id']."\n";
            }
        }
      //  dd($order);
        }

}
