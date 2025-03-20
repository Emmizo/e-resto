@extends('layouts.theme')
@section('content')
    <div class="hold-transition login-page">
        <div class="login-box">
            @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {!! session('success') !!}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {!! session('error') !!}
                    <button type="button" class="close" id="alert" data-dismiss="alert" aria-label="Close">
                        <span style="float:right; cursor:pointer" onclick="$('#alert').hide()" aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <!-- /.login-logo -->
            <div class="logo-center">
                <a href="{{ route('login') }}" class="brand-link navbar-white">
                    <div class="login-img2">

                        <img src="{{ asset('assets/images/logo.png') }}" class="logo-img" alt="logo">
                    </div>
                </a>
            </div>
            <div class="card">
                <div class="card-body login-card-body">
                    <p class="login-box-msg">Reset password</p>

                    <form method="POST" id="login-form" action="{{ route('reset-password-post') }}">
                        @csrf
                        <!-- Password Reset Token -->
                        <input type="hidden" name="token" value="{{ $token }}">
                        <input type="hidden" name="email" value="{{ $email }}">
                        <div class="form-group">
                            <label>New Password<span class="required">*</span></label>
                            <div class="form-group-field">
                                <input id="new_password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    value="{{ old('password') }}" autocomplete="password" autofocus required>
                            </div>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Confirm New Password<span class="required">*</span></label>
                            <div class="form-group-field">
                                <input id="password_confirmation" type="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    name="password_confirmation" autocomplete="current-password">
                            </div>

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="row row-padding">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-block" style="float: center;" ;>Change
                                    Password</button>
                            </div>
                        </div>
                        <br>
                        <div class='row'>
                            <div class="col-12">
                                <a href="{{ route('login') }}" class="btn btn-danger btn-block"
                                    style="float: center;" ;>Login</a>
                            </div>
                        </div>
                    </form>

                    <!-- <p class="mb-0">
                            <a href="#" class="text-center">Create an Account</a>
                          </p> -->
                </div>
                <!-- /.login-card-body -->
            </div>
        </div>
    </div>
@endsection
