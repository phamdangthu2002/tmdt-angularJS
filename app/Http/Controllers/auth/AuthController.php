<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function postLogin(Request $request)
    {
        // Validate dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không đúng định dạng',
            'password.required' => 'Vui lòng nhập mật khẩu',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Lấy dữ liệu từ form
        $email = $request->input('email');
        $password = $request->input('password');
        $remember = $request->has('remember'); // Kiểm tra checkbox "Remember Me"

        // Kiểm tra email và password với tùy chọn "remember me"
        if (auth()->attempt(['email' => $email, 'password' => $password], $remember)) {
            // Lấy thông tin user đã đăng nhập
            $user = auth()->user();

            // Kiểm tra role
            if ($user->role === 'user') {
                return redirect()->route('user.trangchu'); // Điều hướng đến trang người dùng
            } else if ($user->role === 'admin') {
                return redirect()->route('admin.trangchu'); // Điều hướng đến trang admin
            } else {
                auth()->logout(); // Nếu role không hợp lệ, đăng xuất
                return redirect()->back()->withErrors(['email' => 'Tài khoản không có quyền truy cập']);
            }
        } else {
            return redirect()->back()->withErrors(['email' => 'Email hoặc mật khẩu không đúng']);
        }
    }

    public function register()
    {
        return view('auth.register');
    }
    public function logout()
    {
        auth()->logout();
        return redirect()->route('auth.login');
    }
}
