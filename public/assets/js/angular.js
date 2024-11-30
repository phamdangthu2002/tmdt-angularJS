// Menu collapse and expand
document.getElementById("menu-bar").addEventListener("click", function () {
    const sidebar = document.getElementById("sidebar");
    const mainContent = document.getElementById("main-content");
    sidebar.classList.toggle("collapsed");
    mainContent.classList.toggle("collapsed");
});

var app = angular.module("adminApp", []);

app.controller("MainCtrl", function ($scope) {});
////////////////////////////////////////////danhmuc/////////////////////////////////////////
app.controller("ctrlProduct", function ($scope, $http) {
    // Lấy danh mục từ API
    $http.get("/api/danh-muc/all").then(
        function (response) {
            $scope.danhMuc = response.data.data; // Lưu dữ liệu danh mục vào biến danhMuc
            console.log($scope.danhMuc);
        },
        function (error) {
            console.log(error);
        }
    );
});

app.controller("ctrlDanhmuc", function ($scope, $http) {
    // Khai báo dữ liệu của danh mục
    $scope.category = {
        name: "", // Tên danh mục
        description: "", // Mô tả danh mục
        status: "active", // Trạng thái, mặc định là "active"
    };

    // Hàm thêm danh mục
    $scope.addDanhmuc = function () {
        // Gửi dữ liệu từ form lên API
        $http.post("/api/danh-muc/add", $scope.category).then(
            function (response) {
                alert("Đã thêm thành công");
                console.log("Danh mục đã được thêm: ", response.data);
                // Có thể thêm logic xử lý sau khi thành công, ví dụ thông báo
            },
            function (error) {
                // Xử lý khi có lỗi
                if (error.status === 422) {
                    // Lưu lỗi trả về vào scope để hiển thị trong form
                    $scope.errors = error.data.error;
                    console.log($scope.errors);
                } else {
                    alert("Đã xảy ra lỗi, vui lòng thử lại.");
                }
            }
        );
    };
});
app.controller("CategoryController", function ($scope, $http) {
    // Khởi tạo mảng categories
    $scope.categories = [];

    // Lấy danh sách danh mục từ API
    $http.get("/api/danh-muc/all").then(
        function (response) {
            // Lưu dữ liệu danh mục vào scope
            $scope.categories = response.data.data;
            console.log(response.data.data);
        },
        function (error) {
            // Hiển thị lỗi nếu có
            console.log("Lỗi khi tải danh mục", error);
        }
    );
    $scope.editCategory = function (id) {
        $http.get("/api/danh-muc/get/" + id).then(
            function (response) {
                $scope.category = response.data.data;
                console.log(response.data);
            },

            function (error) {
                console.log("Lỗi khi tải danh mục", error);
            }
        );
    };
    $scope.deleteCategory = function (id) {
        $http.delete("/api/danh-muc/delete/" + id).then(
            function (response) {
                alert("Xóa danh mục thành công");
                console.log(response.data);
                $http.get("/api/danh-muc/all").then(
                    function (response) {
                        // Lưu dữ liệu danh mục vào scope
                        $scope.categories = response.data.data;
                        console.log(response.data.data);
                    },
                    function (error) {
                        // Hiển thị lỗi nếu có
                        console.log("Lỗi khi tải danh mục", error);
                    }
                );
            },
            function (error) {
                console.log("Lỗi khi xóa danh mục", error);
            }
        );
    };
    $scope.update = function () {
        // Gửi thông tin danh mục đầy đủ
        $http
            .post("/api/danh-muc/update/" + $scope.category.id, $scope.category)
            .then(
                function (response) {
                    alert("Đã cập nhật thành công");
                    $scope.categories = response.data.data; // Cập nhật lại danh sách danh mục sau khi sửa
                    $scope.category = {}; // Reset form sau khi cập nhật thành công
                    $http.get("/api/danh-muc/all").then(
                        function (response) {
                            // Lưu dữ liệu danh mục vào scope
                            $scope.categories = response.data.data;
                            console.log(response.data.data);
                        },
                        function (error) {
                            // Hiển thị lỗi nếu có
                            console.log("Lỗi khi tải danh mục", error);
                        }
                    );
                },
                function (error) {
                    console.log("Lỗi khi cập nhật danh mục", error);
                    alert("Có lỗi xảy ra khi cập nhật danh mục");
                }
            );
    };
});

