<?php

namespace App\Exports;

use App\Enums\OrderStatusType;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrderExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function headings(): array
    {
        return [
            'ID',
            'Delivery_Date',
            'Product',
            'Total Price',
            'Phone',
            'User',
            'Status',
        ];
    }

    public function collection()
    {
        $orders = Order::getOrder();
        foreach ($orders as &$order) {
            $string = '';
            foreach ($order[' '] as $order_detail) {
                $product=Product::query()->select('name')->where('id',$order_detail['product_id'])->get()->toArray();
                $name = $product[0]['name'];

                $quantity = $order_detail['quantity'];
                $price=$order_detail['price'];
                $name_quantity = 'Name product: ' . $name . ', ' . 'Quantity:' . $quantity.', Price: '.$price;
                $string = $string .'-'. ' ' . $name_quantity;
                $string=$string."\n";
            }
            $order['name_product'] = $string;
            $order['price']=$order['total_price'];
            $order['phone_user']=$order['phone'];


            $users = User::query()->select('display_name')->where('id', $order['user_id'])->get()->toArray();
            foreach ($users as $user) {
                $order['user'] = $user['display_name'];
                array_push($order);
            }
            $order['status_id']=OrderStatusType::Delivered()->key;
            array_push($order);
            array_splice($order, 3, 4);
            array_splice($order, 2, 1);

        }
        return collect($orders);
    }
}
