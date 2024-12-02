@extends('admin.layouts.index')

@section('content')
@include('admin.layouts.breadcrumb')
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="mb-4">Thêm Sản Phẩm</h2>
                <form ng-controller="ctrlProduct" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!-- Tên sản phẩm -->
                    <div class="form-group">
                        <label for="name">Tên Sản Phẩm</label>
                        <input type="text" class="form-control" id="name" ng-model="product.name" name="name">
                    </div>
                    <!-- Giá -->
                    <div class="form-group">
                        <label for="price">Giá</label>
                        <input type="number" class="form-control" id="price" ng-model="product.price" name="price">
                    </div>
                    <!-- Giá khuyến mãi -->
                    <div class="form-group">
                        <label for="price_sale">Giá Khuyến Mãi</label>
                        <input type="number" class="form-control" id="price_sale" ng-model="product.price_sale"
                            name="price_sale">
                    </div>
                    <!-- Mô tả -->
                    <div class="form-group">
                        <label for="mota">Mô Tả</label>
                        <textarea class="form-control" id="mota" ng-model="product.mota" name="mota"></textarea>
                    </div>
                    <!-- Trạng thái -->
                    <div class="form-group">
                        <label for="trangthai">Trạng Thái</label>
                        <select class="form-control" id="trangthai" ng-model="product.trangthai" name="trangthai">
                            <option value="active">Kích Hoạt</option>
                            <option value="inactive">Khóa</option>
                        </select>
                    </div>
                    <!-- Số lượng trong kho -->
                    <div class="form-group">
                        <label for="quantity_in_stock">Số Lượng Trong Kho</label>
                        <input type="number" class="form-control" id="quantity_in_stock"
                            ng-model="product.quantity_in_stock" name="quantity_in_stock">
                    </div>
                    <!-- Chọn danh mục -->
                    <div class="form-group">
                        <label for="category">Danh Mục</label>
                        <select class="form-control" id="category" name="category_id" ng-model="product.category_id">
                            <option value="">Chọn danh mục</option>
                            <option ng-repeat="danhmuc in danhMuc" value="@{{ danhmuc.id }}">@{{ danhmuc.name }}
                            </option>
                        </select>
                    </div>
                    <!-- Chọn và xem trước hình ảnh -->
                    <div class="form-group mt-3">
                        <label for="image">Hình Ảnh</label>
                        <input type="file" class="form-control-file" id="image" name="image"
                            onchange="previewImage(event)">
                        <input type="text" name="file" id="file" ng-model="product.file">
                    </div>

                    <!-- Xem trước ảnh -->
                    <div class="form-group" id="imagePreviewContainer" style="display: none;">
                        <img id="imagePreview" alt="Xem Trước Ảnh" class="img-thumbnail" style="max-width: 200px;">
                    </div>

                    <!-- Nút thêm sản phẩm -->
                    <button type="submit" class="btn btn-primary mt-3" ng-click="addSanpham()">Thêm Sản Phẩm</button>
                    <button type="reset" class="btn btn-secondary mt-3">Làm mới</button>

                </form>
            </div>
        </div>
    </div>
    <script>
        window.previewImage = function(event) {
            var reader = new FileReader();
            var file = event.target.files[0];

            if (file) {
                // Hiển thị hình ảnh xem trước
                reader.onload = function() {
                    var imagePreview = document.getElementById('imagePreview');
                    imagePreview.src = reader.result;
                    document.getElementById('imagePreviewContainer').style.display = 'block';
                };
                reader.readAsDataURL(file);

                // Hiển thị tên file và lưu vào product.image
                document.getElementById('file').textContent = file.name;
            }
        }
    </script>
@endsection
