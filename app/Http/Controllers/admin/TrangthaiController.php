<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TrangthaiController extends Controller
{
    public function trangthai()
    {
        return view('admin.trang-thai.index', [
            'title' => 'Thêm trạng thái'
        ]);
    }

    public function show()
    {
        return view('admin.trang-thai.show', [
            'title' => 'Quản lý trạng thái'
        ]);
    }
}
