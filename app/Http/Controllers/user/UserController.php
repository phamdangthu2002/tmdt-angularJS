<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Chitietgiohang;
use App\Models\Giohang;
use App\Models\User;
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

    public function thanhtoan($id)
    {
        $user = User::find($id);  // Tìm người dùng theo ID
        $giohangs = Giohang::where('user_id', $user->id)->get();  // Lấy tất cả giỏ hàng của người dùng

        // Khởi tạo mảng chứa tất cả các chi tiết giỏ hàng
        $chitietgiohangs = collect();

        // Lặp qua từng giỏ hàng và lấy chi tiết giỏ hàng
        foreach ($giohangs as $giohang) {
            $chitietgiohang = Chitietgiohang::where('giohang_id', $giohang->id)->with('product.images')->get();
            $chitietgiohangs = $chitietgiohangs->merge($chitietgiohang);
        }
        // dd($chitietgiohang);
        return view('user.thanh-toan.index', [
            'title' => 'Thanh Toan',
            'user' => $user,
            'giohangs' => $giohangs,
            'chitietgiohangs' => $chitietgiohangs,
        ]);
    }
}
