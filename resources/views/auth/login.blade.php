@extends('user.layouts.index')
@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">
    <div class="container form-container">
        <form class="form" method="POST" action="{{ route('auth.postLogin') }}">
            @csrf
            <div class="flex-column">
                <label>Email</label>
            </div>
            <div class="inputForm">
                <i class="bx bx-envelope"></i>
                <input placeholder="Enter your Email" class="input" type="text" name="email" ng-model="login.email">
            </div>
            @if ($errors->has('email'))
                <p class="error-message text-danger">*
                    {{ $errors->first('email') }}
                </p>
            @endif
            <div class="text-danger" ng-if="errors.email">
                @{{ errors.email[0] }}
            </div>
            <div class="alert alert-danger" ng-if="errors">
                @{{ errors }}
            </div>
            <div class="flex-column">
                <label>Password</label>
            </div>
            <div class="inputForm">
                <i class="bx bx-lock"></i>
                <input placeholder="Enter your Password" class="input" type="password" name="password"
                    ng-model="login.password">
            </div>
            <div class="text-danger" ng-if="errors.password">
                @{{ errors.password[0] }}
            </div>
            @if ($errors->has('password'))
                <p class="error-message text-danger">*
                    {{ $errors->first('password') }}
                </p>
            @endif
            <div class="flex-row">
                <div>
                    <input type="checkbox" name="remember" ng-model="login.remember">
                    <label>Remember me</label>
                </div>
                <span class="span">Forgot password?</span>
            </div>
            <button class="button-submit" type="submit" ng-click="loginUser()">Sign In</button>
            <p class="p">Don't have an account? <span class="span"><a href="/auth/register">Sign Up</a></span></p>
            <p class="p line">Or With</p>

            <div class="flex-row">
                <button class="button google">
                    <i class="bx bxl-google"></i>
                    Google
                </button>
                <button class="button apple">
                    <i class="bx bxl-apple"></i>
                    Apple
                </button>
            </div>
        </form>
    </div>
@endsection
