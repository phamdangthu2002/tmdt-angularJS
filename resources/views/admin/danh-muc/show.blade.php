@extends('admin.layouts.index')

@section('content')
    @include('admin.layouts.breadcrumb')
    <style>
        .badge .badge-success {
            color: #4CAF50 !important;
        }

        .badge .badge-danger {
            color: #f44336 !important;
        }
    </style>
    <div class="container" ng-controller="CategoryController">
        <div class="row">
            <div class="col-md-6">
                <div class="text-center" ng-show="isLoading">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                
                <h2 class="mt-4">Danh Sách Danh Mục</h2>
                <div ng-if="categories.length === 0" class="alert alert-warning">
                    Không có danh mục nào.
                </div>
                <table class="table table-bordered" ng-if="categories.length > 0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên Danh Mục</th>
                            <th>Mô Tả</th>
                            <th>Trạng Thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="category in categories">
                            <td>@{{ category.id }}</td>
                            <td>@{{ category.name }}</td>
                            <td>@{{ category.description || 'Không có mô tả' }}</td>
                            <td>
                                <span ng-if="category.trangthai === 'active'" class="badge text-success">Kích hoạt</span>
                                <span ng-if="category.trangthai === 'inactive'" class="badge text-danger">Khóa</span>
                            </td>

                            <td>
                                <button ng-click="editCategory(category.id)" class="btn btn-warning">
                                    <i class="bx bx-edit"></i> Sửa
                                </button>
                                <button ng-click="deleteCategory(category.id)" class="btn btn-danger">
                                    <i class="bx bx-trash"></i> Xóa
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <div class="container">
                    <h2 class="mt-4">Chỉnh sửa Danh Mục</h2>
                    <form ng-submit="updateDanhmuc()">
                        <div class="mb-3">
                            <label for="name" class="form-label">Tên Danh Mục</label>
                            <input type="text" class="form-control" id="name" ng-model="category.name">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Mô Tả</label>
                            <textarea class="form-control" id="description" ng-model="category.description"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="trangthai" class="form-label">Trạng Thái</label>
                            <select class="form-control" id="trangthai" ng-model="category.trangthai">
                                <option value="active">Kích hoạt</option>
                                <option value="inactive">Khóa</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary" ng-click="update()">Cập Nhật Danh Mục</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
