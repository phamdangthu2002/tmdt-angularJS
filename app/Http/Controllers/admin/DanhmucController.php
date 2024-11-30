<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DanhmucController extends Controller
{
    public function index()
    {
        return view('admin.danh-muc.index', [
            'title' => 'Danh Mục',
        ]);
    }
    public function show()
    {
        return view('admin.danh-muc.show', [
            'title' => 'Quản lý danh mục',
        ]);
    }
    public function update()
    {
        return view('admin.danh-muc.update', [
            'title' => 'Cập nhật danh mục',
        ]);
    }
}
