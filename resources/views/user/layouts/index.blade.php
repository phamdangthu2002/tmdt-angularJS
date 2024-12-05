<!DOCTYPE html>
<html lang="vi" ng-app="userApp">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Chủ - {{ $title }}</title>
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-5.3.3-dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/boxicons-2.1.4/css/boxicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/user.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/load.css') }}">
    <style>
        /* body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            overflow: hidden;
        } */
    </style>
</head>

<body>
    {{-- Loading --}}
    <div class="loader-container">
        <div class="loader"></div>
        <div class="loader-text">Loading...</div>
    </div>

    <div class="main-content">
        <!-- Header -->
        @include('user.layouts.header')

        <!-- Product Section -->
        @yield('content')


        <!-- Cart Modal -->
        @include('user.layouts.cart')

        <!-- Footer -->
        <footer>
            <p>&copy; 2024 PhoneStore. Tất cả các quyền được bảo lưu. <a href="#">Điều Khoản Sử Dụng</a></p>
        </footer>
    </div>
    <!-- Popper.js -->
    <script src="{{ asset('assets/vendor/popper.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap-5.3.3-dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/angular.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/sweetalert2@11.js') }}"></script>
    <script>
        var app = angular.module("userApp", []);

        // Service để tái sử dụng logic API
        app.service('apiService', function($http) {
            // Phương thức GET
            this.get = function(url) {
                return $http.get(url);
            };

            // Phương thức POST
            this.post = function(url, data) {
                return $http.post(url, data);
            };

            // Phương thức PUT
            this.put = function(url, data) {
                return $http.put(url, data);
            };

            // Phương thức DELETE
            this.delete = function(url, data) {
                return $http({
                    method: 'DELETE',
                    url: url,
                    data: data,
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });
            };

            // Phương thức PATCH (cập nhật một phần tài nguyên)
            this.patch = function(url, data) {
                return $http({
                    method: 'PATCH',
                    url: url,
                    data: data,
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });
            };

            // Phương thức HEAD (kiểm tra tài nguyên mà không tải về body)
            this.head = function(url) {
                return $http.head(url);
            };
        });


        // Controller: Đăng ký người dùng
        app.controller('ctrlRegister', function($scope, apiService) {
            $scope.register = {
                name: '',
                email: '',
                password: '',
                password_confirm: ''
            };

            $scope.registerUser = function() {
                apiService.post('/api/auth/register', $scope.register).then(
                    function(response) {
                        console.log(response);
                        // Thông báo khi đăng ký thành công
                        Swal.fire({
                            title: 'Đăng Ký Thành Công!',
                            text: 'Chúc mừng bạn đã đăng ký thành công. Bạn có thể đăng nhập ngay bây giờ.',
                            icon: 'success',
                            confirmButtonText: 'Đăng Nhập'
                        }).then(function() {
                            // Sau khi người dùng nhấn "Đăng Nhập", chuyển hướng đến trang đăng nhập
                            window.location.href = '/auth/login';
                        });
                    },
                    function(error) {
                        // Thông báo khi đăng ký thất bại
                        $scope.errors = error.data.error || "Đăng ký thất bại!";
                        console.error("Error during register:", error);
                        Swal.fire({
                            title: 'Lỗi!',
                            text: $scope.errors,
                            icon: 'error',
                            confirmButtonText: 'Thử Lại'
                        });
                    }
                );
            };
        });


        // Controller: Danh mục
        app.controller('ctrDanhmuc', function($scope, apiService, $rootScope) {
            $scope.danhmucs = [];
            $scope.cart = [];
            $scope.totalQuantity = 0;

            // Lấy danh mục sản phẩm
            $scope.getDanhMuc = function() {
                apiService.get('/api/danh-muc/all').then(
                    function(response) {
                        $scope.danhmucs = response.data.data;
                    },
                    function(error) {
                        console.error("Error during get danh muc:", error);
                    }
                );
            };

            // Lấy sản phẩm theo danh mục
            $scope.danhmucID = function(id) {
                apiService.get('/api/san-pham/danhmuc/' + id).then(
                    function(response) {
                        $rootScope.danhmucUrl = response.data.data; // Lưu vào $rootScope
                        localStorage.setItem("danhmucUrl", JSON.stringify(response.data
                            .data)); // Lưu vào localStorage
                        window.location.href = "/user/danh-muc"; // Chuyển trang
                    },
                    function(error) {
                        console.error("Error during get danh muc:", error);
                    }
                );
            };


            // Thêm sản phẩm vào giỏ hàng
            $scope.addToCart = function(product) {
                const user_id = {{ auth()->check() ? auth()->user()->id : 'null' }};
                if (!user_id) return;

                const payload = {
                    user_id: user_id,
                    product_id: product.id,
                    quantity: 1
                };

                apiService.post('/api/san-pham/cart/add', payload).then(
                    function(response) {
                        console.log("Thêm sản phẩm vào giỏ hàng thành công:", response.data);

                        // Phát sự kiện cập nhật giỏ hàng
                        $rootScope.$emit("updateCart");
                        // Hiển thị thông báo thành công khi thêm sản phẩm vào giỏ hàng
                        Swal.fire({
                            title: 'Thành Công!',
                            text: 'Sản phẩm đã được thêm vào giỏ hàng.',
                            icon: 'success',
                            confirmButtonText: 'Tiếp Tục Mua Sắm'
                        });
                    },
                    function(error) {
                        console.error("Lỗi khi thêm sản phẩm vào giỏ hàng:", error);
                        // Hiển thị thông báo lỗi nếu có vấn đề
                        Swal.fire({
                            title: 'Lỗi!',
                            text: 'Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng. Vui lòng thử lại!',
                            icon: 'error',
                            confirmButtonText: 'Thử Lại'
                        });
                    }
                );
            };

            // Lấy số lượng sản phẩm trong giỏ hàng
            $rootScope.getCartQuantity = function() {
                const user_id = {{ auth()->check() ? auth()->user()->id : 'null' }};
                if (!user_id) return;

                apiService.get('/api/san-pham/cart/all/' + user_id).then(
                    function(response) {
                        $rootScope.cart = response.data.carts || [];
                        $rootScope.totalQuantity = $rootScope.cart.reduce(
                            (total, item) => total + item.quantity, 0
                        );
                        console.log("Tổng số lượng sản phẩm:", $rootScope.totalQuantity);
                    },
                    function(error) {
                        console.error("Lỗi khi gọi API giỏ hàng:", error);
                    }
                );
            };

            // Khởi tạo
            $scope.init = function() {
                $scope.getDanhMuc();
                $rootScope.getCartQuantity();
            };

            $scope.init();
        });


        //Controller: Danh mục ID
        app.controller('ctrldanhmucID', function($scope, $rootScope, apiService) {
            // Lấy dữ liệu từ $rootScope hoặc localStorage
            $scope.danhmucUrl = $rootScope.danhmucUrl || JSON.parse(localStorage.getItem("danhmucUrl"));

            if (!$scope.danhmucUrl) {
                console.error("Không có dữ liệu danh mục nào được tìm thấy!");
            } else {
                console.log("Dữ liệu danh mục nhận được:", $scope.danhmucUrl);
            }
            // Hiển thị danh sách sản phẩm nếu có
            $scope.products = $scope.danhmucUrl || [];
            $scope.openProductDetail = function(id) {
                $rootScope.$broadcast("openProductDetailModal", id);
                const modal = new bootstrap.Modal(document.getElementById("productDetailModal"));
                modal.show();
            };
            $scope.cart = [];
            $scope.total = 0;

            // Hàm lấy giỏ hàng
            $scope.getCart = function() {
                const user_id = {{ auth()->check() ? auth()->user()->id : 'null' }};
                if (!user_id) return;

                apiService.get('/api/san-pham/cart/all/' + user_id).then(
                    function(response) {
                        $scope.cart = response.data.carts || [];
                        console.log("Giỏ hàng hiện tại:", $scope.cart);

                        $scope.cart.forEach(function(item) {
                            item.quantity = item.quantity || 1; // Đảm bảo quantity luôn có giá trị
                        });

                        // Tính tổng tiền giỏ hàng
                        $scope.total = $scope.cart.reduce((sum, item) => {
                            const price = item.product.price_sale || item.product.price;
                            return sum + item.quantity * price;
                        }, 0);

                        // Cập nhật tổng số lượng sản phẩm trong giỏ
                        $rootScope.totalQuantity = $scope.cart.reduce(
                            (total, item) => total + item.quantity,
                            0
                        );
                    },
                    function(error) {
                        console.error("Lỗi khi gọi API giỏ hàng:", error);
                    }
                );
            };

            $scope.addCart = function(id) {
                const user_id = {{ auth()->check() ? auth()->user()->id : 'null' }};
                if (!user_id) return;

                const data = {
                    user_id,
                    product: {
                        quantity: 1,
                        ram: '4GB',
                        rom: '64GB',
                        color: 'Black',
                        id: id,
                    }
                };

                apiService.post('/api/san-pham/cart/add', data).then(
                    function(response) {
                        console.log("Sản phẩm đã được thêm vào giỏ hàng:", response.data);

                        // Thông báo thành công khi sản phẩm được thêm vào giỏ hàng
                        Swal.fire({
                            title: 'Thêm vào giỏ hàng thành công!',
                            text: 'Sản phẩm đã được thêm vào giỏ hàng của bạn.',
                            icon: 'success',
                            confirmButtonText: 'Tiếp tục mua sắm'
                        }).then(function() {
                            // Sau khi người dùng nhấn "Tiếp tục mua sắm", có thể giữ lại trên trang hiện tại
                        });

                        // Phát sự kiện để cập nhật giỏ hàng
                        $rootScope.$emit("updateCart");
                    },
                    function(error) {
                        console.error("Lỗi khi thêm sản phẩm vào giỏ hàng:", error);

                        // Thông báo lỗi khi thêm sản phẩm vào giỏ hàng
                        Swal.fire({
                            title: 'Lỗi!',
                            text: 'Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng. Vui lòng thử lại.',
                            icon: 'error',
                            confirmButtonText: 'Thử lại'
                        });
                    }
                );
            }
            // Gọi hàm getCart khi khởi tạo controller để tải giỏ hàng ban đầu
            $scope.getCart();
        });


        // Controller: Sản phẩm
        app.controller('ctrlCard', function($scope, apiService, $rootScope) {
            $scope.sanphams = [];

            $scope.getSanPhams = function() {
                apiService.get('/api/san-pham/all').then(
                    function(response) {
                        $scope.sanphams = response.data.data.map(sanpham => {
                            sanpham.price_percent = sanpham.price_sale ?
                                Math.round(((sanpham.price - sanpham.price_sale) / sanpham.price) *
                                    100) :
                                0;
                            sanpham.image = sanpham.images?.[0]?.url ||
                                'https://placehold.co/290x380';
                            return sanpham;
                        });
                    },
                    function(error) {
                        console.error("Lỗi khi gọi API sản phẩm:", error);
                    }
                );
            };
            $scope.openProductDetail = function(id) {
                $rootScope.$broadcast("openProductDetailModal", id);
                const modal = new bootstrap.Modal(document.getElementById("productDetailModal"));
                modal.show();
            };

            $scope.cart = [];
            $scope.total = 0;

            // Hàm lấy giỏ hàng
            $scope.getCart = function() {
                const user_id = {{ auth()->check() ? auth()->user()->id : 'null' }};
                if (!user_id) return;

                apiService.get('/api/san-pham/cart/all/' + user_id).then(
                    function(response) {
                        $scope.cart = response.data.carts || [];
                        console.log("Giỏ hàng hiện tại:", $scope.cart);

                        $scope.cart.forEach(function(item) {
                            item.quantity = item.quantity || 1; // Đảm bảo quantity luôn có giá trị
                        });

                        // Tính tổng tiền giỏ hàng
                        $scope.total = $scope.cart.reduce((sum, item) => {
                            const price = item.product.price_sale || item.product.price;
                            return sum + item.quantity * price;
                        }, 0);

                        // Cập nhật tổng số lượng sản phẩm trong giỏ
                        $rootScope.totalQuantity = $scope.cart.reduce(
                            (total, item) => total + item.quantity,
                            0
                        );
                    },
                    function(error) {
                        console.error("Lỗi khi gọi API giỏ hàng:", error);
                    }
                );
            };

            $scope.addCart = function(id) {
                const user_id = {{ auth()->check() ? auth()->user()->id : 'null' }};
                if (!user_id) return;

                const data = {
                    user_id,
                    product: {
                        quantity: 1,
                        ram: '4GB',
                        rom: '64GB',
                        color: 'Black',
                        id: id,
                    }
                };

                apiService.post('/api/san-pham/cart/add', data).then(
                    function(response) {
                        console.log("Sản phẩm đã được thêm vào giỏ hàng:", response.data);

                        // Thông báo thành công khi sản phẩm được thêm vào giỏ hàng
                        Swal.fire({
                            title: 'Thêm vào giỏ hàng thành công!',
                            text: 'Sản phẩm đã được thêm vào giỏ hàng của bạn.',
                            icon: 'success',
                            confirmButtonText: 'Tiếp tục mua sắm'
                        }).then(function() {
                            // Sau khi người dùng nhấn "Tiếp tục mua sắm", có thể giữ lại trên trang hiện tại
                        });

                        // Phát sự kiện để cập nhật giỏ hàng
                        $rootScope.$emit("updateCart");
                    },
                    function(error) {
                        console.error("Lỗi khi thêm sản phẩm vào giỏ hàng:", error);

                        // Thông báo lỗi khi thêm sản phẩm vào giỏ hàng
                        Swal.fire({
                            title: 'Lỗi!',
                            text: 'Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng. Vui lòng thử lại.',
                            icon: 'error',
                            confirmButtonText: 'Thử lại'
                        });
                    }
                );
            }
            // Gọi hàm getCart khi khởi tạo controller để tải giỏ hàng ban đầu
            $scope.getCart();
            $scope.getSanPhams();
        });

        // Controller: Chi tiết sản phẩm
        app.controller('ctrlDetail', function($scope, apiService, $rootScope) {
            $rootScope.$on("openProductDetailModal", function(event, productId) {
                apiService.get('/api/san-pham/get/' + productId).then(
                    function(response) {
                        $scope.sanphamDetail = response.data.data;
                        $scope.sanphamDetail.price_percent = Math.round(
                            (($scope.sanphamDetail.price - $scope.sanphamDetail.price_sale) / $scope
                                .sanphamDetail.price) * 100
                        );
                    },
                    function(error) {
                        console.error("Lỗi khi gọi API chi tiết sản phẩm:", error);
                    }
                );
            });

            $scope.changeMainImage = function(image, index) {
                document.getElementById('mainProductImage').src = image.url;
                document.querySelectorAll('.small-image').forEach((img, idx) => {
                    img.classList.toggle('active', idx === index);
                });
            };

            $scope.addToCart = function() {
                const user_id = {{ auth()->check() ? auth()->user()->id : 'null' }};
                if (!user_id) return;

                const data = {
                    user_id,
                    product: $scope.sanphamDetail
                };

                apiService.post('/api/san-pham/cart/add', data).then(
                    function(response) {
                        console.log("Sản phẩm đã được thêm vào giỏ hàng:", response.data);

                        // Thông báo thành công khi sản phẩm được thêm vào giỏ hàng
                        Swal.fire({
                            title: 'Thêm vào giỏ hàng thành công!',
                            text: 'Sản phẩm đã được thêm vào giỏ hàng của bạn.',
                            icon: 'success',
                            confirmButtonText: 'Tiếp tục mua sắm'
                        }).then(function() {
                            // Sau khi người dùng nhấn "Tiếp tục mua sắm", có thể giữ lại trên trang hiện tại
                        });

                        // Đóng modal sản phẩm
                        const modal = bootstrap.Modal.getInstance(document.getElementById(
                            "productDetailModal"));
                        modal.hide();

                        // Phát sự kiện để cập nhật giỏ hàng
                        $rootScope.$emit("updateCart");
                    },
                    function(error) {
                        console.error("Lỗi khi thêm sản phẩm vào giỏ hàng:", error);

                        // Thông báo lỗi khi thêm sản phẩm vào giỏ hàng
                        Swal.fire({
                            title: 'Lỗi!',
                            text: 'Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng. Vui lòng thử lại.',
                            icon: 'error',
                            confirmButtonText: 'Thử lại'
                        });
                    }
                );
            };
        });


        // Controller: Giỏ hàng
        app.controller('ctrlCart', function($scope, apiService, $rootScope) {
            $scope.cart = [];
            $scope.total = 0;

            // Lấy giỏ hàng
            $scope.getCart = function() {
                const user_id = {{ auth()->check() ? auth()->user()->id : 'null' }};
                if (!user_id) return;

                apiService.get('/api/san-pham/cart/all/' + user_id).then(
                    function(response) {
                        $scope.cart = response.data.carts || [];
                        console.log($scope.cart);
                        $scope.cart.forEach(function(item) {
                            item.quantity = item.quantity || 1; // Đảm bảo quantity luôn có giá trị
                        });
                        $scope.total = $scope.cart.reduce((sum, item) => {
                            const price = item.product.price_sale || item.product.price;
                            return sum + item.quantity * price;
                        }, 0);

                        // Cập nhật tổng số lượng
                        $rootScope.totalQuantity = $scope.cart.reduce(
                            (total, item) => total + item.quantity, 0
                        );
                    },
                    function(error) {
                        console.error("Lỗi khi gọi API giỏ hàng:", error);
                    }
                );
            };

            // Lắng nghe sự kiện updateCart để cập nhật giỏ hàng
            $rootScope.$on("updateCart", function() {
                $scope.getCart();
            });

            // Chức năng thanh toán
            $scope.Thanhtoan = function() {
                const user_id = {{ auth()->check() ? auth()->user()->id : 'null' }};
                window.location.href = '/user/thanh-toan/' + user_id;
            }

            $scope.deleteCart = function(item) {
                Swal.fire({
                    title: 'Bạn có chắc chắn muốn xóa sản phẩm này?',
                    text: item.product.name, // Hiển thị tên sản phẩm nếu cần
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Đồng Ý',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Gửi yêu cầu xóa sản phẩm chỉ khi người dùng xác nhận
                        const user_id = {{ auth()->check() ? auth()->user()->id : 'null' }};

                        apiService.delete(`/api/san-pham/cart/delete/${user_id}`, {
                            quantity: item.quantity,
                            sanpham_id: item.sanpham_id, // Kiểm tra item.sanpham_id
                            ram: item.ram,
                            rom: item.rom,
                            color: item.color
                        }).then(
                            function(response) {
                                Swal.fire({
                                    title: 'Thành Công!',
                                    text: 'Sản phẩm đã được xóa khỏi giỏ hàng.',
                                    icon: 'success',
                                    confirmButtonText: 'Đồng Ý'
                                });
                                $scope.getCart(); // Cập nhật lại giỏ hàng sau khi xóa
                                $scope.$applyAsync()
                            },
                            function(error) {
                                console.error("Lỗi khi xóa sản phẩm khỏi giỏ hàng:", error);
                                Swal.fire({
                                    title: 'Lỗi!',
                                    text: 'Không thể xóa sản phẩm khỏi giỏ hàng.',
                                    icon: 'error',
                                    confirmButtonText: 'Đồng Ý'
                                });
                            }
                        );
                    }
                });
            };
            // Gọi khi khởi tạo
            $scope.getCart();
        });


        // Controller: Update Quantity
        app.controller('QuantityController', function($scope, apiService, $rootScope) {
            $scope.min = 1; // Giá trị tối thiểu
            $scope.max = 10; // Giá trị tối đa
            const user_id = {{ auth()->check() ? auth()->user()->id : 'null' }}; // Lấy ID người dùng

            // Hàm tăng số lượng
            $scope.increaseQuantity = function(item) {
                if (item.quantity < $scope.max) {
                    item.quantity++; // Tăng số lượng của sản phẩm
                    $scope.updateQuantity(item);
                }
            };

            // Hàm giảm số lượng
            $scope.decreaseQuantity = function(item) {
                if (item.quantity > $scope.min) {
                    item.quantity--; // Giảm số lượng của sản phẩm
                    $scope.updateQuantity(item);
                }
            };

            // Hàm gửi dữ liệu cập nhật lên server
            $scope.updateQuantity = function(item) {
                if (!user_id) {
                    console.error('Người dùng chưa đăng nhập!');
                    return; // Ngừng gửi yêu cầu nếu người dùng chưa đăng nhập
                }

                // Gửi yêu cầu API để cập nhật số lượng
                apiService.post(`/api/san-pham/update-quantity/${user_id}`, {
                    quantity: item.quantity,
                    sanpham_id: item.product.id,
                    ram: item.ram,
                    rom: item.rom,
                    color: item.color
                }).then(
                    function(response) {
                        console.log('Cập nhật thành công:', response.data);

                        // Phát sự kiện cập nhật giỏ hàng
                        $rootScope.$broadcast("updateCart");
                    },
                    function(error) {
                        console.error('Lỗi khi cập nhật:', error);
                    }
                );
            };
        });


        /////////////////////////////////////////////////////////////lich su don hang////////////////////////////////////////////////////
        app.controller('ctrlHistory', function($scope, apiService) {
            $scope.history = [];
            const user_id = {{ auth()->check() ? auth()->user()->id : 'null' }}; // Lấy ID người dùng

            // Hàm tải lịch sử đơn hàng
            const loadHistory = async function() {
                try {
                    const response = await apiService.get('/api/don-hang/all');
                    $scope.historys = response.data.donhang.data;
                    console.log('don hang', $scope.historys);

                    // Đảm bảo giao diện cập nhật sau khi nhận dữ liệu
                    $scope.$applyAsync();
                } catch (error) {
                    console.error('Lỗi khi tải lịch sử:', error);
                }
            };
            $scope.loadChitiet = async function(id) {
                try {
                    const response = await apiService.get(`/api/don-hang/chitiet/${id}`);
                    $scope.chitiets = response.data.chitietdonhang; // Danh sách chi tiết đơn hàng
                    $scope.totalPrice = response.data.donhang.price; // Tổng tiền từ API
                    $scope.$applyAsync()
                } catch (error) {
                    console.error('Lỗi khi tải chi tiết đơn hàng:', error);
                }

                // Lấy modal
                const modal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));

                // Hiển thị modal
                modal.show();
            }

            // Gọi hàm khi controller được khởi tạo
            loadHistory();
        });


        // Hiển thị loader ngắn gọn
        window.addEventListener('load', function() {
            const loaderContainer = document.querySelector('.loader-container');
            const mainContent = document.querySelector('.main-content');

            loaderContainer.style.display = 'flex';
            mainContent.style.display = 'none';

            setTimeout(() => {
                loaderContainer.style.display = 'none';
                mainContent.style.display = 'block';
            }, 500); // 200ms thay vì 5 giây
        });

        // Lắng nghe sự kiện trên toàn bộ document
        document.addEventListener("click", function(event) {
            // Giảm số lượng
            if (event.target.id === "decreaseQuantity") {
                const input = event.target.nextElementSibling; // Lấy ô input bên cạnh nút "-"
                let currentValue = parseInt(input.value);
                if (currentValue > parseInt(input.min)) {
                    input.value = currentValue - 1;
                } else {
                    alert("Số lượng không thể nhỏ hơn " + input.min);
                }
            }

            // Tăng số lượng
            if (event.target.id === "increaseQuantity") {
                const input = event.target.previousElementSibling; // Lấy ô input bên cạnh nút "+"
                let currentValue = parseInt(input.value);
                if (currentValue < parseInt(input.max)) {
                    input.value = currentValue + 1;
                } else {
                    alert("Số lượng không thể lớn hơn " + input.max);
                }
            }
        });

        // Đảm bảo giá trị nhập tay hợp lệ
        document.addEventListener("input", function(event) {
            if (event.target.id === "quantityInput") {
                const input = event.target;
                let value = parseInt(input.value);
                if (value < parseInt(input.min)) {
                    input.value = input.min;
                    alert("Số lượng không thể nhỏ hơn " + input.min);
                } else if (value > parseInt(input.max)) {
                    input.value = input.max;
                    alert("Số lượng không thể lớn hơn " + input.max);
                }
            }
        });
        // Xử lý cuộn ảnh nhỏ
        document.getElementById('scrollLeft').addEventListener('click', function() {
            const container = document.querySelector('.small-images-container');
            container.scrollLeft -= 50;
        });

        document.getElementById('scrollRight').addEventListener('click', function() {
            const container = document.querySelector('.small-images-container');
            container.scrollLeft += 50;
        });

        function showLoginAlert() {
            Swal.fire({
                title: 'Cảnh Báo!',
                text: 'Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng.',
                icon: 'warning',
                confirmButtonText: 'Đồng Ý'
            });
        }
    </script>

</body>

</html>
