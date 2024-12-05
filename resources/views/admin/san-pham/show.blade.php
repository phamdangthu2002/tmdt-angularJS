@extends('admin.layouts.index')
@section('content')
    @include('admin.layouts.breadcrumb')
    <style>
        .descreption {
            display: -webkit-box;
            -webkit-line-clamp: 4;
            /* Giới hạn số dòng là 2 */
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            /* Thêm ba dấu chấm (...) */
            line-height: 1.5;
            /* Điều chỉnh chiều cao dòng */
            max-height: 6.5em;
            /* Tương ứng với 2 dòng (1.5em mỗi dòng) */
        }
        img{
            width: 100%;
            height: 60px;
            background-size: cover;
            border-radius: 5px;
            margin-top: 15px;
        }
    </style>
    <div class="container mt-5" ng-controller="productController">
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="text-center mb-4">Quản Lý Sản Phẩm</h1>
                <div class="text-center" ng-show="isLoading">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div ng-if="products.length === 0" class="alert alert-warning">
                    Không sản phẩm nào.
                </div>
                <!-- Bảng danh sách sản phẩm -->
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Tên Sản Phẩm</th>
                            <th>Giá</th>
                            <th>Giá Khuyến Mãi</th>
                            <th>Danh mục</th>
                            <th>Mô tả</th>
                            <th>Slug</th>
                            <th>Trong kho</th>
                            <th>Trạng Thái</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="product in products">
                            <td>@{{ product.id }}</td>
                            <td>
                                <img ng-if="product.images.length > 0" ng-src="@{{ product.images[0].url }}"
                                    alt="@{{ product.name }}" width="50">
                            </td>
                            <td>@{{ product.name }}</td>
                            <td>@{{ product.price | currency: 'VND ': 0 }}</td>
                            <td>@{{ product.price_sale | currency: 'VND ': 0 }}</td>
                            <td>@{{ product.category.name }}</td>
                            <td class="descreption">@{{ product.mota }}</td>
                            <td>@{{ product.slug }}</td>
                            <td>@{{ product.quantity_in_stock }}</td>
                            <td>
                                <span ng-if="product.trangthai === 'active'" class="badge text-success">Kích hoạt</span>
                                <span ng-if="product.trangthai === 'inactive'" class="badge text-danger">Khóa</span>
                            </td>
                            <td>
                                <button class="btn btn-warning btn-sm" ng-click="openEditModal(product.id)">Sửa</button>
                                <button class="btn btn-warning btn-sm" ng-click="openEditModalAnh(product.id)">Ảnh</button>
                                <button class="btn btn-danger btn-sm" ng-click="deleteProduct(product.id)">Xóa</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal thêm/sửa sản phẩm -->
    <div class="modal fade" id="productModal" ng-controller="ctrProductEdit" tabindex="-1"
        aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel">Sửa Sản Phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="name" class="form-label">Tên Sản Phẩm</label>
                            <input type="text" id="name" class="form-control" ng-model="productEdit.name">
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Giá</label>
                                    <input type="number" id="price" class="form-control" ng-model="productEdit.price"
                                        ng-model-options="{ allowInvalid: true }">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price_sale" class="form-label">Giá Khuyến Mãi</label>
                                    <input type="number" id="price_sale" class="form-control"
                                        ng-model="productEdit.price_sale">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="trangthai" class="form-label">Trạng Thái</label>
                                    <select id="trangthai" class="form-control" ng-model="productEdit.trangthai">
                                        <option value="active">Kích Hoạt</option>
                                        <option value="inactive">Khóa</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category" class="form-label">Danh Mục</label>
                                    <select id="category" class="form-control" ng-model="productEdit.category_id">
                                        <option ng-repeat="category in categories" value="@{{ category.id }}"
                                            ng-selected="category.id === productEdit.category_id">
                                            @{{ category.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="mota" class="from-label">Mô tả</label>
                            <textarea name="mota" class="form-control" id="mota" cols="30" rows="10" ng-model="productEdit.mota"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary" ng-click="saveProduct(productEdit.id)">Lưu</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal xác nhận xóa -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteLabel">Xác Nhận Xóa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn xóa sản phẩm này không?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteButton">Xóa</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="anhModal" ng-controller="ctrlAnh" tabindex="-1" aria-labelledby="anhModeal"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalAnh">Thêm ảnh cho sản phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form tải lên ảnh -->
                    <form id="uploadImageForm" ng-submit="submitImages(productId)">
                        <div class="form-group">
                            <label for="imageInput">Chọn ảnh</label>
                            <input type="file" id="imageInput" class="form-control" accept="image/*" multiple
                                onchange="angular.element(this).scope().previewImages(this)" ng-model="url">
                        </div>
                        <div class="form-group">
                            <label class="mt-3 mb-3">Danh sách ảnh đã thêm:</label>
                            <div id="existingImages"
                                style="text-align: center; display: flex; flex-wrap: wrap; gap: 10px;">
                                <div ng-repeat="anh in anhs" style="position: relative; display: inline-block;">
                                    <img ng-src="@{{ anh.url }}" alt="Ảnh đã thêm"
                                        style="max-width: 100px; max-height: 100px; display: block; border: 1px solid #ccc;">
                                    <button type="button" class="btn btn-danger btn-sm"
                                        style="position: absolute; top: 0; right: 0; transform: translate(50%, -50%); padding: 2px 5px;"
                                        ng-click="removeExistingImage(anh.id)">
                                        &times;
                                    </button>
                                </div>
                            </div>
                            <p ng-show="!anhs.length" style="text-align: center;">Chưa có ảnh nào</p>
                        </div>

                        <div class="form-group">
                            <label class="mt-3 mb-3">Xem trước ảnh:</label>
                            <div id="imagePreview" style="text-align: center; display: flex; flex-wrap: wrap; gap: 10px;">
                                <div ng-repeat="image in imagePreviews"
                                    style="position: relative; display: inline-block;">
                                    <img ng-src="@{{ image }}" alt="Preview"
                                        style="max-width: 100px; max-height: 100px; display: block; border: 1px solid #ccc;">
                                    <button type="button" class="btn btn-danger btn-sm"
                                        style="position: absolute; top: 0; right: 0; transform: translate(50%, -50%); padding: 2px 5px;"
                                        ng-click="removeImage($index)">
                                        &times;
                                    </button>
                                </div>
                            </div>
                            <p ng-show="!imagePreviews.length" style="text-align: center;">Chưa chọn ảnh nào</p>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success" ng-click="Save(productId)">Lưu</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
