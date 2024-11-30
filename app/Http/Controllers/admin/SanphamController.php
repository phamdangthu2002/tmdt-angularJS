<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SanphamController extends Controller
{
    public function index()
    {
        return view('admin.san-pham.index', [
            'title' => 'Quản lý sản phẩm'
        ]);
    }

    public function show()
    {
        return view('admin.san-pham.show', [
            'title' => 'Chi tiết sản phẩm'
        ]);
    }
}