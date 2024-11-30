
var app = angular.module("userApp", []);

// Controller: Đăng ký người dùng
app.controller('ctrlRegister', function($scope, $http) {
    $scope.register = {
        name: '',
        email: '',
        password: '',
        password_confirm: ''
    };

    $scope.registerUser = function() {
        $http.post('/api/auth/register', $scope.register).then(
            function(response) {
                console.log(response);
                window.location.href = '/auth/login';
            },
            function(error) {
                $scope.errors = error.data.error;
                console.error("Error during register:", error);
            }
        );
    };
});

// Controller: Danh mục sản phẩm
app.controller('ctrDanhmuc', function($scope, $http) {
    $scope.getDanhMuc = function() {
        $http.get('/api/danh-muc/all').then(
            function(response) {
                $scope.danhmucs = response.data.data;
                console.log($scope.danhmucs);
            },
            function(error) {
                console.error("Error during get danh muc:", error);
            }
        );
    };

    $scope.getCartQuantity = function() {
        const user_id = {{ auth()->check() ? auth()->user()->id : 'null' }};
        if (!user_id) return;

        $http.get('/api/san-pham/cart/all/' + user_id).then(
            function(response) {
                $scope.cart = response.data.carts || [];
                $scope.totalQuantity = $scope.cart.reduce((total, item) => total + item.quantity,
                0);
                console.log("Tổng số lượng sản phẩm trong giỏ hàng:", $scope.totalQuantity);
            },
            function(error) {
                console.error("Lỗi khi gọi API danh sách sản phẩm:", error);
            }
        );
    };

    $scope.getDanhMuc();
    $scope.getCartQuantity();
});

// Controller: Sản phẩm
app.controller('ctrlCard', function($scope, $http, $rootScope) {
    $scope.getSanPhams = function() {
        $http.get('/api/san-pham/all').then(
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
                console.log($scope.sanphams);
            },
            function(error) {
                console.error('Lỗi khi gọi API:', error);
            }
        );
    };

    $scope.openProductDetail = function(id) {
        console.log(id);
        $rootScope.$broadcast("openProductDetailModal", id);
        const modal = new bootstrap.Modal(document.getElementById("productDetailModal"));
        modal.show();
    };

    $scope.getSanPhams();
});

// Controller: Chi tiết sản phẩm
app.controller('ctrlDetail', function($scope, $http, $rootScope) {
    $rootScope.$on("openProductDetailModal", function(event, productId) {
        console.log("Chi tiết sản phẩm:", productId);

        $scope.getProductDetail = function() {
            $http.get('/api/san-pham/get/' + productId).then(
                function(response) {
                    $scope.sanphamDetail = response.data.data;
                    $scope.sanphamDetail.price_percent = Math.round(
                        (($scope.sanphamDetail.price - $scope.sanphamDetail
                            .price_sale) / $scope.sanphamDetail.price) * 100
                    );
                    console.log($scope.sanphamDetail);
                },
                function(error) {
                    console.error("Lỗi khi gọi API chi tiết sản phẩm:", error);
                }
            );
        };

        $scope.changeMainImage = function(image, index) {
            const mainImage = document.getElementById('mainProductImage');
            mainImage.src = image.url;

            document.querySelectorAll('.small-image').forEach((img, idx) => {
                img.classList.toggle('active', idx === index);
            });
        };

        $scope.addToCart = function() {
            var user_id = {{ auth()->check() ? auth()->user()->id : 'null' }};
            if (user_id) {
                const data = {
                    user_id: user_id,
                    product: $scope.sanphamDetail // Gửi thông tin chi tiết sản phẩm
                };

                // Gửi yêu cầu thêm sản phẩm vào giỏ hàng
                $http.post('/api/san-pham/cart/add', data).then(function(response) {
                    console.log("Sản phẩm đã được thêm vào giỏ hàng:", response.data);

                    // Đóng modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById(
                        "productDetailModal"));
                    modal.hide();

                    // Gọi lại API giỏ hàng để cập nhật dữ liệu
                    updateCart();
                }).catch(function(error) {
                    console.error("Lỗi khi thêm sản phẩm vào giỏ hàng:", error);
                });
            } else {
                console.log("User ID is not defined.");
            }
        };

        // Hàm cập nhật giỏ hàng
        function updateCart() {
            $http.get('/api/san-pham/cart/all/' + user_id).then(function(response) {
                $scope.cart = response.data.carts || [];

                // Tính tổng số lượng sản phẩm
                let totalQuantity = 0;
                $scope.cart.forEach(function(item) {
                    totalQuantity += item.quantity;
                });

                $scope.totalQuantity = totalQuantity; // Lưu tổng số lượng vào scope
                console.log("Cập nhật giỏ hàng thành công. Tổng số lượng:", totalQuantity);
            }).catch(function(error) {
                console.error("Lỗi khi cập nhật giỏ hàng:", error);
            });
        }


        $scope.getProductDetail();
    });
});

// Controller: Giỏ hàng
app.controller('ctrlCart', function($scope, $http) {
    $scope.getCart = function() {
        const user_id = {{ auth()->check() ? auth()->user()->id : 'null' }};
        if (!user_id) return;

        $http.get('/api/san-pham/cart/all/' + user_id).then(
            function(response) {
                $scope.cart = response.data.carts;
                $scope.total = $scope.cart.reduce((sum, item) => {
                    const price = item.product.price_sale || item.product.price;
                    return sum + item.quantity * price;
                }, 0);
                console.log("Tổng tiền sản phẩm trong giỏ hàng:", $scope.total);
            },
            function(error) {
                console.error("Lỗi khi gọi API danh sách sản phẩm:", error);
            }
        );
    };

    $scope.getCart();
});

// Hiển thị loader trong 5 giây
window.addEventListener('load', function() {
    // Ẩn nội dung chính và hiện loader
    const loaderContainer = document.querySelector('.loader-container');
    const mainContent = document.querySelector('.main-content');

    // Đảm bảo loader hiển thị
    mainContent.style.display = 'none';
    loaderContainer.style.display = 'flex';

    setTimeout(function() {
        // Sau 5 giây, ẩn loader và hiển thị nội dung chính
        loaderContainer.style.display = 'none';
        mainContent.style.display = 'block';
        document.body.style.overflow = 'auto'; // Cho phép cuộn
    }, 200); // 5 giây
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
