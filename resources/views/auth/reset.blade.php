@extends( 'layouts.theme' )
@section('content')
    <div class="hold-transition login-page">
        <div class="login-box">



            <!-- /.login-logo -->
            @if (session()->has('link_sent'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {!! session('link_sent') !!}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {!! session('error') !!}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <div class="logo-center ">
                <a href="{{ route('login') }}" class="brand-link navbar-white ">
                    <div class="login-img2 ">

                        <img src="{{asset('assets/images/logo.png') }}" class="logo-img" alt="logo">
                    </div>
                </a>
            </div>
            <div class="card">
                <div class="card-body login-card-body">
                    <p class="login-box-msg">You forgot your password? Just enter your email</p>

                    <form method="POST" action="{{ route('forgot-password-post') }}">
                        @csrf
                        @foreach($users as $user)
                        <div class="form-group">
                            <label>Email address<span class="required">*</span></label>
                            <div class="form-group-field">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ $user->email }} " required autocomplete="email" autofocus>
                            </div>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        @endforeach
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">{{ __('Send Reset Link') }}</button>
                        </div>
                    </form>
                    <center>
                        <p class="mb-1">
                          <a href='{{ route('login') }}'>Log In</a>

                        </p>
                    </center>
                </div>
                <!-- /.login-card-body -->
            </div>
        </div>
    </div>
@endsection
