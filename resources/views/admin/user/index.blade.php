@extends('admin.layouts.index')

@section('content')
    @include('admin.layouts.breadcrumb')
    <div class="container mt-5" ng-controller="userController">
        <h2 class="mb-4">Thêm Người Dùng Mới</h2>

        <!-- Hiển thị thông báo thành công -->
        <div class="alert alert-success" ng-if="successMessage" role="alert">
            @{{ successMessage }}
        </div>

        <!-- Form thêm user -->
        <form name="userForm" ng-submit="submitForm()" novalidate>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Tên:</label>
                        <input type="text" class="form-control" id="name" ng-model="user.name">
                        <div class="text-danger" ng-if="errors.name">
                            @{{ errors.name[0] }}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="text" class="form-control" id="email" ng-model="user.email">
                        <div class="text-danger" ng-if="errors.email">
                            @{{ errors.email[0] }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password">Mật khẩu:</label>
                        <input type="password" class="form-control" id="password" ng-model="user.password">
                        <div class="text-danger" ng-if="errors.password">
                            @{{ errors.password[0] }}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password_confirmation">Xác nhận mật khẩu:</label>
                        <input type="password" class="form-control" id="password_confirmation"
                            ng-model="user.password_confirmation">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="role">Vai trò:</label>
                        <select class="form-control" id="role" ng-model="user.role">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                        <div class="text-danger" ng-if="errors.role">
                            @{{ errors.role[0] }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="phone">Số điện thoại:</label>
                        <input class="form-control" id="phone" ng-model="user.phone" />
                        <div class="text-danger" ng-if="errors.phone">
                            @{{ errors.phone[0] }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="trangthai">Trang thái:</label>
                        <select class="form-control" id="trangthai" ng-model="user.trangthai">
                            <option value="active">Kích hoạt</option>
                            <option value="inactive">Khóa</option>
                        </select>
                        <div class="text-danger" ng-if="errors.trangthai">
                            @{{ errors.trangthai[0] }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="ngaysinh">Ngày sinh:</label>
                        <input type="date" id="ngaysinh" class="form-control" placeholder="Chọn ngày"
                            ng-model="user.ngaysinh" ng-pattern="/\d{4}-\d{2}-\d{2}/">
                            <div class="text-danger" ng-if="errors.ngaysinh">
                                @{{ errors.ngaysinh[0] }}
                            </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="gioitinh">Giới tính:</label>
                        <select class="form-control" id="gioitinh" ng-model="user.gioitinh">
                            <option value="male">Nam</option>
                            <option value="famale">Nữ</option>
                        </select>
                        <div class="text-danger" ng-if="errors.gioitinh">
                            @{{ errors.gioitinh[0] }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="diachi">Địa chỉ</label>
                        <textarea class="form-control" ng-model="user.diachi" name="diachi" id="diachi" cols="30" rows="6"></textarea>
                        <div class="text-danger" ng-if="errors.diachi">
                            @{{ errors.diachi[0] }}
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Thêm Người Dùng</button>
        </form>
    </div>
@endsection
