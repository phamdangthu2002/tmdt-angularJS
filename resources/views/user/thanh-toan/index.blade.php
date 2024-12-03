@extends('user.layouts.index')
@section('content')
    <style>
        body {
            background-color: #f8f9fa;
        }

        .card-header {
            font-size: 1.2rem;
            font-weight: bold;
        }

        .btn-pay {
            background-color: #007bff;
            border: none;
        }

        .btn-pay:hover {
            background-color: #0056b3;
        }

        .total-price {
            font-size: 1.5rem;
            font-weight: bold;
            color: #dc3545;
        }

        .img-fluid {
            height: 100px;
        }
    </style>

    <div class="container mb-5">
        @include('user.layouts.breadcrumb')
        <form action="/user/thanh-toan/payment/{{ $user->id }}" method="POST">

            <h2 class="mb-4 text-center">Thanh Toán</h2>
            <div class="row g-4">
                <!-- Thông tin đơn hàng -->
                <div class="col-lg-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">Thông Tin Đơn Hàng</div>
                        <div class="card-body">
                            <!-- Duyệt qua từng chi tiết giỏ hàng -->
                            @foreach ($chitietgiohangs as $chitiet)
                                <input type="hidden" name="sanpham_id" value="{{ $chitiet->product->id }}">
                                <div class="row mb-4">
                                    <!-- Hình ảnh sản phẩm -->
                                    <div class="col-4 d-flex justify-content-center align-items-center">
                                        <img src="{{ $chitiet->product->images[0]->url }}"
                                            alt="{{ $chitiet->product->name }}" class="img-fluid rounded"
                                            style="max-height: 100px; object-fit: cover;">
                                    </div>
                                    <!-- Thông tin sản phẩm -->
                                    <div class="col-8">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="font-weight-bold">{{ $chitiet->product->name }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Số lượng:</span>
                                            <span>{{ $chitiet->quantity }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Giá:</span>
                                            <span
                                                class="text-success">{{ number_format($chitiet->product->price_sale, 0, ',', '.') }}
                                                VNĐ</span>
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-3">
                                @php
                                    $quantitys = $chitietgiohangs->sum('quantity');
                                @endphp
                            @endforeach
                            <input type="hidden" name="quantity" value="{{ $quantitys }}">

                            <!-- Tổng cộng -->
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <strong>Tổng cộng:</strong>
                                <span class="total-price text-danger font-weight-bold">
                                    @php
                                        $total = $chitietgiohangs->sum(function ($item) {
                                            return $item->product->price_sale * $item->quantity;
                                        });
                                    @endphp
                                    {{ number_format($total, 0, ',', '.') }} VNĐ
                                    <input type="hidden" name="price" value="{{ $total }}">
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form nhập thông tin thanh toán -->
                <div class="col-lg-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-success text-white">Thông Tin Thanh Toán</div>
                        <div class="card-body">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Họ và Tên</label>
                                <input type="text" class="form-control" name="name" id="name"
                                    placeholder="Nhập họ và tên" value="{{ $user->name }}">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" id="email"
                                    placeholder="Nhập email" value="{{ $user->email }}">
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Số Điện Thoại</label>
                                <input type="text" class="form-control" name="phone" id="phone"
                                    placeholder="Nhập số điện thoại" value="{{ $user->phone }}">
                            </div>
                            <div class="mb-3">
                                <label for="diachi" class="form-label">Địa Chỉ</label>
                                <textarea class="form-control" id="diachi" name="diachi" rows="3" placeholder="Nhập địa chỉ giao hàng">{{ $user->diachi }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="payment_method" class="form-label">Phương Thức Thanh Toán</label>
                                <select class="form-select" id="payment_method" name="phuongthuc">
                                    <option value="cod" selected>Thanh toán khi nhận hàng (COD)</option>
                                    <option value="bank">Chuyển khoản ngân hàng</option>
                                    <option value="credit_card">Thẻ tín dụng</option>
                                </select>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-pay btn-lg text-white">Thanh Toán</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
