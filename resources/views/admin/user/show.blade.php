@extends('admin.layouts.index')

@section('content')
    @include('admin.layouts.breadcrumb')
    <div class="container mt-5">
        <h1 class="text-center">Quản Lý Người Dùng</h1>
        <div class="text-center" ng-show="isLoading">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <div ng-if="users.length === 0" class="alert alert-warning">
            Không có user nào.
        </div>
        <!-- Bảng danh sách người dùng -->
        <div class="card shadow mt-4" ng-controller="userController">
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Họ Tên</th>
                            <th>Email</th>
                            <th>Số Điện Thoại</th>
                            <th>Ngày Sinh</th>
                            <th>Quyền</th>
                            <th>Trạng Thái</th>
                            <th>Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="user in users">
                            <td>@{{ $index + 1 }}</td>
                            <td>@{{ user.name }}</td>
                            <td>@{{ user.email }}</td>
                            <td>@{{ user.phone }}</td>
                            <td>@{{ user.ngaysinh }}</td>
                            <td>@{{ user.role }}</td>
                            <td>
                                <span class="badge text-success" ng-if="user.trangthai === 'active'">Hoạt động</span>
                                <span class="badge text-danger" ng-if="user.trangthai === 'inactive'">Khóa</span>
                            </td>
                            <td>
                                <button class="btn btn-warning btn-sm" ng-click="editUser(user.id)">Sửa</button>
                                <button class="btn btn-danger btn-sm" ng-click="deleteUser(user.id)">Xóa</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal thêm/sửa người dùng -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true"
        ng-controller="ctrUserEdit">
        <div class="modal-dialog modal-lg modal-fullscreen-lg-down">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">Chỉnh Sửa Người Dùng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="container">
                    <div class="modal-body">
                        <form>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Họ Tên</label>
                                        <input type="text" class="form-control" ng-model="userEdit.name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" ng-model="userEdit.email">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Số Điện Thoại</label>
                                        <input type="text" class="form-control" ng-model="userEdit.phone">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Ngày Sinh</label>
                                        <input type="date" class="form-control" ng-model="userEdit.ngaysinh">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Password</label>
                                        <input type="password" class="form-control" ng-model="userEdit.password_new"
                                            placeholder="Nếu có thay đổi">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Giới tính</label>
                                        <select class="form-select" ng-model="userEdit.gioitinh">
                                            <option value="male">Nam</option>
                                            <option value="female">Nữ</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Quyền</label>
                                        <select class="form-select" ng-model="userEdit.role">
                                            <option value="admin">Admin</option>
                                            <option value="user">User</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Trạng Thái</label>
                                        <select class="form-select" ng-model="userEdit.trangthai">
                                            <option value="active">Hoạt động</option>
                                            <option value="inactive">Khóa</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Địa chỉ</label>
                                <textarea class="form-control" name="diachi" id="diachi" cols="30" rows="10" ng-model="userEdit.diachi"></textarea>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary" ng-click="saveUser(userEdit.id)">Lưu</button>
                </div>
            </div>
        </div>
    </div>
@endsection