////////////////////////////////////////////sanpham/////////////////////////////////////////
app.controller("ctrlProduct", function ($scope, $http) {
    // Lấy danh mục sản phẩm
    $http.get("/api/danh-muc/all").then(
        function (response) {
            // Lưu dữ liệu danh mục vào scope
            $scope.danhMuc = response.data.data;
            console.log(response.data.data);
        },
        function (error) {
            // Hiển thị lỗi nếu có
            console.log("Lỗi khi tải danh mục", error);
        }
    );

    // Khởi tạo đối tượng sản phẩm
    $scope.product = {
        name: "", // Tên sản phẩm
        category_id: "", // ID danh mục
        price: "",
        price_sale: "",
        mota: "", // Mô tả sản phẩm
        quantity_in_stock: "", // Số lượng trong kho
        file: "", // Đường dẫn tệp tin (ảnh sản phẩm)
        trangthai: "active", // Trạng thái, mặc định là "active"
    };

    // Hàm thêm sản phẩm
    $scope.addSanpham = function () {
        if (
            $scope.product.name &&
            $scope.product.category_id &&
            $scope.product.price
        ) {
            $http.post("/api/san-pham/add", $scope.product).then(
                function (response) {
                    // Reset form sau khi thêm thành công
                    $scope.product = {
                        name: "",
                        category_id: "",
                        price: "",
                        price_sale: "",
                        mota: "",
                        quantity_in_stock: "",
                        file: "",
                        trangthai: "active",
                    };
                    // Cập nhật lại danh sách sản phẩm nếu cần
                    alert("Thêm sản phẩm thành công!");
                    console.log(response.data); // Log dữ liệu trả về từ server
                },
                function (error) {
                    console.log("Lỗi khi thêm sản phẩm", error);
                }
            );
        } else {
            alert("Vui lòng điền đầy đủ thông tin sản phẩm.");
        }
    };
});

app.controller("productController", function ($scope, $http, $rootScope) {
    // Lấy danh sách sản phẩm
    $http.get("/api/san-pham/all").then(
        function (response) {
            $scope.products = response.data.data;
            console.log($scope.products);
        },
        function (error) {
            console.log("Lỗi khi lấy danh sách sản phẩm", error);
        }
    );

    // Mở modal chỉnh sửa sản phẩm và gửi id sản phẩm
    $scope.openEditModal = function (id) {
        // Gửi id sản phẩm và mở modal
        $rootScope.$broadcast("openEditModal", id);

        // Gọi modal
        const modal = new bootstrap.Modal(
            document.getElementById("productModal")
        );
        modal.show();
    };

    $scope.openEditModalAnh = function (id) {
        $rootScope.$broadcast("openEditModalAnh", id);
        const modal = new bootstrap.Modal(document.getElementById("anhModal"));
        modal.show();
    };

    //hàm xóa sản phẩm
    $scope.deleteProduct = function (id) {
        // Mở modal xác nhận
        $("#confirmDeleteModal").modal("show");

        // Lắng nghe sự kiện khi người dùng nhấn nút "Xóa" trong modal
        $("#confirmDeleteButton")
            .off("click")
            .on("click", function () {
                // Gửi yêu cầu xóa sản phẩm
                $http.delete("/api/san-pham/delete/" + id).then(
                    function (response) {
                        // Lấy danh sách sản phẩm
                        $http.get("/api/san-pham/all").then(
                            function (response) {
                                $scope.products = response.data.data;
                                console.log($scope.products);
                            },
                            function (error) {
                                console.log(
                                    "Lỗi khi lấy danh sách sản phẩm",
                                    error
                                );
                            }
                        );
                        // Đóng modal
                        $("#confirmDeleteModal").modal("hide");
                    },
                    function (error) {
                        console.log("Lỗi khi xóa sản phẩm", error);
                        $("#confirmDeleteModal").modal("hide");
                    }
                );
            });
    };
});

