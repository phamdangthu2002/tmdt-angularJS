<style>
    .product-description {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        /* Hiển thị tối đa 3 dòng */
        -webkit-box-orient: vertical;
        /* Định dạng box theo chiều dọc */
        overflow: hidden;
        /* Ẩn phần nội dung vượt quá */
        text-overflow: ellipsis;
        /* Hiển thị dấu ba chấm khi tràn */
        white-space: normal;
        /* Đảm bảo văn bản có thể ngắt dòng */
    }
</style>
<div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true"
    ng-controller="ctrlCart">
    <div class="modal-dialog modal-lg modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cartModalLabel">Giỏ Hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="container">
                <div class="modal-body">
                    <div class="container-xxl">
                        <p class="text-center text-success" ng-if="cart.length === 0">Giỏ hàng hiện đang trống.</p>
                        <!-- Sản phẩm mẫu 1 -->
                        <div class="cart-item d-flex align-items-center mb-3" ng-repeat="item in cart">
                            <img ng-src="@{{item.product.images[0].url}}" class="img-thumbnail" alt="iPhone 14 Pro">
                            <div class="item-details flex-grow-1 ms-3">
                                <h6 class="mb-0">@{{ item.product.name }}</h6>
                                <p class="mb-2 text-success product-description">@{{ item.product.mota }}</p>
                                <div class="d-flex align-items-center">
                                    <div class="input-group" style="width: 120px;">
                                        <button class="btn btn-outline-secondary" id="decreaseQuantity">-</button>
                                        <input type="text" class="form-control text-center"
                                            value="@{{ item.quantity }}" min="1" max="10"
                                            id="quantityInput">
                                        <button class="btn btn-outline-secondary" id="increaseQuantity">+</button>
                                    </div>
                                </div>
                                <p class="text-danger mb-0 mt-2">
                                    <!-- Kiểm tra nếu có giá sale thì hiển thị giá sale, nếu không có thì hiển thị giá gốc -->
                                    <span ng-if="item.product.price_sale">
                                        @{{ (item.product.price_sale) | number }} VND
                                    </span>
                                    <span ng-if="!item.product.price_sale">
                                        @{{ (item.product.price) | number }} VND
                                    </span>
                                </p>
                            </div>
                            <button class="btn btn-danger btn-sm bx bx-trash" ng-click="deleteCart(item.sanpham_id)"></button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <h5 class="text-end">Tổng: <span class="text-success" id="cartTotal">@{{ total | number }}
                            VNĐ</span></h5>
                    <div>
                        <a href="#" class="btn btn-primary" ng-if="cart.length !== 0" ng-click="Thanhtoan()">Thanh Toán</a>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
