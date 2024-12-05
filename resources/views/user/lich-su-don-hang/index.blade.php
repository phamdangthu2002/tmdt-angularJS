@extends('user.layouts.index')
@section('content')
    <title>{{ $title }}</title>
    <div ng-controller="ctrlHistory">
        <div class="container mt-5">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h2 class="mb-4">Danh sách đơn hàng</h2>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Ngày đặt</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody id="order-list">
                            <tr ng-repeat="history in historys">
                                <td>@{{ history.id }}</td>
                                <td>@{{ history.ngaydathang }}</td>
                                <td>@{{ history.price | number }} VND</td>
                                <td>@{{ history.trangthai.name }}</td>
                                <td>
                                    <button class="btn btn-primary btn-sm" ng-click="loadChitiet(history.id)">
                                        Xem chi tiết
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal chi tiết đơn hàng -->
        <div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="orderDetailsModalLabel">Chi tiết đơn hàng</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-success text-center" ng-if="!chitiets || chitiets.length === 0">Không có sản phẩm</p>
                        <table class="table table-bordered" ng-if="chitiets && chitiets.length > 0">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Số lượng</th>
                                    <th>Giá</th>
                                    <th>RAM</th>
                                    <th>ROM</th>
                                    <th>Color</th>
                                </tr>
                            </thead>
                            <tbody id="order-details">
                                <tr ng-repeat="chitiet in chitiets">
                                    <td>
                                        <img ng-src="@{{ chitiet.product.images[0].url }}" alt="@{{ chitiet.product.name }}"
                                            style="width: 100%;height: 50px; background-size: cover">
                                    </td>
                                    <td>@{{ chitiet.product.name }}</td>
                                    <td>@{{ chitiet.quantity }}</td>

                                    <td ng-if="!chitiet.product.price">@{{ chitiet.product.price | number }} VND</td>
                                    <td ng-if="chitiet.product.price_sale">@{{ chitiet.product.price_sale | number }} VND</td>

                                    <td>@{{ chitiet.ram }}</td>
                                    <td>@{{ chitiet.rom }}</td>
                                    <td>@{{ chitiet.color }}</td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                    <div class="modal-footer">
                        <p class="text-end"><b>Tổng tiền:</b> <span id="order-total"></span>@{{ totalPrice | number }} VND</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
