@extends('admin.layouts.index')

@section('content')
    @include('admin.layouts.breadcrumb')

    <div class="container" ng-controller="ctrlDanhmuc">
        <h2 class="mt-4">Thêm Danh Mục</h2>

        <!-- Form sử dụng ng-submit để gọi hàm thêm danh mục -->
        <form ng-submit="addDanhmuc()">
            <div class="mb-3">
                <label for="name" class="form-label">Tên Danh Mục</label>
                <input type="text" class="form-control" id="name" ng-model="category.name">
                <div ng-if="errors.name" class="text-danger">
                    @{{ errors.name[0] }}
                </div>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Mô Tả</label>
                <textarea class="form-control" id="description" ng-model="category.description"></textarea>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Trạng Thái</label>
                <select class="form-control" id="status" ng-model="category.status">
                    <option value="active">Kích hoạt</option>
                    <option value="inactive">Khóa</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Thêm Danh Mục</button>
        </form>
    </div>
@endsection
