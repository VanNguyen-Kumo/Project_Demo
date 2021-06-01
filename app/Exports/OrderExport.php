<?php

namespace App\Exports;

use App\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrderExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings():array{
        return [
            'ID',
            'Delivery_Date',
            'Total Quantity',
            'Total Price',
            'Token Key'
        ];
    }
    public function collection()
    {
        // return User::all();
        return collect(User::getUser());
    }
}
