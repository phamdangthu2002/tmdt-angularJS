<!DOCTYPE html>
<html lang="vi" ng-app="adminApp" ng-controller="MainCtrl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin | {{ $title }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-5.3.3-dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/boxicons-2.1.4/css/boxicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/load.css') }}">

</head>

<body>
    <div class="row">
        <div class="col-lg-2" id="sidebar">
            <div class="d-flex flex-column bg-dark text-white vh-100">
                <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                    <img src="/assets/images/1.png" alt="logo" class="img-fluid" style="width: 100%; height: 40px;"
                        id="logo">
                    <button class="btn btn-outline-light" type="button" id="menu-bar">
                        <i class="bx bx-menu"></i>
                    </button>
                </div>
                <ul class="nav flex-column mt-3">
                    <!-- Mục Dashboard với submenu -->
                    <li class="nav-item">
                        <a href="#" class="nav-link text-white" data-bs-toggle="collapse"
                            data-bs-target="#trangchu" aria-expanded="false">
                            <i class="bx bxs-home me-2"></i>
                            <span>Trang chủ</span>
                        </a>
                        <!-- Submenu Dashboard -->
                        <ul id="trangchu" class="collapse ms-3">
                            <li>
                                <a href="{{ route('admin.trangchu') }}" class="item nav-link text-white"
                                    style="font-size: 13px">
                                    <i class='bx bx-arrow-back me-2' style="font-size: 13px"></i>
                                    <span>Trang chủ</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link text-white" data-bs-toggle="collapse"
                            data-bs-target="#danhmuc" aria-expanded="false">
                            <i class="bx bxs-category me-2"></i>
                            <span>Danh mục</span>
                        </a>
                        <!-- Submenu Dashboard -->
                        <ul id="danhmuc" class="collapse ms-3">
                            <li>
                                <a href="{{ route('admin.danhmuc') }}" class="item nav-link text-white "
                                    style="font-size: 13px">
                                    <i class="bx bx-edit-alt me-2" style="font-size: 13px"></i>
                                    Thêm mới
                                </a>
                            </li>
                            <li><a href="{{ route('admin.showDanhmuc') }}" class="item nav-link text-white"
                                    style="font-size: 13px">
                                    <i class="bx bx-cog me-2" style="font-size: 13px"></i>
                                    Quản lý
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link text-white" data-bs-toggle="collapse" data-bs-target="#user"
                            aria-expanded="false">
                            <i class="bx bx-user me-2"></i>
                            <span>User</span>
                        </a>
                        <!-- Submenu Dashboard -->
                        <ul id="user" class="collapse ms-3">
                            <li>
                                <a href="/admin/user" class="item nav-link text-white" style="font-size: 13px">
                                    <i class="bx bx-edit-alt me-2" style="font-size: 13px"></i>
                                    <span>Thêm mới</span>
                                </a>
                            </li>
                            <li>
                                <a href="/admin/show" class="item nav-link text-white" style="font-size: 13px">
                                    <i class="bx bx-cog me-2" style="font-size: 13px"></i>
                                    <span>Quản lý</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link text-white" data-bs-toggle="collapse"
                            data-bs-target="#sanpham" aria-expanded="false">
                            <i class="bx bxs-package me-2"></i>
                            <span>Sản phẩm</span>
                        </a>
                        <!-- Submenu Dashboard -->
                        <ul id="sanpham" class="collapse ms-3">
                            <li>
                                <a href="/admin/san-pham/" class="item nav-link text-white" style="font-size: 13px">
                                    <i class="bx bx-edit-alt me-2" style="font-size: 13px"></i>
                                    <span>Thêm mới</span>
                                </a>
                            </li>
                            <li>
                                <a href="/admin/san-pham/show" class="item nav-link text-white"
                                    style="font-size: 13px">
                                    <i class="bx bx-cog me-2" style="font-size: 13px"></i>
                                    <span>Quản lý</span>
                                </a>
                            </li>
                        </ul>
                    <li class="nav-item">
                        <a href="#" class="nav-link text-white" data-bs-toggle="collapse"
                            data-bs-target="#trangthaii" aria-expanded="false">
                            <i class="bx bx-station me-2"></i>
                            <span>Trạng thái</span>
                        </a>
                        <!-- Submenu Dashboard -->
                        <ul id="trangthaii" class="collapse ms-3">
                            <li>
                                <a href="/admin/trang-thai/" class="item nav-link text-white" style="font-size: 13px">
                                    <i class="bx bx-edit-alt me-2" style="font-size: 13px"></i>
                                    <span>Thêm mới</span>
                                </a>
                            </li>
                            <li>
                                <a href="/admin/trang-thai/show" class="item nav-link text-white"
                                    style="font-size: 13px">
                                    <i class="bx bx-cog me-2" style="font-size: 13px"></i>
                                    <span>Quản lý</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link text-white" data-bs-toggle="collapse"
                            data-bs-target="#donhang" aria-expanded="false">
                            <i class="bx bx-receipt me-2"></i>
                            <span>Đơn hàng</span>
                        </a>
                        <!-- Submenu Donhang -->
                        <ul id="donhang" class="collapse ms-3">
                            <li>
                                <a href="/admin/don-hang" class="item nav-link text-white" style="font-size: 13px">
                                    <i class="bx bx-cog me-2" style="font-size: 13px"></i>
                                    <span>Quản lý</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Mục Logout -->
                    <li class="nav-item">
                        <a href="#" class="nav-link text-white" onclick="confirmLogout()">
                            <i class="bx bxs-log-out-circle me-2"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        {{-- Loading --}}
        <div class="loader-container">
            <div class="loader"></div>
            <div class="loader-text">Loading...</div>
        </div>
        <div class="main-content">
            <div class="col-lg-10" id="main-content">
                @yield('content')
            </div>
        </div>

    </div>
    <script src="{{ asset('assets/vendor/moment.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap-5.3.3-dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/angular.min.js') }}"></script>
    <script src="{{ asset('assets/js/angular.js') }}"></script>
    <script>
        // Hiển thị loader trong 5 giây
        window.addEventListener('load', function() {
            // Ẩn nội dung chính và hiện loader
            const loaderContainer = document.querySelector('.loader-container');
            const mainContent = document.querySelector('.main-content'); // Sử dụng ID chính xác

            // Hiển thị loader
            loaderContainer.style.display = 'flex';
            mainContent.style.display = 'none';

            setTimeout(function() {
                // Sau 5 giây, ẩn loader và hiển thị nội dung chính
                loaderContainer.style.display = 'none';
                mainContent.style.display = 'block';
                document.body.style.overflow = 'auto'; // Cho phép cuộn
            }, 2000); // 5 giây
        });


        function confirmLogout() {
            if (confirm('Bạn có muốn đăng xuất không?')) {
                window.location.href = '/auth/logout';
            }
        }
    </script>

</body>

</html>
