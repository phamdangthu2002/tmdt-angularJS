@extends('admin.layouts.index')

@section('content')
    @include('admin.layouts.breadcrumb')
    <div class="container" ng-controller="ctrlDonhang">
        <div class="row">
            <!-- Cột bên trái: Danh sách đơn hàng -->
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2>Danh Sách Đơn Hàng</h2>
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="table-active">
                                <tr>
                                    <th>Mã Đơn</th>
                                    <th>Người Mua</th>
                                    <th>Tổng Tiền</th>
                                    <th>Trạng Thái</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="donhang in donhangs">
                                    <td>@{{ donhang.id }}</td>
                                    <td>@{{ donhang.user.name }}</td>
                                    <td>@{{ donhang.price | number }} VND</td>
                                    <td>
                                        <span class="badge"
                                            ng-class="{
                                            'text-black': donhang.trangthai.id === 1,
                                            'text-warning': donhang.trangthai.id === 2,
                                            'text-success': donhang.trangthai.id >= 3 && donhang.trangthai.id <= 6,
                                            'text-danger': donhang.trangthai.id === 7 || donhang.trangthai.id === 8 }">
                                            @{{ donhang.trangthai.name }}
                                        </span>

                                    </td>
                                    <td>
                                        <a class="btn btn-warning" ng-click="chiTiet(donhang.id)"><i
                                                class="bx bx-edit"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="pagination">
                    <!-- Nút "Trang trước" -->
                    <a ng-disabled="!pagination.prev_page_url" ng-click="fetchData(pagination.current_page - 1)"
                        class="btn btn-secondary">
                        <i class='bx bx-chevron-left'></i> 
                    </a>

                    <!-- Hiển thị số trang -->
                    <span>Trang @{{ pagination.current_page }} / @{{ pagination.last_page }}</span>

                    <!-- Nút "Trang kế tiếp" -->
                    <a ng-disabled="!pagination.next_page_url" ng-click="fetchData(pagination.current_page + 1)"
                        class="btn btn-secondary">
                        <i class='bx bx-chevron-right'></i>
                    </a>
                </div>

            </div>

            <!-- Cột bên phải: Thông tin chi tiết đơn hàng và cập nhật trạng thái -->
            <div class="col-md-6" id="order-details">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h2>Chi Tiết Đơn Hàng</h2>
                                <table class="table table-bordered" id="product-list">
                                    <thead>
                                        <tr>
                                            <th>Mã Đơn Hàng:</th>
                                            <th>Người Mua:</th>
                                            <th>Địa Chỉ:</th>
                                            <th>Số Điện Thoại:</th>
                                            <th>Tổng Tiền:</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>@{{ donhang.id }}</td>
                                            <td>@{{ donhang.user.name }}</td>
                                            <td>@{{ donhang.user.diachi }}</td>
                                            <td>@{{ donhang.user.phone }}</td>
                                            <td>@{{ donhang.price | number }} VND</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card shadow-sm mt-2">
                            <div class="card-body">
                                <h4>Sản Phẩm Đặt Mua</h4>
                                <table class="table table-bordered" id="product-list">
                                    <thead>
                                        <tr>
                                            <th>Tên Sản Phẩm</th>
                                            <th>Giá</th>
                                            <th>Số Lượng</th>
                                            <th>Tổng Tiền</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="sanpham in chitietdonhang">
                                            <td>@{{ sanpham.product.name }}</td>
                                            <td>@{{ sanpham.price_sale ? (sanpham.price_sale | number) : (sanpham.price | number) }} VND</td>
                                            <td>@{{ sanpham.quantity || 0 }}</td> <!-- Nếu quantity không có, hiển thị 0 -->
                                            <td>@{{ sanpham.price | number }} VND</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <h4>Cập Nhật Trạng Thái</h4>
                        <form id="status-form">
                            <div class="mb-3">
                                <label for="status" class="form-label">Trạng Thái</label>
                                <div ng-show="trangthai_id !== 6 && trangthai_id !== 7 && trangthai_id !== 8">
                                    <select ng-model="trangthai_id" class="form-control">
                                        <option ng-repeat="trangthai in trangthais" value="@{{ trangthai.id }}"
                                            ng-selected="trangthai_id === trangthai.id">
                                            @{{ trangthai.name }}
                                        </option>
                                    </select>
                                    <!-- Hiển thị lỗi nếu có -->
                                    <div ng-if="errors" class="alert alert-danger">
                                        @{{ errors.id[0] }}
                                    </div>
                                </div>

                                <div ng-show="trangthai_id === 6 || trangthai_id === 7 || trangthai_id === 8"
                                    class="text-center text-success">
                                    <p>Hoàn tất đơn hàng</p>
                                </div>
                            </div>
                            <button ng-hide="trangthai_id === 6 || trangthai_id === 7 || trangthai_id === 8" type="submit"
                                class="btn btn-primary" ng-click="updateDonhang(donhang.id)">Cập Nhật</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
