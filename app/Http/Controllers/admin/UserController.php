<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.user.index', [
            'title' => 'Thêm mới User'
        ]);
    }

    public function show()
    {
        return view('admin.user.show', [
            'title' => 'Quản lý User'
        ]);
    }
}
