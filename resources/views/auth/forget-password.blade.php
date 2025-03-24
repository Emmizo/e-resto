@extends('layouts.theme')
<body>
    <!-- Login -->
    <div class="login-wrapper">
        <div class="login-content">

            <div class="row m-0">
                <div class="col-xl-7 col-lg-6 col-sm-12 p-0 d-sm-none d-lg-block">
                    <div class="district-left-content position-relative h-100">
                        <div class="login-left position-relative d-flex align-items-start justify-content-between flex-column h-100 animate__animated animate__fadeIn">
                            <div class="login-left-top mb-4">
                                <a href="javascript:;" class="district-logo animate__animated animate__bounceIn animate__delay-1s" title="FoodFinder">
                                    <img src="{{asset('assets/images/logo.png')}}" alt="FoodFinder" width="96" height="134" class="district-logo-img">
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
                                    <li>
                                        <p class="font-dmsans fw-normal text-white lh-base position-relative ps-3">
                                            <span>Table Reservation System</span>
                                        </p>
                                    </li>
                                    <li>
                                        <p class="font-dmsans fw-normal text-white lh-base position-relative ps-3">
                                            <span>Restaurant Owner Dashboard</span>
                                        </p>
                                    </li>
                                </ul>
                                <p class="font-dmsans fw-normal text-white lh-base">The RestoFinder App will serve to provide you with access to nearby restaurants, personalized meal suggestions, user reviews, and convenient table reservations based on your dining preferences and location.</p>
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
                <div class="col-xl-5 col-lg-6 col-sm-12 p-0 bg-white">
                    <div class="district-right-content position-relative h-100">
                        <div class="login-right position-relative d-flex align-items-start justify-content-between flex-column h-100 mx-auto">
                            <div class="login-right-top d-flex align-items-center justify-content-center flex-column mb-4">
                                <div class="login-heading mb-4">
                                    <h2 class="font-dmsans fw-bold medium text-dark-v2 mb-1">Forget Password</h2>
                                </div>
                                @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {!! session('error') !!}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
                                <form  method="POST" action="" id="forgot-form">

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group m-0 mb-3">
                                                <label for="loginEmail" class="form-label">Email Address <span class="asterik">*</span></label>
                                                <input type="email" class="form-control rounded-3" id="loginEmail" placeholder="Enter Email Address" name="email" required>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary btn-small fw-semibold text-uppercase rounded-3 w-100 text-center">
                                                <span>Send Password Reset Link</span>
                                            </button>
                                        </div>
                                        <div class="col-md-12 mt-3">
                                            <div class="m-0 mb-3 text-start">
                                                <a href="{{route('login')}}" class="font-dmsans fw-medium small text-secondary position-relative d-inline-block" title="Login">Login</a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="login-right-bottom d-flex flex-column justify-content-end w-100">
                                <ul class="login-content-links d-flex align-items-center justify-content-center flex-wrap my-3 my-xl-0">
                                    <li>
                                        <a href="https://www.necadistrict10.com/user/contact" class="font-dmsans fw-medium xsmall text-decoration-underline link-offset-2 position-relative d-inline-block" title="Contact us" target="_blank">Contact us</a>
                                    </li>
                                    <li>
                                        <a href="https://www.necadistrict10.com/user/directory" class="font-dmsans fw-medium xsmall text-decoration-underline link-offset-2 position-relative d-inline-block" title="Directory" target="_blank">Directory</a>
                                    </li>
                                    <li>
                                        <a href="https://www.necadistrict10.com/user/member-projects" class="font-dmsans fw-medium xsmall text-decoration-underline link-offset-2 position-relative d-inline-block" title="Member Projects" target="_blank">Member Projects</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
  </body>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>

<script>
    //login
$('#forgot-form').validate({
    rules: {

        email: {
            required: true,
            email: true,
        },




    },
    messages: {

        email: {
            required: "Please enter an email address",
            email: "Please enter a valid email address",
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
        // Add submit handler to prevent default form submission and handle via AJAX
        submitHandler: function(form, e) {
        e.preventDefault();

        var form_data = new FormData();


                $('#forgot-form input').each(function(i, e) {
                var getID = $(this).attr('id');
                var name = $(this).attr('name');
                form_data.append(name, $("#" + getID).val());
                });



        // Set up CSRF token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Make AJAX request
        $.ajax({
            url: "{{ route('forgot-password-post') }}", // Use form action or fallback
            type: "POST",
            dataType: "json",
            data: form_data,

            cache: false,
            contentType: false,
            processData: false,

            beforeSend: function() {
                $('#send_btn').html("<i class='fa fa-spin fa-spinner'></i> Submit");
                $('#loader').show();
                $('#send_btn').prop('disabled', true);
                $('.alert-dismissible').hide(); // Hide any previous alerts
            },
            success: function(result) {
                console.log(result.status);

                if (result.status == '201') {
                    // Show success message before redirect
                    $('.alert-dismissible').removeClass('alert-danger').addClass('alert-success');
                    $('.alert-dismissible').html('logged in successful! Redirecting to dashboard...');
                    $('.alert-dismissible').show();

                    // Redirect after a short delay
                    setTimeout(function() {
                        // window.location.href = result.redirect || '/login';
                    }, 1500);
                } else {
                    // Show error message
                    $('.alert-dismissible').removeClass('alert-success').addClass('alert-danger');
                    $('.alert-dismissible').html(result.message || 'An error occurred. Please try again.');
                    $('.alert-dismissible').show();

                    // Reset button
                    $('#send_btn2').html("Register");
                    $('#loader').hide();
                    $('#send_btn2').prop('disabled', false);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);

                // Handle validation errors from Laravel
                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                    var errorMessages = '';
                    $.each(xhr.responseJSON.errors, function(field, errors) {
                        errorMessages += errors.join('<br>') + '<br>';
                    });

                    $('.alert-dismissible').removeClass('alert-success').addClass('alert-danger');
                    $('.alert-dismissible').html(errorMessages);
                } else {
                    // Generic error message
                    $('.alert-dismissible').removeClass('alert-success').addClass('alert-danger');
                    $('.alert-dismissible').html('An error occurred: ' + (xhr.responseJSON ? xhr.responseJSON.message : error));
                }

                $('.alert-dismissible').show();
                $('#send_btn2').html("Register");
                $('#loader').hide();
                $('#send_btn2').prop('disabled', false);
            }
        });

        return false; // Prevent default form submission
    }
});

</script>
