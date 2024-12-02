@extends('admin.layouts.index')
@section('content')
    @include('admin.layouts.breadcrumb')
    <div class="container mt-5" ng-controller="ctrlTrangthai">
        <div class="card shadow-sm">
            <div class="card-header">
                <h4>Thêm Trạng Thái</h4>
            </div>
            <div class="card-body">
                <form>
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên Trạng Thái</label>
                        <input type="text" class="form-control" id="name" name="name" ng-model="trangthai.name" placeholder="Nhập tên trạng thái">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả</label>
                        <input type="text" class="form-control" id="description" name="description" ng-model="trangthai.description" placeholder="Nhập mô tả trạng thái">
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-success" ng-click="Them()">Thêm</button>
                        <button type="reset" class="btn btn-secondary">Làm mới</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
