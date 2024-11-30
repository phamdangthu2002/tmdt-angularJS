<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function trangchu(){
        return view('admin.trang-chu.index',[
            'title' => 'Trang chủ'
        ]);
    }
}
