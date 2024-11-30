@extends('user.layouts.index')
@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/card.css') }}">
    <style>
        /* Tùy chỉnh hình ảnh */
        .product-images img {
            max-height: 400px;
            object-fit: cover;
        }

        .smallImage {
            width: 80px;
            height: 80px;
        }

        @media (max-width: 576px) {
            .product-images img {
                max-height: 300px;
            }
        }

        #productTag {
            text-align: center;
            justify-content: center;
            justify-items: center;
        }

        /* Vùng chứa hình ảnh nhỏ */
        .small-images-container {
            padding: 5px;
            overflow-x: scroll;
            /* Kích hoạt cuộn ngang */
            white-space: nowrap;
            /* Đảm bảo nội dung không xuống dòng */
            max-width: 300px;
            /* Giới hạn chiều rộng */
            scrollbar-width: none;
            /* Ẩn thanh cuộn trên Firefox */
            -ms-overflow-style: none;
            /* Ẩn thanh cuộn trên IE và Edge */
        }

        .small-images-container::-webkit-scrollbar {
            display: none;
            /* Ẩn thanh cuộn trên Chrome, Safari */
        }

        .small-images-container::-webkit-scrollbar-thumb {
            background-color: #888;
            /* Màu tay cầm cuộn */
            border-radius: 3px;
            /* Bo góc */
            transition: background-color 0.3s ease;
            /* Hiệu ứng mượt khi hover */
        }

        .small-images-container::-webkit-scrollbar-thumb:hover {
            background-color: #555;
            /* Đổi màu khi hover */
        }

        /* Ảnh nhỏ */
        .small-image {
            width: 50px;
            /* Chiều rộng ảnh */
            height: auto;
            /* Tự điều chỉnh chiều cao theo tỉ lệ */
            margin-right: 10px;
            /* Khoảng cách giữa các ảnh */
            cursor: pointer;
            /* Con trỏ chuột dạng "tay" khi hover */
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            /* Hiệu ứng mượt */
            border-radius: 5px;
            /* Bo góc cho ảnh */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            /* Đổ bóng nhẹ */
        }

        .small-image:hover {
            transform: scale(1.1);
            /* Phóng to khi hover */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            /* Đổ bóng nổi bật */
        }

        .small-image {
            border: 2px solid transparent;
            opacity: 0.7;
            transition: border 0.3s, opacity 0.3s;
        }

        .small-image:hover {
            opacity: 1;
        }

        .small-image.active {
            border-color: #007bff;
            /* Màu viền cho ảnh active */
            opacity: 1;
        }

        .product-description {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            /* Giới hạn số dòng là 2 */
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            /* Thêm ba dấu chấm (...) */
            line-height: 1.5;
            /* Điều chỉnh chiều cao dòng */
            max-height: 3em;
            /* Tương ứng với 2 dòng (1.5em mỗi dòng) */
        }
    </style>

    <!-- Product Section -->
    <div class="container py-5" ng-controller="ctrldanhmucID">
        <h2 class="text-center mb-4" ng-if="products.length === 0">Không có sản phẩm...</h2>
        <div class="product-row">
            <div class="card product-card shadow-lg" ng-repeat="sanpham in products">
                <img ng-src="@{{ sanpham.images[0].url }}" class="card-img-top" alt="iPhone SE">
                <span class="sale-badge"><i class='bx bx-tag icon-sale'></i>@{{ sanpham.price_percent }}% OFF</span>
                <div class="card-body">
                    <span class="date-added">Ngày thêm: @{{ sanpham.updated_at | date: 'dd/MM/yyyy' }}</span>
                    <h5 class="card-title">
                        <a class="text-decoration-none" style="cursor: pointer" ng-click="openProductDetail(sanpham.id)">
                            @{{ sanpham.name }}
                        </a>
                    </h5>
                    {{-- <p class="card-text product-description">256GB, Dynamic Island</p> --}}
                    <p class="card-text product-description">@{{ sanpham.mota }}</p>
                    <h6 class="text-success fw-bold">
                        <span class="text-price">@{{ sanpham.price_sale | number }} VND</span></br>
                        <span class="text-price-sale text-muted text-decoration-line-through">@{{ sanpham.price | number }}
                            VND</span>
                    </h6>
                    <a class="btn btn-primary btn-buy"><i class='bx bx-cart'></i> Add to Cart</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Chi Tiết Sản Phẩm -->
    <div class="modal fade" id="productDetailModal" tabindex="-1" aria-labelledby="productDetailModalLabel"
        aria-hidden="true" ng-controller="ctrlDetail">
        <div class="modal-dialog modal-xl"> <!-- Bạn có thể đổi thành modal-lg hoặc tùy chỉnh CSS -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productDetailModalLabel">Chi Tiết Sản Phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Section Hình ảnh -->
                        <div class="col-md-6">
                            <div id="productImages" class="d-flex justify-content-center">
                                <img ng-src="@{{ sanphamDetail.images[0].url }}" alt="Main Product Image" id="mainProductImage"
                                    class="img-fluid" style="max-width: 100%;">
                            </div>
                            <div class="d-flex justify-content-center align-items-center mt-3">
                                <!-- Nút điều hướng bên trái -->
                                {{-- <a class="btn btn-light btn-outline-primary border me-2" id="scrollLeft">
                                    <i class='bx bx-chevron-left'></i>
                                </a> --}}
                                <i class='bx bx-chevron-left' id="scrollLeft" style="font-size: 40px;cursor: pointer;"></i>

                                <!-- Vùng chứa ảnh nhỏ -->
                                <div class="small-images-container"
                                    style="overflow-x: auto; white-space: nowrap; max-width: 300px;">
                                    <img ng-src="@{{ image.url }}" alt="Image 1" class="small-image"
                                        style="width: 50px; margin-right: 10px; cursor: pointer;"
                                        ng-repeat="(index, image) in sanphamDetail.images | limitTo: sanphamDetail.images.length - 1 : 1"
                                        ng-class="{'active': $index === 0}" ng-click="changeMainImage(image, index)">
                                </div>
                                <!-- Nút điều hướng bên phải -->
                                {{-- <a class="btn btn-light btn-outline-primary border ms-2" id="scrollRight">
                                    <i class='bx bx-chevron-right'></i>
                                </a> --}}
                                <i class='bx bx-chevron-right' id="scrollRight"
                                    style="font-size: 40px;cursor: pointer;"></i>
                            </div>
                        </div>
                        <!-- Section Thông tin Sản Phẩm -->
                        <div class="col-md-6">
                            <h4 id="productName" class="text-black" style="font-weight: 600">@{{ sanphamDetail.name }}</h4>
                            <p id="productPrice" class="text-muted">
                                <del id="originalPrice" style="font-size: 12px">
                                    @{{ sanphamDetail.price | number }} VND
                                </del> </tap>
                                <span id="salePrice" class="text-success" style="font-weight: 600;font-size: 20px">
                                    @{{ sanphamDetail.price_sale | number }} VND
                                </span>
                            </p>
                            <p id="productTag" class="bx bx-purchase-tag badge bg-warning text-dark text-center"
                                style="font-weight: 600">
                                Giảm @{{ sanphamDetail.price_percent }}%
                            </p>
                            <p class="product-description">@{{ sanphamDetail.mota }}</p>
                            <!-- Sử dụng ng-init để gán giá trị user_id từ server -->
                            {{-- <input type="hidden" ng-bind="sanphamDetail.user_id = '{{ Auth::user()->id }}'" /> --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mt-3">
                                        <label for="ramOption">Chọn RAM:</label>
                                        <select id="ramOption" class="form-select" ng-model="sanphamDetail.ram">
                                            <option value="4GB">4GB</option>
                                            <option value="6GB">6GB</option>
                                            <option value="8GB">8GB</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mt-3">
                                        <label for="romOption">Chọn ROM:</label>
                                        <select id="romOption" class="form-select" ng-model="sanphamDetail.rom">
                                            <option value="64GB">64GB</option>
                                            <option value="128GB">128GB</option>
                                            <option value="256GB">256GB</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mt-3">
                                        <label for="colorOption">Chọn Màu:</label>
                                        <select id="colorOption" class="form-select" ng-model="sanphamDetail.color">
                                            <option value="Black">Đen</option>
                                            <option value="White">Trắng</option>
                                            <option value="Blue">Xanh</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <!-- Số Lượng -->
                                    <div class="mt-3">
                                        <label for="quantityInput">Số Lượng:</label>
                                        <div class="input-group" style="width: 120px;">
                                            <button class="btn btn-outline-secondary" id="decreaseQuantity">-</button>
                                            <input type="number" class="form-control text-center" value="1"
                                                min="1" max="10" id="quantityInput"
                                                ng-model="sanphamDetail.quantity">
                                            <button class="btn btn-outline-secondary" id="increaseQuantity">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Nút Thêm Vào Giỏ Hàng và Mua Ngay -->
                            <div class="mt-3">
                                <!-- Nút Thêm Vào Giỏ Hàng, gọi hàm addToCart và truyền user_id -->
                                @auth
                                    {{-- <button class="btn btn-success w-100" ng-click="addToCart(sanphamDetail.user_id)">Thêm Vào
                                        Giỏ Hàng</button> --}}
                                    <button class="btn btn-success w-100" ng-click="addToCart()">Thêm Vào
                                        Giỏ Hàng</button>
                                @else
                                    <button class="btn btn-secondary w-100" onclick="showLoginAlert()">Thêm
                                        Vào
                                        Giỏ Hàng</button>
                                @endauth
                                <button class="btn btn-danger w-100 mt-2" ng-click="buy()">Mua Ngay</button>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Mô Tả Sản Phẩm và Comment -->
                    <ul class="nav nav-tabs mt-3" id="productTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="productDescriptionTab" data-bs-toggle="tab"
                                href="#productDescription" role="tab" aria-controls="productDescription"
                                aria-selected="true">Mô Tả</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="productCommentTab" data-bs-toggle="tab" href="#productComment"
                                role="tab" aria-controls="productComment" aria-selected="false">Comment</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="productTabContent">
                        <div class="tab-pane fade show active" id="productDescription" role="tabpanel"
                            aria-labelledby="productDescriptionTab">
                            <p>@{{sanphamDetail.mota}}</p>
                        </div>
                        <div class="tab-pane fade" id="productComment" role="tabpanel"
                            aria-labelledby="productCommentTab">
                            <p>Các bình luận về sản phẩm... </p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
@endsection
