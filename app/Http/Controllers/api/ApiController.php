<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Anhsanpham;
use App\Models\Category;
use App\Models\Chitietdonhang;
use App\Models\Chitietgiohang;
use App\Models\Donhang;
use App\Models\Giohang;
use App\Models\Payment;
use App\Models\Payment_info;
use App\Models\Payment_information;
use App\Models\Sanpham;
use App\Models\Trangthai;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use JWTAuth; // Nếu bạn dùng JWT, hoặc auth nếu dùng Auth thông thường

class ApiController extends Controller
{
    public function upload(Request $request)
    {
        try {
            // Kiểm tra xem có tệp tin nào được tải lên không
            if ($request->hasFile('file')) {
                $name = $request->file('file')->getClientOriginalName();
                $pathFull = 'uploads/' . date('Y/m/d');

                // Lưu tệp tin vào thư mục public
                $request->file('file')->storeAs(
                    'public/' . $pathFull,
                    $name
                );

                // Trả về URL của tệp tin đã được tải lên
                $url = '/storage/' . $pathFull . '/' . $name;

                return response()->json([
                    'error' => false,
                    'url' => $url
                ]);
            } else {
                // Nếu không có tệp tin nào được tải lên
                return response()->json([
                    'error' => true,
                    'message' => 'No file uploaded'
                ]);
            }
        } catch (Exception $e) {
            // Nếu có lỗi trong quá trình tải lên
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }
    }


    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed', // Xác nhận mật khẩu
        ], [
            'name.required' => 'Vui lòng nhập tên',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email đã tồn tại',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu ít nhất 8 ký tự',
            'password.confirmed' => 'Mật khẩu không khớp',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
        return response()->json(['message' => 'Đăng ký thành công'], 200);

    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Đăng xuất thành công']);
    }


    public function add(Request $request)
    {
        try {
            // Sử dụng Validator để xác thực dữ liệu đầu vào
            $validator = Validator::make(
                $request->all(),
                [
                    'name' => 'required|string|max:255',
                    'description' => 'nullable|string',
                    'status' => 'required|in:active,inactive',
                ],
                [
                    'name.required' => 'Tên danh mục là bắt buộc.',
                    'name.string' => 'Tên danh mục phải là chuỗi.',
                    'name.max' => 'Tên danh mục không được quá 255 ký tự.',
                    'status.required' => 'Trạng thái là bắt buộc.',
                    'status.in' => 'Trạng thái phải là "active" hoặc "inactive".',
                ]
            );

            // Nếu xác thực không thành công, trả về lỗi
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            // Tạo đối tượng danh mục mới
            $category = new Category();
            $category->name = $request->input('name');
            $category->description = $request->input('description');
            $category->trangthai = $request->input('status');

            // Lưu danh mục vào cơ sở dữ liệu
            $category->save();

            // Trả về phản hồi thành công
            return response()->json(['message' => 'Danh mục đã được thêm thành công!', 'data' => $category], 201);
        } catch (Exception $e) {
            // Xử lý lỗi và trả về thông báo lỗi
            return response()->json(['error' => 'Đã xảy ra lỗi: ' . $e->getMessage()], 500);
        }
    }

    public function all()
    {
        try {
            // Lấy danh sách danh mục từ cơ sở dữ liệu
            $categories = Category::all();
            return response()->json(['data' => $categories], 200);
        } catch (Exception $e) {
            // Xử lý lỗi và trả về thông báo lỗi
            return response()->json(['error' => 'Đã xảy ra lỗi: ' . $e->getMessage()], 500);
        }
    }
    public function get($id)
    {
        // Lấy danh mục theo ID
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['error' => 'Danh mục không tồn tại.'], 404);
        }

        return response()->json(['data' => $category], 200);
    }
    public function update(Request $request, $id)
    {
        try {
            // Lấy danh mục theo ID
            $category = Category::find($id);
            if (!$category) {
                return response()->json(
                    ['error' => 'Danh mục không tồn tại.'],
                    404
                );
            }
            // Cập nhật thông tin danh mục
            $category->name = $request->input('name');
            $category->description = $request->input('description');
            $category->trangthai = $request->input('trangthai');
            // Lưu danh mục vào cơ sở dữ liệu
            $category->save();
            // Trả về phản hồi thành công
            return response()->json([
                'message' => 'Danh mục đã được cập nhật thành công!',
                'data' => $category
            ], 200);
        } catch (Exception $e) {
            // Xử lý lỗi và trả về thông báo lỗi
            return response()->json([
                'error' => 'Đã xảy ra lỗi: ' . $e
                    ->getMessage()
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            // Lấy danh mục theo ID
            $category = Category::find($id);
            if (!$category) {
                return response()->json(
                    ['error' => 'Danh mục không tồn tại.'],
                    404
                );
            }
            // Xóa danh mục khỏi cơ sở dữ liệu
            $category->delete();
            // Trả về phản hồi thành công
            return response()->json([
                'message' => 'Danh mục đã được xóa thành công!',
            ], 200);
        } catch (Exception $e) {
            // Xử lý lỗi và trả về thông báo lỗi
            return response()->json([
                'error' => 'Đã xảy ra lỗi: ' . $e
                    ->getMessage()
            ], 500);
        }
    }

    /////////////////////////////////////////sanpham//////////////////////////////
    public function addSanPham(Request $request)
    {
        try {
            // Xác thực dữ liệu đầu vào
            $validator = Validator::make(
                $request->all(),
                [
                    'name' => 'required|string|max:255',
                    'price' => 'required|numeric',
                    'description' => 'nullable|string',
                    'category_id' => 'required|exists:categories,id',
                ]
            );

            // Nếu xác thực không thành công, trả về lỗi
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            // Lưu sản phẩm vào bảng sanphams
            $sanpham = Sanpham::create([
                'name' => $request->input('name'),
                'price' => $request->input('price'),
                'slug' => Str::slug($request->input('name')),
                'price_sale' => $request->input('price_sale'),
                'mota' => $request->input('mota'),
                'category_id' => $request->input('category_id'),
                'quantity_in_stock' => $request->input('quantity_in_stock'),
            ]);

            DB::table('anhsanphams')->insert(
                [
                    'sanpham_id' => $sanpham->id,
                    'url' => $request->input('file'),
                ]
            );
            // Trả về phản hồi thành công với thông tin sản phẩm
            return response()->json([
                'message' => 'Sản phẩm đã được thêm thành công!',
                'data' => $sanpham
            ], 201);

        } catch (Exception $e) {
            // Xử lý lỗi và trả về thông báo lỗi
            return response()->json([
                'error' => 'Đã xảy ra lỗi: ' . $e->getMessage()
            ], 500);
        }
    }
    public function allSanPham()
    {
        $sanphams = Sanpham::with('category')->with('images')->with('images')->get();
        return response()->json([
            'data' => $sanphams
        ], 200);
    }

    public function sanphamDanhmucID($id)
    {
        $sanphams = Sanpham::with('category')->with('images')->where('category_id', '=', $id)->get();
        return response()->json([
            'data' => $sanphams
        ], 200);
    }

    public function getSanPham($id)
    {
        $sanpham = Sanpham::with('category')->with('images')->findOrFail($id);
        return response()->json([
            'data' => $sanpham
        ], 200);

    }

    public function updateSanPham(Request $request, $id)
    {
        // dd($request->all());
        $sanpham = Sanpham::findOrFail($id);
        $sanpham->update([
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'price_sale' => $request->input('price_sale'),
            'slug' => Str::slug($request->input('name')),
            'mota' => $request->input('mota'),
            'category_id' => $request->input('category_id'),
            'trangthai' => $request->input('trangthai'),
            'quantity_in_stock' => $request->input('quantity_in_stock'),
        ]);
        // Trả về phản hồi thành công với thông tin sản phẩm
        return response()->json([
            'message' => 'Sản phẩm đã được cập nhật thành công!',
            'data' => $sanpham
        ], 200);
    }

    public function deleteSanPham($id)
    {
        $sanpham = Sanpham::findOrFail($id);
        $sanpham->delete();
        return response()->json([
            'message' => 'Sản phẩm đã được xóa thành công!'
        ], 200);
    }

    public function getAnh(Request $request, $id)
    {
        $anh = Anhsanpham::where('sanpham_id', $id)->get();
        return response()->json([
            'data' => $anh
        ], 200);
    }
    public function uploadAnh(Request $request)
    {
        // Kiểm tra xem có tệp tin nào được tải lên không
        if ($request->hasFile('images')) {
            $sanphamId = $request->input('sanpham_id');
            $uploadedFiles = $request->file('images'); // Thay 'url' bằng 'images'
            $uploadedPaths = [];  // Mảng để lưu đường dẫn các tệp đã tải lên

            // Duyệt qua từng ảnh đã tải lên và lưu chúng
            foreach ($uploadedFiles as $file) {
                $name = $file->getClientOriginalName();
                $pathFull = 'uploads/' . date('Y/m/d');

                // Lưu ảnh vào thư mục public
                $file->storeAs('public/' . $pathFull, $name);

                // Lưu đường dẫn của ảnh vào cơ sở dữ liệu
                $url = '/storage/' . $pathFull . '/' . $name;
                $uploadedPaths[] = $url;

                // Lưu thông tin ảnh vào bảng Anhsanpham (hoặc bảng tương ứng)
                $anh = new Anhsanpham();
                $anh->sanpham_id = $sanphamId;
                $anh->url = $url;
                $anh->save();
            }

            return response()->json([
                'message' => 'Upload ảnh thành công!',
                'urls' => $uploadedPaths // Trả về tất cả các URL ảnh đã tải lên
            ], 200);
        } else {
            // Nếu không có tệp tin nào được tải lên
            return response()->json([
                'error' => true,
                'message' => 'No files uploaded'
            ]);
        }
    }


    public function delAnh($id)
    {
        $anh = Anhsanpham::findOrFail($id);
        $anh->delete();
        return response()->json([
            'message' => 'Ảnh đã được xóa thành công!'
        ], 200);
    }

    public function addToCart(Request $request)
    {
        $productData = $request->input('product');
        $user_id = $request->input('user_id');

        $sanpham_id = $productData['id'];
        $quantity = $productData['quantity'];
        $ram = $productData['ram'];
        $rom = $productData['rom'];
        $color = $productData['color'];

        // Tìm người dùng theo user_id
        $user = User::find($user_id);
        if (!$user) {
            return response()->json([
                'error' => true,
                'message' => 'Người dùng không tồn tại!'
            ], 404);
        }

        // Tìm giỏ hàng của người dùng, nếu không có thì tạo mới
        $cart = Giohang::where('user_id', $user_id)->where('trangthai', 'Chưa đặt hàng')->first();

        if (!$cart) {
            // Nếu chưa có giỏ hàng, tạo giỏ hàng mới
            $cart = new Giohang();
            $cart->user_id = $user_id;
            $cart->save();
        }

        // Tìm chi tiết giỏ hàng của sản phẩm, kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
        $cartItem = Chitietgiohang::where('giohang_id', $cart->id)
            ->where('sanpham_id', $sanpham_id)
            ->where('ram', $ram)
            ->where('rom', $rom)
            ->where('color', $color)
            ->first();

        if ($cartItem) {
            // Nếu sản phẩm đã có trong giỏ hàng, cập nhật số lượng
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            // Nếu chưa có, tạo mới chi tiết giỏ hàng
            $cartItem = new Chitietgiohang();
            $cartItem->giohang_id = $cart->id;
            $cartItem->sanpham_id = $sanpham_id;
            $cartItem->quantity = $quantity;
            $cartItem->ram = $ram;
            $cartItem->rom = $rom;
            $cartItem->color = $color;
            $cartItem->save();
        }

        return response()->json([
            'message' => 'Sản phẩm đã được thêm vào giỏ hàng thành công!',
            'cart' => $cart // Trả về giỏ hàng đã cập nhật
        ], 200);
    }

    public function allCart($id)
    {
        // Tìm giỏ hàng của người dùng
        $cart = Giohang::where('user_id', $id)->where('trangthai', 'Chưa đặt hàng')->first();

        if (!$cart) {
            return response()->json([
                'error' => true,
                'message' => 'Giỏ hàng không tồn tại!'
            ], 404);
        }

        // Lấy các chi tiết giỏ hàng của giỏ hàng vừa tìm được
        $cartItems = Chitietgiohang::where('giohang_id', $cart->id)->with('product.images')->get();

        // Kiểm tra nếu không có mục giỏ hàng nào
        if ($cartItems->isEmpty()) {
            return response()->json([
                'error' => true,
                'message' => 'Giỏ hàng không có sản phẩm!'
            ], 404);
        }

        // Trả về các mục giỏ hàng cùng với thông tin sản phẩm
        return response()->json([
            'message' => 'Danh sách sản phẩm trong giỏ hàng',
            'carts' => $cartItems
        ], 200);
    }

    public function updateQuantity(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'error' => true,
                'message' => 'Người dùng không tồn tại!'
            ], 404);
        }
        $cart = Giohang::where('user_id', $user->id)->where('trangthai', 'Chưa đặt hàng')->latest()->first();
        if (!$cart) {
            return response()->json([
                'error' => true,
                'message' => 'Giỏ hàng không tồn tại!'
            ], 404);
        }
        $cartItem = Chitietgiohang::where('giohang_id', $cart->id)
            ->where('ram', $request->ram)
            ->where('rom', $request->rom)
            ->where('color', $request->color)
            ->where('sanpham_id', $request->sanpham_id)->first();
        if (!$cartItem) {
            return response()->json([
                'error' => true,
                'message' => 'Sản phẩm không tồn tại trong giỏ hàng!'
            ], 404);
        }
        $cartItem->quantity = $request->quantity;
        $cartItem->save();
        return response()->json([
            'message' => 'Cập nhật số lượng thành công!',
            'cartItem' => $cartItem
        ], 200);
    }

    public function deleteCart(Request $request, $id)
    {
        // Log dữ liệu nhận được từ client
        Log::info('Dữ liệu gửi từ client:', [
            'sanpham_id' => $request->sanpham_id,
            'ram' => $request->ram,
            'rom' => $request->rom,
            'color' => $request->color
        ]);

        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'error' => true,
                'message' => 'Người dùng không tồn tại!'
            ], 404);
        }

        $cart = Giohang::where('user_id', $user->id)->where('trangthai', 'Chưa đặt hàng')->latest()->first();
        if (!$cart) {
            return response()->json([
                'error' => true,
                'message' => 'Giỏ hàng không tồn tại!'
            ], 404);
        }

        $cartItem = Chitietgiohang::where('giohang_id', $cart->id)
            ->where('ram', $request->ram)
            ->where('rom', $request->rom)
            ->where('color', $request->color)
            ->where('sanpham_id', $request->sanpham_id)
            ->first();

        if (!$cartItem) {
            return response()->json([
                'error' => true,
                'message' => 'Sản phẩm không tồn tại trong giỏ hàng!'
            ], 404);
        }

        $cartItem->delete();

        return response()->json([
            'message' => 'Xóa sản phẩm khỏi giỏ hàng thành công!',
            'cartItem' => $cartItem
        ], 200);
    }


    ////////////////////////////////////////user////////////////////////////////////////////
    public function addUser(Request $request)
    {
        // dd($request->all());

        try {
            // Xác thực dữ liệu đầu vào
            $validator = Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'email' => 'required|email|unique:users',
                    'password' => 'required|min:6|confirmed', // Sử dụng 'confirmed' để kiểm tra password_confirmation
                    'phone' => 'required|regex:/^[0-9]{10}$/',  // Kiểm tra 10 chữ số
                    'diachi' => 'required',
                    'role' => 'required',
                    'trangthai' => 'required',
                    'ngaysinh' => 'required',
                    'gioitinh' => 'required',
                ],
                [
                    'name.required' => 'Tên không được để trống',
                    'email.required' => 'Email không được để trống',
                    'email.email' => 'Email không đúng định dạng',
                    'email.unique' => 'Email đã tồn tại',
                    'password.required' => 'Mật khẩu không được để trống',
                    'password.min' => 'Mật khẩu ít nhất 6 ký tự',
                    'password.confirmed' => 'Mật khẩu không khớp',
                    'phone.required' => 'Số điện thoại không được để trống',
                    'phone.regex' => 'Số điện thoại phải gồm 10 chữ số',  // Thông báo lỗi khi không hợp lệ
                    'diachi.required' => 'Địa chỉ không được để trống',
                    'role.required' => 'Vai trò không được để trống',
                    'trangthai.required' => 'Trạng thái không được để trống',
                    'ngaysinh.required' => 'Ngày sinh không được để trống',
                    'gioitinh.required' => 'Giới tính không được để trống',
                ]
            );

            // Nếu có lỗi validation, trả về lỗi
            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }

            // Tạo mới một user
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
                'phone' => $request->input('phone'),
                'diachi' => $request->input('diachi'),
                'role' => $request->input('role'),
                'trangthai' => $request->input('trangthai'),
                'ngaysinh' => $request->input('ngaysinh'),
            ]);

            // Trả về thông báo thành công và dữ liệu người dùng mới
            return response()->json([
                'message' => 'Người dùng đã được thêm thành công!',
                'user' => $user
            ], 201);

        } catch (Exception $e) {
            // Xử lý lỗi và trả về thông báo lỗi
            return response()->json([
                'error' => 'Đã xảy ra lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function allUser()
    {
        $users = User::all();
        return response()->json([
            'users' => $users
        ], 200);
    }

    public function getUser($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'error' => 'Người dùng không tồn tại'
            ], 404);
        }
        return response()->json([
            'user' => $user
        ], 200);
    }

    public function updateUser(Request $request, $id)
    {
        // dd($request->all());
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'error' => 'Người dùng không tồn tại'
            ], 404);
        }
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->input('password_new') ? bcrypt($request->input('password')) : $user->password, // Giữ nguyên mật khẩu cũ nếu không thay đổi
            'phone' => $request->input('phone'),
            'diachi' => $request->input('diachi'),
            'role' => $request->input('role'),
            'ngaysinh' => $request->input('ngaysinh'),
            'gioitinh' => $request->input('gioitinh'),
            'trangthai' => $request->input('trangthai'),
        ]);
        return response()->json([
            'user' => $user
        ], 200);

    }

    /////////////////////////////////////////trang thai/////////////////////////////////////////////
    public function addTrangThai(Request $request)
    {
        $trangthai = Trangthai::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ]);
        return response()->json([
            'trangthai' => $trangthai
        ], 200);
    }

    public function allTrangThai()
    {
        $trangthai = Trangthai::all();
        return response()->json([
            'trangthai' => $trangthai
        ], 200);
    }

    public function getTrangThai($id)
    {
        $trangthai = Trangthai::find($id);
        if (!$trangthai) {
            return response()->json([
                'error' => 'Trạng thái không tồn tại'
            ], 404);
        }
        return response()->json([
            'trangthai' => $trangthai
        ], 200);
    }

    public function updateTrangThai(Request $request, $id)
    {
        $trangthai = Trangthai::find($id);
        if (!$trangthai) {
            return response()->json([
                'error' => 'Trạng thái không tồn tại'
            ], 404);
        }
        $trangthai->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ]);
        return response()->json([
            'trangthai' => $trangthai
        ], 200);
    }

    public function deleteTrangThai($id)
    {
        $trangthai = Trangthai::find($id);
        if (!$trangthai) {
            return response()->json([
                'error' => 'Trạng thái không tồn tại'
            ], 404);
        }
        $trangthai->delete();
        return response()->json([
            'message' => 'Xóa trạng thái thành công'
        ], 200);
    }

    /////////////////////////////////////////////////////////thanh toan////////////////////////////////////////////
    // public function thanhtoan(Request $request, $id)
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
    //         return response()->json([
    //             'error' => 'Người dùng không tồn tại'
    //         ], 404);
    //     }
    //     $user->update([
    //         'name' => $name,
    //         'email' => $email,
    //         'phone' => $phone,
    //         'diachi' => $diachi,
    //     ]);

    //     $donhang = Donhang::create([
    //         'user_id' => $id,
    //         'trangthai_id' => $trangthai_id,
    //         'price' => $price,
    //         'ngaydathang' => Carbon::now(),
    //     ]);

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
    //     $giohang = new Giohang();
    //     $giohang = Giohang::where('user_id', $user->id)->first();
    //     $giohang->trangthai = 'Đã đặt hàng';
    //     $giohang->save();
    //     return response()->json(
    //         [
    //             'status' => 'success',
    //             'message' => 'Đặt hàng thành công',
    //             'data' => [
    //                 'thanhtoan' => $thanhtoan,
    //                 'donhang' => $donhang,
    //                 'chitietdonhang' => $chitietdonhang,
    //                 'user' => $user,
    //                 'thongtinthanhtoan' => $thongtinthanhtoan,
    //             ]
    //         ],
    //         200
    //     );
    // }
    /////////////////////////////////////////////////don hang////////////////////////////////////////////////
    public function allDonhang(Request $request)
    {
        $donhang = Donhang::select(['id', 'user_id', 'trangthai_id', 'price', 'ngaydathang'])
            ->with([
                'user:id,name',
                'trangthai:id,name'
            ])
            ->paginate(1);
        return response()->json([
            'status' => 'success',
            'message' => 'Lấy danh sách đơn hàng thành công',
            'donhang' => $donhang,
        ], 200);
    }
    public function chiTiet($id)
    {
        // Lấy thông tin đơn hàng và các quan hệ
        $donhang = Donhang::select(['id', 'user_id', 'trangthai_id', 'price', 'ngaydathang'])
            ->where('id', $id)
            ->with(['user:id,name,diachi,phone', 'trangthai:id,name']) // Lấy thông tin người dùng và trạng thái
            ->first(); // Chỉ lấy 1 bản ghi

        // Nếu không tìm thấy đơn hàng, trả về lỗi
        if (!$donhang) {
            return response()->json([
                'status' => 'error',
                'message' => 'Đơn hàng không tồn tại',
            ], 404);
        }

        // Lấy chi tiết đơn hàng và thông tin sản phẩm
        $chitietdonhang = Chitietdonhang::select(['id', 'donhang_id', 'sanpham_id', 'quantity', 'price'])
            ->where('donhang_id', $id)
            ->with(['product:id,name,price,price_sale']) // Lấy thông tin sản phẩm qua quan hệ product()
            ->get();

        // Trả về dữ liệu
        return response()->json([
            'status' => 'success',
            'message' => 'Lấy chi tiết đơn hàng thành công',
            'donhang' => $donhang,
            'chitietdonhang' => $chitietdonhang,
        ], 200);
    }

    public function updateDonhang(Request $request)
    {
        // dd($request->id,$request->donhang_id);
        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'required',
            ],
            [
                'id.required' => 'Vui lòng chọn trạng thái',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }
        $donhang = Donhang::find($request->donhang_id);
        if (!$donhang) {
            return response()->json([
                'status' => 'error',
                'message' => 'Đơn hàng không tồn tại',
            ], 404);
        }
        $donhang->update([
            'trangthai_id' => $request->id,
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Cập nhật đơn hàng thành công',
            'donhang' => $donhang,
        ], 200);
    }
}

