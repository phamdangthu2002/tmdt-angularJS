<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Chitietdonhang;
use App\Models\Chitietgiohang;
use App\Models\Donhang;
use App\Models\Giohang;
use App\Models\Payment;
use App\Models\Payment_information;
use App\Models\User;
use Carbon\Carbon;
use FFI\Exception;
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
        $giohangs = Giohang::where('user_id', $user->id)->where('trangthai', 'Chưa đặt hàng')->get();  // Lấy tất cả giỏ hàng của người dùng

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

    // public function payment(Request $request, $id)
    // {
    //     $name = $request->input('name');
    //     $email = $request->input('email');
    //     $phone = $request->input('phone');
    //     $diachi = $request->input('diachi');
    //     $price = $request->input('price');
    //     $sanpham_id = $request->input('sanpham_id');
    //     $quantity = $request->input('quantity');
    //     $trangthai_id = 1;
    //     $phuongthuc = $request->input('phuongthuc');
    //     // dd($name, $email, $phone, $diachi, $price, $sanpham_id, $quantity, $phuongthuc);
    //     $user = User::find($id);
    //     if (!$user) {
    //         return redirect()->back()->with('error', 'Người dùng không tồn tại.');
    //     }
    //     $user->update([
    //         'name' => $name,
    //         'email' => $email,
    //         'phone' => $phone,
    //         'diachi' => $diachi,
    //     ]);

    //     try {
    //         $donhang = Donhang::create([
    //             'user_id' => $id,
    //             'trangthai_id' => $trangthai_id,
    //             'price' => $price,
    //             'ngaydathang' => Carbon::now(),
    //         ]);
    //     } catch (\Exception $e) {
    //         return redirect()->back()->with('error', 'Không thể tạo đơn hàng: ' . $e->getMessage());
    //     }


    //     $chitietdonhang = Chitietdonhang::create([
    //         'donhang_id' => $donhang->id,
    //         'sanpham_id' => $sanpham_id,
    //         'quantity' => $quantity,
    //         'price' => $price,
    //     ]);
    //     $thanhtoan = Payment::create([
    //         'donhang_id' => $donhang->id,
    //         'phuongthuc' => $phuongthuc,
    //         'tong' => $price,
    //     ]);
    //     $thongtinthanhtoan = Payment_information::create([
    //         'user_id' => $user->id,
    //         'phuongthuc' => $phuongthuc,
    //         'tong' => $price,
    //     ]);
    //     $giohang = Giohang::where('user_id', $user->id)->first();
    //     if ($giohang) {
    //         $giohang->update(['trangthai' => 'Đã đặt hàng']);
    //     } else {
    //         return redirect()->back()->with('error', 'Giỏ hàng không tồn tại.');
    //     }
    //     return redirect()->route('user.trangchu')->with('success', 'Thanh toán thành công');
    // }

    public function payment(Request $request, $id)
    {
        // Lấy thông tin từ request
        $name = $request->input('name');
        $email = $request->input('email');
        $phone = $request->input('phone');
        $diachi = $request->input('diachi');
        $price = $request->input('price');
        $sanpham_ids = $request->input('sanpham_id', []);  // Nhận mảng các ID sản phẩm
        $quantities = $request->input('quantity', []);     // Nhận mảng các số lượng sản phẩm
        $phuongthuc = $request->input('phuongthuc');
        $trangthai_id = 1;

        // Kiểm tra xem các mảng có đủ phần tử không
        if (count($sanpham_ids) != count($quantities)) {
            return redirect()->back()->with('error', 'Số lượng sản phẩm và số lượng không khớp.');
        }

        // In thông tin ra màn hình để kiểm tra
        // dd($name, $email, $phone, $diachi, $price, $sanpham_ids, $quantities, $phuongthuc);

        // Kiểm tra người dùng có tồn tại hay không
        $user = User::find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'Người dùng không tồn tại.');
        }

        // Cập nhật thông tin người dùng
        $user->update([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'diachi' => $diachi,
        ]);

        // Tạo đơn hàng
        try {
            $donhang = Donhang::create([
                'user_id' => $id,
                'trangthai_id' => $trangthai_id,
                'price' => $price,
                'ngaydathang' => Carbon::now(),
            ]);

            // Tạo chi tiết đơn hàng cho mỗi sản phẩm
            foreach ($sanpham_ids as $index => $sanpham_id) {
                if (isset($quantities[$index])) {
                    // Tính giá theo sản phẩm và số lượng
                    $productPrice = $price;  // Nếu có nhiều loại giá, tính toán giá riêng ở đây

                    // Tạo chi tiết đơn hàng
                    Chitietdonhang::create([
                        'donhang_id' => $donhang->id,
                        'sanpham_id' => $sanpham_id,
                        'quantity' => $quantities[$index],  // Sử dụng số lượng tương ứng với sản phẩm
                        'price' => $productPrice * $quantities[$index],  // Tính tổng tiền cho sản phẩm này
                    ]);
                }
            }

            // Tạo thông tin thanh toán
            Payment::create([
                'donhang_id' => $donhang->id,
                'phuongthuc' => $phuongthuc,
                'tong' => $price,  // Cần tính lại tổng nếu có nhiều sản phẩm
            ]);

            Payment_information::create([
                'user_id' => $user->id,
                'phuongthuc' => $phuongthuc,
                'tong' => $price,  // Tính tổng giá trị đơn hàng nếu cần
            ]);

            // Cập nhật trạng thái giỏ hàng
            $giohang = Giohang::where('user_id', $user->id)->latest()->first();
            if ($giohang) {
                $giohang->update(['trangthai' => 'Đã đặt hàng']);
            }

            // Chuyển hướng kèm thông báo thành công
            return redirect()->route('user.trangchu')->with('success', 'Thanh toán thành công!');
        } catch (Exception $e) {
            // Xử lý nếu có lỗi xảy ra
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }


}
