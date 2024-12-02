@extends('admin.layouts.index')

@section('content')
    @include('admin.layouts.breadcrumb')
    <div class="container mt-5" ng-controller="ctrlShowTrangthai">
        <div class="row">
            <h2 class="mb-4">Danh Sách Trạng Thái</h2>
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <table class="table table-bordered table-hover">
                            <thead class="table-active">
                                <tr>
                                    <th>ID</th>
                                    <th>Tên Trạng Thái</th>
                                    <th>Mô tả</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Sử dụng vòng lặp để hiển thị danh sách trạng thái -->
                                <tr ng-repeat="trangthai in trangthais">
                                    <td>@{{trangthai.id}}</td>
                                    <td>@{{trangthai.name}}</td>
                                    <td>@{{trangthai.description}}</td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" ng-click="SuaTrangthai(trangthai.id)">Sửa</button>
                                        <button class="btn btn-danger btn-sm" ng-click="DeleteTrangthai(trangthai.id)">Xóa</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h4>Sửa Trạng Thái</h4>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="mb-3">
                                <label for="name" class="form-label">Tên Trạng Thái</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    ng-model="trangthai.name" placeholder="Nhập tên trạng thái">
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Mô tả</label>
                                <input type="text" class="form-control" id="description" name="description"
                                    ng-model="trangthai.description" placeholder="Nhập mô tả trạng thái">
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-success" ng-click="Update(trangthai.id)">Thêm</button>
                                <button type="reset" class="btn btn-secondary">Làm mới</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