app.controller("ctrProductEdit", function ($scope, $http, $rootScope) {
    // Lắng nghe sự kiện 'openEditModal' từ controller khác
    $rootScope.$on("openEditModal", function (event, productId) {
        console.log("Sản phẩm cần sửa:", productId);

        // Lấy thông tin sản phẩm từ API
        $http.get("/api/san-pham/get/" + productId).then(
            function (response) {
                console.log(response.data.data);
                $scope.productEdit = response.data.data; // Lưu thông tin vào productEdit
                $scope.productEdit.price = parseFloat(
                    $scope.productEdit.price.replace(/[^0-9.-]+/g, "")
                );
                $scope.productEdit.price_sale = parseFloat(
                    $scope.productEdit.price_sale.replace(/[^0-9.-]+/g, "")
                );
            },
            function (error) {
                console.log("Lỗi khi lấy thông tin sản phẩm", error);
            }
        );
    });

    // Lấy danh mục sản phẩm để hiển thị trong select
    $http.get("/api/danh-muc/all").then(
        function (response) {
            $scope.categories = response.data.data;
        },
        function (error) {
            console.log("Lỗi khi lấy danh mục", error);
        }
    );

    //hàm lưu sản phẩm
    $scope.saveProduct = function () {
        // Kiểm tra dữ liệu có hợp lệ không trước khi gửi
        if ($scope.productEdit.name && $scope.productEdit.price) {
            console.log($scope.productEdit);
            // Gửi dữ liệu lên server (thêm hoặc sửa sản phẩm)
            $http
                .post(
                    "/api/san-pham/update/" + $scope.productEdit.id,
                    $scope.productEdit
                )
                .then(function (response) {
                    console.log("Sản phẩm đã được lưu:", response.data);
                    // Có thể hiển thị thông báo thành công ở đây
                    // Ví dụ: thông báo SweetAlert2 hoặc Toast
                    // Sau đó, đóng modal nếu cần

                    // Lấy danh sách sản phẩm mới từ server
                    $http.get("/api/san-pham/all").then(
                        function (response) {
                            $scope.products = response.data.data; // Cập nhật lại danh sách sản phẩm
                            console.log($scope.products);
                            // Có thể hiển thị thông báo thành công
                            $("#productModal").modal("hide");
                            alert("Đã cập nhật sản phẩm");
                        },
                        function (error) {
                            console.log(
                                "Lỗi khi lấy danh sách sản phẩm",
                                error
                            );
                        }
                    );
                })
                .catch(function (error) {
                    console.log("Lỗi khi lưu sản phẩm:", error);
                    // Hiển thị thông báo lỗi nếu có
                    alert("Lỗi khi lưu sản phẩm, vui lòng thử lại");
                });
        } else {
            // Nếu dữ liệu không hợp lệ, hiển thị thông báo lỗi
            alert("Vui lòng điền đầy đủ thông tin sản phẩm.");
        }
    };
});
app.controller("ctrlAnh", function ($scope, $http, $rootScope) {
    // Hàm lấy danh sách ảnh
    $scope.getAnh = function () {
        $http.get("/api/anhs").then(
            function (response) {
                $scope.anhs = response.data.data;
            },
            function (error) {
                console.log("Lỗi khi lấy danh sách ảnh:", error);
            }
        );
    };
    // Lắng nghe sự kiện 'openEditModal' từ controller khác
    $rootScope.$on("openEditModalAnh", function (event, productId) {
        console.log("Sản phẩm cần thêm ảnh:", productId);
        $scope.productId = productId; // Gán productId vào scope
        $http.get("/api/san-pham/anh/" + productId).then(
            function (response) {
                $scope.anhs = response.data.data;
                console.log($scope.anhs);
                // Hiển thị modal
                $("#anhModal").modal("show");
            },
            function (error) {
                console.log("Lỗi khi lấy danh sách ảnh:", error);
            }
        );
    });
    $scope.previewImages = function (input) {
        // Khởi tạo lại mảng ảnh đã chọn và xem trước
        $scope.selectedImages = []; // Mảng lưu trữ các tệp đã chọn
        $scope.imagePreviews = []; // Mảng lưu trữ URL xem trước ảnh

        if (input.files && input.files.length > 0) {
            angular.forEach(input.files, function (file) {
                // Thêm tệp vào mảng selectedImages
                $scope.selectedImages.push(file);

                // Xem trước ảnh
                const reader = new FileReader();
                reader.onload = function (e) {
                    $scope.$apply(function () {
                        // Thêm URL xem trước vào mảng imagePreviews
                        $scope.imagePreviews.push(e.target.result);
                    });
                };
                reader.readAsDataURL(file);
            });
        }
    };

    $scope.Save = function (productId) {
        // Kiểm tra xem có ảnh nào đã chọn hay không
        if (!$scope.selectedImages || !$scope.selectedImages.length) {
            alert("Vui lòng chọn ít nhất một ảnh!");
            return;
        }

        // Tạo FormData để gửi dữ liệu lên server
        var formData = new FormData();

        // Thêm sanpham_id vào FormData
        formData.append("sanpham_id", productId);

        // Thêm từng ảnh vào FormData
        for (var i = 0; i < $scope.selectedImages.length; i++) {
            formData.append("images[]", $scope.selectedImages[i]);
        }

        // Gửi request POST đến server
        $http
            .post("/api/san-pham/upload-anh", formData, {
                headers: { "Content-Type": undefined }, // Quan trọng để gửi dữ liệu kiểu multipart
            })
            .then(
                function (response) {
                    console.log(response.data);
                    // Nếu tải lên thành công, cập nhật lại danh sách ảnh
                    if (response.data.urls) {
                        // Cập nhật lại danh sách ảnh
                        $scope.imagePreviews = response.data.urls;

                        // Nếu bạn cần lưu lại các ảnh đã chọn trong một biến khác, có thể gán lại như sau:
                        $scope.selectedImages = [];
                    }
                },
                function (error) {
                    console.log(error);
                    // Xử lý lỗi khi tải lên
                }
            );
    };

    // Hàm xóa ảnh đã tải lên
    $scope.removeExistingImage = function (imageId) {
        if (confirm("Bạn có chắc chắn muốn xóa ảnh này?")) {
            $http.delete("/api/san-pham/delete-anh/" + imageId).then(
                function (response) {
                    // Xóa ảnh khỏi danh sách hiện tại
                    $scope.anhs = $scope.anhs.filter(
                        (anh) => anh.id !== imageId
                    );
                    console.log("Ảnh đã xóa:", imageId);
                },
                function (error) {
                    console.log("Lỗi khi xóa ảnh:", error);
                }
            );
        }
    };
});

