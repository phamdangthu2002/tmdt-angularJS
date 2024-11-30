@extends('user.layouts.index')
@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">
    <div class="container form-container" ng-controller="ctrlRegister">
        <form class="form">
            <div class="flex-column">
                <label>Full Name</label>
            </div>
            <div class="inputForm">
                <i class="bx bx-user"></i>
                <input placeholder="Enter your Full Name" class="input" type="text" ng-model="register.name">
            </div>
            <div class="text-danger" ng-if="errors.name">
                @{{ errors.name[0] }}
            </div>

            <div class="flex-column">
                <label>Email</label>
            </div>
            <div class="inputForm">
                <i class="bx bx-envelope"></i>
                <input placeholder="Enter your Email" class="input" type="text" ng-model="register.email">
            </div>
            <div class="text-danger" ng-if="errors.email">
                @{{ errors.email[0] }}
            </div>

            <div class="flex-column">
                <label>Password</label>
            </div>
            <div class="inputForm">
                <i class="bx bx-lock"></i>
                <input placeholder="Enter your Password" class="input" type="password" ng-model="register.password">
            </div>
            <div class="text-danger" ng-if="errors.password">
                @{{ errors.password[0] }}
            </div>

            <div class="flex-column">
                <label>Confirm Password</label>
            </div>
            <div class="inputForm">
                <i class="bx bx-lock-alt"></i>
                <input placeholder="Confirm your Password" class="input" type="password"
                    ng-model="register.password_confirmation">
            </div>

            <button class="button-submit" ng-click="registerUser()">Sign Up</button>
            <p class="p">Already have an account? <span class="span"><a href="/auth/login">Sign In</a></span></p>
        </form>
    </div>
@endsection
