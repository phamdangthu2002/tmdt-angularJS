<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DonhangController extends Controller
{
    public function index(){
        return view('admin.don-hang.index',[
            'title' => 'Đơn hàng',
        ]);
    }
}