///////////////////////////////////user////////////////////////////////////////////
app.controller("userController", function ($scope, $http) {
    $scope.user = {
        name: "",
        email: "",
        password: "",
        ngaysinh: "",
        diachi: "",
        phone: "",
    };

    $scope.submitForm = function () {
        // Kiểm tra nếu ngaysinh có giá trị và chuyển đổi sang định dạng YYYY-MM-DD HH:mm:ss
        if ($scope.user.ngaysinh) {
            // Chuyển đổi ngày từ chuỗi sang đối tượng moment với định dạng 'YYYY/MM/DD'
            let date = moment($scope.user.ngaysinh, "YYYY/MM/DD", true);

            // Kiểm tra nếu ngày hợp lệ
            if (date.isValid()) {
                // Nếu ngày sinh không có giờ, lấy giờ hiện tại
                if (!date.hours() && !date.minutes() && !date.seconds()) {
                    // Lấy giờ hiện tại từ hệ thống
                    date = moment(); // Tạo một moment mới với thời gian hiện tại
                }

                // Chuyển đổi múi giờ sang GMT+7 (UTC+7) và định dạng lại thành YYYY-MM-DD HH:mm:ss
                $scope.user.ngaysinh = date
                    .utcOffset(7)
                    .format("YYYY-MM-DD HH:mm:ss");
            } else {
                alert("Ngày sinh không hợp lệ. Vui lòng nhập đúng định dạng.");
                return; // Dừng lại nếu ngày sinh không hợp lệ
            }
        }

        console.log($scope.user.ngaysinh); // Kiểm tra giá trị ngày sinh trước khi gửi

        // Gửi dữ liệu lên server
        $http.post("/api/user/add", $scope.user).then(
            function (response) {
                console.log(response.data);
                // Xóa lỗi khi form thành công
                $scope.errors = {};
            },
            function (error) {
                console.log("Lỗi khi lưu user:", error);

                if (error.status === 422) {
                    // Xử lý lỗi trả về từ API
                    $scope.errors = error.data.errors;
                }
            }
        );
    };
});

