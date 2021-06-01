<?php

namespace App\Http\Controllers\API;

use App\Exports\OrderExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Excel;
class OrderController extends Controller
{
    public function exportCSV()
    {
        toast('Export CSV success','success','top-right');
        return Excel::download(new OrderExport(), 'User.csv');
    }
}
