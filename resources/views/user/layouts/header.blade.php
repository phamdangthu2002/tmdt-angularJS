<nav class="navbar navbar-expand-lg navbar-light bg-white py-3">
    <div class="container">
        <a class="navbar-brand" href="/">PhoneStore</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav" ng-controller="ctrDanhmuc" ng-init="init()">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link" href="/user/trang-chu">Trang Chủ</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="categoryDropdown" data-bs-toggle="dropdown">
                        Danh Mục
                    </a>
                    <ul class="dropdown-menu text-center" id="categoryDropdownMenu">
                        <li ng-repeat="danhmuc in danhmucs">
                            <a class="dropdown-item" style="cursor: pointer" ng-click="danhmucID(danhmuc.id)">@{{ danhmuc.name }}</a>
                        </li>
                    </ul>
                </li>
            </ul>

            <div class="d-flex align-items-center">
                @auth
                    <!-- Nếu đã đăng nhập, hiển thị icon người dùng và menu -->
                    <div class="position-relative user-menu">
                        <i class="bx bx-user bx-sm" id="userIcon"></i>
                        <div class="user-dropdown text-center" id="userDropdown">
                            <a href="#">Thông Tin Tài Khoản</a>
                            <a href="#">Lịch Sử Đơn Hàng</a>
                            <a href="/auth/logout">Đăng Xuất</a>
                        </div>
                    </div>
                    <!-- Hiển thị nút giỏ hàng -->
                    <button class="btn btn-outline text-decoration-none" data-bs-toggle="modal" data-bs-target="#cartModal">
                        <i class="bx bx-cart bx-sm"></i>
                        <span id="cart-count" class="badge" ng-bind="$root.totalQuantity || 0"></span>
                    </button>
                @else
                    <!-- Kiểm tra nếu người dùng chưa đăng nhập -->
                    <a href="/auth/login" class="btn btn-outline-primary btn-sm me-2">Đăng Nhập</a>
                    <a href="/auth/register" class="btn btn-primary btn-sm me-3">Đăng Ký</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