app.controller("userController", function ($scope, $http, $rootScope) {
    $http.get("/api/user/all").then(
        function (response) {
            $scope.users = response.data.users;
            console.log($scope.users);
        },
        function (error) {
            console.log("Lỗi khi lấy danh sách user:", error);
        }
    );

    $scope.editUser = function (id) {
        // Gửi ID người dùng và mở modal
        $rootScope.$broadcast("editUser", id);

        // Gọi modal
        const modal = new bootstrap.Modal(document.getElementById("userModal"));
        modal.show();
    };

    $scope.isLoading = false;
});

app.controller("ctrUserEdit", function ($scope, $http, $rootScope) {
    // Lắng nghe sự kiện 'editUser' từ controller khác
    $rootScope.$on("editUser", function (event, id) {
        console.log("Sản phẩm cần sửa:", id);
        $scope.editUser(id); // Gọi hàm editUser để lấy dữ liệu người dùng
    });

    // Hàm lấy dữ liệu người dùng và hiển thị modal
    $scope.editUser = function (id) {
        $scope.isLoading = true; // Bắt đầu loading
        $http
            .get("/api/user/get/" + id)
            .then(
                function (response) {
                    $scope.userEdit = response.data.user; // Gán dữ liệu vào userEdit
                    // Định dạng ngày sinh
                    if ($scope.userEdit.ngaysinh) {
                        $scope.userEdit.ngaysinh = new Date(
                            $scope.userEdit.ngaysinh
                        );
                    }
                    console.log($scope.userEdit);
                },
                function (error) {
                    alert("Lỗi khi lấy thông tin người dùng!");
                    console.log(error);
                }
            )
            .finally(function () {
                $scope.isLoading = false; // Kết thúc loading
            });
    };
    $scope.saveUser = function () {
        $scope.isLoading = true; // Bắt đầu loading
        // Kiểm tra nếu ngaysinh có giá trị và chuyển đổi sang định dạng YYYY-MM-DD HH:mm:ss
        if ($scope.userEdit.ngaysinh) {
            // Chuyển đổi ngày từ chuỗi sang đối tượng moment với định dạng 'YYYY/MM/DD'
            let date = moment($scope.userEdit.ngaysinh, "YYYY/MM/DD", true);

            // Kiểm tra nếu ngày hợp lệ
            if (date.isValid()) {
                // Nếu ngày sinh không có giờ, lấy giờ hiện tại
                if (!date.hours() && !date.minutes() && !date.seconds()) {
                    // Lấy giờ hiện tại từ hệ thống
                    date = moment(); // Tạo một moment mới với thời gian hiện tại
                }

                // Chuyển đổi múi giờ sang GMT+7 (UTC+7) và định dạng lại thành YYYY-MM-DD HH:mm:ss
                $scope.userEdit.ngaysinh = date
                    .utcOffset(7)
                    .format("YYYY-MM-DD HH:mm:ss");
            } else {
                alert("Ngày sinh không hợp lệ. Vui lòng nhập đúng định dạng.");
                return; // Dừng lại nếu ngày sinh không hợp lệ
            }
        }
        $http
            .post("/api/user/update/" + $scope.userEdit.id, $scope.userEdit) // Sửa tham số URL
            .then(
                function (response) {
                    alert("Cập nhật thông tin người dùng thành công!");
                    $scope.userEdit = {};
                    $scope.isLoading = false; // Kết thúc loading
                    $scope.data = response.data.user;
                    // Đóng modal sau khi cập nhật thành công
                    $("#userModal").modal("hide");
                    $http.get("/api/user/all").then(
                        function (response) {
                            $scope.users = response.data.users;
                            console.log($scope.users);
                        },
                        function (error) {
                            console.log("Lỗi khi lấy danh sách user:", error);
                        }
                    );
                },
                function (error) {
                    alert("Lỗi khi cập nhật thông tin người dùng!");
                    console.log(error);
                }
            )
            .finally(function () {
                $scope.isLoading = false; // Kết thúc loading
            });
    };
});
