@extends('layouts.theme')
@section('content')
    <!-- Forgot Password -->
    <div class="login-wrapper">
        <div class="login-content">
            <div class="row m-0">
                <!-- Left Side - App Info Panel -->
                <div class="col-xl-7 col-lg-6 col-sm-12 p-0 d-sm-none d-lg-block">
                    <div class="district-left-content position-relative h-100">
                        <div class="login-left position-relative d-flex align-items-start justify-content-between flex-column h-100 animate__animated animate__fadeIn">
                            <div class="login-left-top mb-4">
                                <a href="javascript:;" class="district-logo animate__animated animate__bounceIn animate__delay-1s" title="RestoFinder">
                                    <img src="{{asset('assets/images/logo.png')}}" alt="RestoFinder" width="96" height="134" class="district-logo-img">
                                </a>
                            </div>
                            <div class="login-left-bottom animate__animated animate__fadeInUp animate__delay-1s">
                                <h2 class="font-dmsans fw-bold medium text-white mb-4">Welcome to the RestoFinder app</h2>
                                <p class="font-dmsans fw-normal text-white lh-base">RestoFinder is an AI-Based Restaurant Recommendation System that helps users discover restaurants around their location using geolocation technology, providing personalized dining suggestions based on preferences and real-time availability.</p>
                                <ul class="login-content-list my-3 ps-2">
                                    <li>
                                        <p class="font-dmsans fw-normal text-white lh-base position-relative ps-3">
                                            <span>Personalized Restaurant Recommendations</span>
                                        </p>
                                    </li>
                                    <li>
                                        <p class="font-dmsans fw-normal text-white lh-base position-relative ps-3">
                                            <span>Geolocation-Based Nearby Suggestions</span>
                                        </p>
                                    </li>
                                    <li>
                                        <p class="font-dmsans fw-normal text-white lh-base position-relative ps-3">
                                            <span>AI-Powered Meal Recommendations</span>
                                        </p>
                                    </li>
                                    <li>
                                        <p class="font-dmsans fw-normal text-white lh-base position-relative ps-3">
                                            <span>Restaurant Ambiance Filtering</span>
                                        </p>
                                    </li>
                                    <li>
                                        <p class="font-dmsans fw-normal text-white lh-base position-relative ps-3">
                                            <span>Real-Time Table Availability</span>
                                        </p>
                                    </li>
                                </ul>
                                <div class="store-logo d-flex align-items-center mt-4">
                                    <a href="https://play.google.com/store" class="me-4 animate__animated animate__fadeInLeft animate__delay-2s" title="Google Play Store" target="_blank">
                                        <figure class="m-0">
                                            <img src="{{asset('assets/images/play-store.png')}}" alt="Google Play Store" width="174" height="53">
                                        </figure>
                                    </a>
                                    <a href="https://apps.apple.com" class="me-4 animate__animated animate__fadeInRight animate__delay-2s" title="App Store" target="_blank">
                                        <figure class="m-0">
                                            <img src="{{asset('assets/images/app-store.png')}}" alt="App Store" width="174" height="53">
                                        </figure>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Forgot Password Form -->
                <div class="col-xl-5 col-lg-6 col-sm-12 p-0 bg-white">
                    <div class="district-right-content position-relative h-100">
                        <div class="login-right position-relative d-flex align-items-start justify-content-between flex-column h-100 mx-auto">
                            <div class="login-right-top d-flex align-items-center justify-content-center flex-column mb-4">
                                <div class="login-heading mb-4">
                                    <h2 class="font-dmsans fw-bold medium text-dark-v2 mb-1">Forgot Password</h2>
                                </div>

                                <div id="message-container">
                                    @if (session()->has('link_sent'))
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            {!! session('link_sent') !!}
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        </div>
                                    @endif
                                    @if (session()->has('error'))
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            {!! session('error') !!}
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        </div>
                                    @endif
                                </div>

                                <p class="text-center mb-4">You forgot your password? Just enter your email address below and we'll send you a link to reset your password.</p>

                                <form method="POST" action="{{ route('forgot-password-post') }}" id="forgot-password-form">
                                    @csrf

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group m-0 mb-4">
                                                <label for="email" class="form-label">Email Address <span class="asterik">*</span></label>
                                                @foreach($users as $user)
                                                <input type="email" class="form-control rounded-3 @error('email') is-invalid @enderror" id="email"
                                                    placeholder="Enter Email Address" name="email" value="{{ $user->email }}" required autocomplete="email" autofocus>
                                                @endforeach
                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary btn-small fw-semibold text-uppercase rounded-3 w-100 text-center" id="send_btn">
                                                <span>Send Password Reset Link</span>
                                            </button>
                                        </div>

                                        <div class="col-md-12 mt-3">
                                            <div class="m-0 mb-3 text-center">
                                                <a href="{{route('login')}}" class="font-dmsans fw-medium small text-secondary position-relative d-inline-block" title="Back to Login">Back to Login</a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="login-right-bottom d-flex flex-column justify-content-end w-100">
                                <ul class="login-content-links d-flex align-items-center justify-content-center flex-wrap my-3 my-xl-0">
                                    <li>
                                        <a href="#" class="font-dmsans fw-medium xsmall text-decoration-underline link-offset-2 position-relative d-inline-block" title="Contact us">Contact us</a>
                                    </li>
                                    <li>
                                        <a href="#" class="font-dmsans fw-medium xsmall text-decoration-underline link-offset-2 position-relative d-inline-block" title="Privacy Policy">Privacy Policy</a>
                                    </li>
                                    <li>
                                        <a href="#" class="font-dmsans fw-medium xsmall text-decoration-underline link-offset-2 position-relative d-inline-block" title="Terms of Service">Terms of Service</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>

<script>
$(document).ready(function() {
    $('#forgot-password-form').validate({
        rules: {
            email: {
                required: true,
                email: true
            }
        },
        messages: {
            email: {
                required: "Please enter your email address",
                email: "Please enter a valid email address"
            }
        },
        errorElement: 'span',
        errorPlacement: function(error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function(element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element) {
            $(element).removeClass('is-invalid');
        },
        submitHandler: function(form) {
            $('#send_btn').html("<i class='fa fa-spin fa-spinner'></i> Sending");
            $('#send_btn').prop('disabled', true);
            form.submit();
        }
    });
});
</script>
@endsection
