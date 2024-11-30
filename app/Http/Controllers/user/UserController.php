<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('user.trang-chu.index', [
            'title' => 'Home'
        ]);
    }

    public function danhmuc()
    {
        return view('user.danh-muc.index', [
            'title' => 'Danh Muc'
        ]);
    }
}
