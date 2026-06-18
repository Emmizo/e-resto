@extends('layouts.theme')
{{-- @section('content') --}}
<!-- Body -->
<body>

    <!-- Login -->
    <div class="login-wrapper">
        <div class="login-content">

            <div class="row m-0">
                <div class="col-xl-7 col-lg-6 col-sm-12 p-0 d-sm-none d-lg-block">
                    <div class="district-left-content position-relative h-100">
                        <div class="login-left position-relative d-flex align-items-start justify-content-between flex-column h-100 animate__animated animate__fadeIn">
                            <div class="login-left-top mb-4">
                                <a href="{{ route('login') }}" class="district-logo animate__animated animate__bounceIn animate__delay-1s" title="FoodFinder">
                                    <img src="{{asset('assets/images/logo.png')}}" alt="FoodFinder" width="96" height="134" class="district-logo-img">
                                </a>
                            </div>
                            <div class="login-left-bottom animate__animated animate__fadeInUp animate__delay-1s">
                                <h2 class="font-dmsans fw-bold medium text-white mb-4">Welcome to the RestoFinder app</h2>
                                <p class="font-dmsans fw-normal text-white lh-base">RestoFinder helps users discover restaurants around their location using geolocation technology, providing personalized dining suggestions based on preferences and real-time availability.</p>
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
                                            <span>Smart Meal Recommendations</span>
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
                            <div class="login-right-top d-flex align-items-center justify-content-center flex-column mb-4 animate__animated animate__fadeIn">
                                <!-- Tabs for Login and Register -->
                                <ul class="nav nav-tabs w-100 mb-4" id="authTabs" role="tablist">
                                    <li class="nav-item w-50" role="presentation">
                                        <button class="nav-link active w-100" id="login-tab" data-bs-toggle="tab" data-bs-target="#login-panel" type="button" role="tab" aria-controls="login-panel" aria-selected="true">Sign In</button>
                                    </li>
                                    <li class="nav-item w-50" role="presentation">
                                        <button class="nav-link w-100" id="register-tab" data-bs-toggle="tab" data-bs-target="#register-panel" type="button" role="tab" aria-controls="register-panel" aria-selected="false">Register</button>
                                    </li>
                                </ul>

                                <div class="tab-content w-100" id="authTabsContent">
                                    <!-- Login Tab -->
                                    <div class="tab-pane fade show active animate__animated animate__fadeIn" id="login-panel" role="tabpanel" aria-labelledby="login-tab">
                                        <div class="login-heading mb-4">
                                            <h2 class="font-dmsans fw-bold medium text-dark-v2 mb-1">Sign in to your Account</h2>
                                            <p class="font-dmsans fw-normal text-dark-v2 lh-base pb-2">Are you a new user? Click on the Register tab to create your account.</p>
                                        </div>
                                        <div id="message-container-login">
                                            @if(session('success'))
                                            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-3" role="alert">
                                                <i class="fas fa-check-circle"></i>
                                                <span>{{ session('success') }}</span>
                                                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                                            </div>
                                            @endif
                                        </div>
                                        <form action="{{ route('login') }}" method="POST" id="loginForm" name="loginForm">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group m-0 mb-3">
                                                        <label for="loginEmail" class="form-label">Email Address <span class="asterik">*</span></label>
                                                        <input type="email" class="form-control rounded-3" id="loginEmail" placeholder="Enter Email Address" name="email" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group m-0 mb-3 position-relative">
                                                        <label for="loginPassword" class="form-label">Password <span class="asterik">*</span></label>
                                                        <input type="password" class="form-control rounded-3" id="loginPassword" placeholder="Enter Password" name="password" required>
                                                        <span class="toggle-password" style="position:absolute;top:38px;right:15px;cursor:pointer;z-index:2;">
                                                            <i class="fa fa-eye-slash" id="toggleLoginPassword"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 d-flex align-items-center justify-content-end mb-3">
                                                    <a href="javascript:void(0)" id="showForgotBtn" class="forgot-password-link">Forgot Password?</a>
                                                </div>
                                                <div class="col-md-12">
                                                    <button type="submit" class="btn btn-primary btn-small fw-semibold text-uppercase rounded-3 w-100 text-center animate__animated animate__pulse animate__infinite animate__slow" id="send_btn">
                                                        <span>Sign In</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Forgot Password Tab (shown in place of Register when triggered) -->
                                    <div class="tab-pane fade animate__animated animate__fadeIn" id="forgot-panel" role="tabpanel">
                                        <div class="forgot-icon-wrap mb-3">
                                            <div class="forgot-icon-circle">
                                                <i class="fa fa-lock"></i>
                                            </div>
                                        </div>
                                        <div class="login-heading mb-2">
                                            <h2 class="font-dmsans fw-bold medium text-dark-v2 mb-1">Forgot Password?</h2>
                                            <p class="font-dmsans fw-normal text-muted lh-base pb-1">No worries! Enter your email and we'll send you a reset link.</p>
                                        </div>
                                        <div id="message-container-forgot"></div>
                                        <form method="POST" action="" id="forgotForm" name="forgotForm">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group m-0 mb-3 position-relative">
                                                        <label for="forgotEmail" class="form-label">Email Address <span class="asterik">*</span></label>
                                                        <div class="input-wrapper position-relative">
                                                            <input type="email" class="form-control rounded-3 pe-5" id="forgotEmail" placeholder="Enter your registered email" name="email" required autocomplete="off">
                                                            <span id="forgotEmailStatus" class="forgot-email-status"></span>
                                                        </div>
                                                        <div id="forgotEmailFeedback" class="forgot-email-feedback"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mt-1">
                                                    <button type="submit" class="btn btn-primary btn-small fw-semibold text-uppercase rounded-3 w-100 text-center" id="forgot_btn" disabled>
                                                        <i class="fa fa-paper-plane me-2"></i><span>Send Reset Link</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Register Tab -->
                                    <div class="tab-pane fade animate__animated animate__fadeIn" id="register-panel" role="tabpanel" aria-labelledby="register-tab">
                                        <div class="login-heading mb-4">
                                            <h2 class="font-dmsans fw-bold medium text-dark-v2 mb-1">Create New Account</h2>
                                            <p class="font-dmsans fw-normal text-dark-v2 lh-base pb-2">Already have an account? Click on the Sign In tab.</p>
                                        </div>
                                        <div id="message-container"></div>
                                        <form action=""  method="POST" enctype="multipart/form-data" id="registerForm" name="registerForm">
                                            {{-- @csrf --}}
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group m-0 mb-3">
                                                        <label for="firstName" class="form-label">First Name <span class="asterik">*</span></label>
                                                        <input type="text" class="form-control rounded-3" id="first_name" name="first_name" placeholder="Enter First Name" required>
                                                        <small class="text-danger">{{ $errors->first('first_name') }}</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group m-0 mb-3">
                                                        <label for="lastName" class="form-label">Last Name <span class="asterik">*</span></label>
                                                        <input type="text" class="form-control rounded-3" id="last_name" name="last_name" placeholder="Enter Last Name" required>
                                                        <small class="text-danger">{{ $errors->first('last_name') }}</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group m-0 mb-3">
                                                        <label for="registerEmail" class="form-label">Email Address <span class="asterik">*</span></label>
                                                        <div class="position-relative">
                                                            <input type="email" class="form-control rounded-3 pe-5" id="registerEmail" name="email" placeholder="Enter Email Address" required autocomplete="off">
                                                            <span id="registerEmailStatus" class="forgot-email-status"></span>
                                                        </div>
                                                        <div id="registerEmailFeedback" class="forgot-email-feedback"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group m-0 mb-3">
                                                        <label for="phoneNumber" class="form-label">Phone Number <span class="asterik">*</span></label>
                                                        <input type="tel" class="form-control rounded-3" id="phoneNumber" name="phone_number" placeholder="Enter Phone Number" required>
                                                    </div>
                                                </div>


                                                <div class="col-md-12">
                                                    <div class="form-group m-0 mb-3">
                                                        <label for="profilePicture" class="form-label">Profile Picture</label>
                                                        <input type="file" class="form-control rounded-3" id="profilePicture" name="profile_picture" accept="image/*">
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group m-0 mb-3">
                                                        <label class="form-label">User Type <span class="asterik">*</span></label>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="role" id="roleUser" value="user" checked>
                                                            <label class="form-check-label" for="roleUser">Regular User</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="role" id="roleRestaurantOwner" value="restaurant_owner">
                                                            <label class="form-check-label" for="roleRestaurantOwner">Restaurant Owner</label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Restaurant Owner Fields (conditionally displayed) -->
                                                <div id="restaurantFields" class="restaurant-section d-none">
                                                    <hr>
                                                    <h4 class="font-dmsans fw-medium mb-3">Restaurant Information</h4>

                                                    <div class="col-md-12">
                                                        <div class="form-group m-0 mb-3">
                                                            <label for="restaurantName" class="form-label">Restaurant Name <span class="asterik">*</span></label>
                                                            <input type="text" class="form-control rounded-3" id="restaurantName" name="restaurant_name" placeholder="Enter Restaurant Name" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group m-0 mb-3">
                                                            <label for="restaurantDescription" class="form-label">Description<span class="asterik">*</span></label>
                                                            <textarea class="form-control rounded-3" id="restaurantDescription" name="restaurant_description" rows="3" placeholder="Enter Restaurant Description" required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group m-0 mb-3">
                                                            <label for="restaurantAddress" class="form-label">Opening Hours <span class="asterik">*</span></label>
                                                            <input type="time" class="form-control rounded-3" id="restaurant_opening_hours" name="restaurant_opening_hours" placeholder="Enter Restaurant Address" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group m-0 mb-3">
                                                            <label for="restaurantAddress" class="form-label">Address <span class="asterik">*</span></label>
                                                            <input type="text" class="form-control rounded-3" id="restaurantAddress" name="restaurant_address" placeholder="Enter Restaurant Address" required>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group m-0 mb-3">
                                                                <label for="restaurantLongitude" class="form-label">Longitude<span class="asterik">*</span></label>
                                                                <input type="text" class="form-control rounded-3" id="restaurantLongitude" name="restaurant_longitude" placeholder="Enter Longitude" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group m-0 mb-3">
                                                                <label for="restaurantLatitude" class="form-label">Latitude<span class="asterik">*</span></label>
                                                                <input type="text" class="form-control rounded-3" id="restaurantLatitude" name="restaurant_latitude" placeholder="Enter Latitude" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group m-0 mb-3">
                                                            <label for="restaurantPhone" class="form-label">Restaurant Phone <span class="asterik">*</span></label>
                                                            <input type="tel" class="form-control rounded-3" id="restaurantPhone" name="restaurant_phone_number" placeholder="Enter Restaurant Phone" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group m-0 mb-3">
                                                            <label for="restaurantEmail" class="form-label">Restaurant Email<span class="asterik">*</span></label>
                                                            <div class="position-relative">
                                                                <input type="email" class="form-control rounded-3 pe-5" id="restaurantEmail" name="restaurant_email" placeholder="Enter Restaurant Email" required autocomplete="off">
                                                                <span id="restaurantEmailStatus" class="forgot-email-status"></span>
                                                            </div>
                                                            <div id="restaurantEmailFeedback" class="forgot-email-feedback"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group m-0 mb-3">
                                                            <label for="restaurantWebsite" class="form-label">Website</label>
                                                            <input type="url" class="form-control rounded-3" id="restaurantWebsite" name="restaurant_website_" placeholder="Enter Restaurant Website">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group m-0 mb-3">
                                                            <label for="cuisineType" class="form-label">Cuisine Type</label>
                                                            <select class="form-select rounded-3" id="cuisineType" name="restaurant_cuisine_id" required>
                                                                <option value="">Select Cuisine Type</option>
                                                                @foreach($cuisines as $cuisine)
                                                                    <option value="{{ $cuisine->id }}">{{ $cuisine->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group m-0 mb-3">
                                                            <label for="priceRange" class="form-label">Price Range</label>
                                                            <select class="form-select rounded-3" id="priceRange" name="restaurant_price_range">
                                                                <option value="">Select Price Range</option>
                                                                <option value="500-5000">RWF 500 - 5,000 (Budget)</option>
                                                                <option value="5000-15000">RWF 5,000 - 15,000 (Moderate)</option>
                                                                <option value="15000-30000">RWF 15,000 - 30,000 (Expensive)</option>
                                                                <option value="30000+">RWF 30,000+ (Luxury)</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group m-0 mb-3">
                                                            <label for="restaurantImage" class="form-label">Restaurant Image</label>
                                                            <input type="file" class="form-control rounded-3" id="restaurantImage" name="restaurant_image" accept="image/*">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 mt-3">
                                                    <div class="form-check mb-3">
                                                        <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                                                        <label class="form-check-label" for="terms">
                                                            I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and Conditions</a>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mt-3">
                                                    <button type="submit" class="btn btn-primary btn-small fw-semibold text-uppercase rounded-3 w-100 text-center animate__animated animate__pulse animate__infinite animate__slow" id="send_btn2">
                                                        <span>Register</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="login-right-bottom d-flex flex-column justify-content-end w-100"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Required JS Files -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>

    <!-- JavaScript for conditional display of restaurant fields -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
    // Make sure Bootstrap is loaded
    if (typeof bootstrap === 'undefined') {
        console.error('Bootstrap JavaScript is not loaded!');
        return;
    }

    // Initialize Bootstrap tabs
    var tabElements = document.querySelectorAll('#authTabs button');
    tabElements.forEach(function(tabEl) {
        new bootstrap.Tab(tabEl);

        // Add click event listener to ensure tabs work
        tabEl.addEventListener('click', function(event) {
            event.preventDefault();
            new bootstrap.Tab(this).show();
        });
    });

    // Handle user type selection
    const roleRadios = document.querySelectorAll('input[name="role"]');
    const restaurantFields = document.getElementById('restaurantFields');
    const registerButton = document.querySelector('#registerForm button[type="submit"]');
    const appMessageContainer = document.createElement('div');
    appMessageContainer.className = 'alert alert-info mt-3 d-none';
    appMessageContainer.innerHTML = `
        <strong>Download the App!</strong> Regular users are encouraged to download our mobile app for the best experience.
        <div class="mt-2">
            <a href="https://play.google.com/store/apps/details?id=com.neca.district10" class="btn btn-sm btn-outline-primary me-2" target="_blank">
                Google Play
            </a>
            <a href="https://apps.apple.com/us/app/district-10-neca/id1568041607" class="btn btn-sm btn-outline-primary" target="_blank">
                App Store
            </a>
        </div>
    `;

    // Insert the message after the registration button
    registerButton.parentNode.insertAdjacentElement('afterend', appMessageContainer);

    roleRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'restaurant_owner') {
                restaurantFields.classList.remove('d-none');
                // Make restaurant fields required when restaurant owner is selected
                document.getElementById('restaurantName').required = true;
                document.getElementById('restaurantAddress').required = true;
                document.getElementById('restaurantPhone').required = true;

                // Enable registration button for restaurant owners
                registerButton.disabled = false;

                // Hide app download message
                appMessageContainer.classList.add('d-none');

            } else {
                // Regular user selected
                restaurantFields.classList.add('d-none');
                // Remove required attribute when regular user is selected
                document.getElementById('restaurantName').required = false;
                document.getElementById('restaurantAddress').required = false;
                document.getElementById('restaurantPhone').required = false;

                registerButton.disabled = false;

                // Show app download message
                appMessageContainer.classList.remove('d-none');
            }
        });
    });

    // Check the initial state when page loads
    const userRoleRadio = document.getElementById('roleUser');
    if (userRoleRadio && userRoleRadio.checked) {
        registerButton.disabled = false;
        appMessageContainer.classList.remove('d-none');
    }

   // Geolocation for restaurant longitude and latitude
function addGeolocationButton() {
  // Make sure DOM is loaded
  if (!document.getElementById('restaurantLatitude')) {
    console.error('Latitude field not found in DOM');
    return;
  }

  const getLongLatButton = document.createElement('button');
  getLongLatButton.type = 'button';
  getLongLatButton.className = 'btn btn-sm btn-outline-secondary mt-2';
  getLongLatButton.innerHTML = 'Detect Current Location';

  getLongLatButton.addEventListener('click', function() {
    if (!navigator.geolocation) {
      alert('Geolocation is not supported by this browser.');
      return;
    }

    getLongLatButton.innerHTML = 'Detecting...';
    getLongLatButton.disabled = true;

    navigator.geolocation.getCurrentPosition(
      // Success callback
      function(position) {
        console.log("Latitude: " + position.coords.latitude);
        console.log("Longitude: " + position.coords.longitude);

        const latField = document.getElementById('restaurantLatitude');
        const longField = document.getElementById('restaurantLongitude');

        if (latField && longField) {
          latField.value = position.coords.latitude.toFixed(6);
          longField.value = position.coords.longitude.toFixed(6);

          getLongLatButton.innerHTML = 'Location Detected';
          getLongLatButton.className = 'btn btn-sm btn-success mt-2';
        } else {
          console.error('Latitude or longitude field not found');
          getLongLatButton.innerHTML = 'DOM Error';
          getLongLatButton.className = 'btn btn-sm btn-danger mt-2';
        }

        setTimeout(() => {
          getLongLatButton.innerHTML = 'Detect Current Location';
          getLongLatButton.className = 'btn btn-sm btn-outline-secondary mt-2';
          getLongLatButton.disabled = false;
        }, 3000);
      },
      // Error callback
      function(error) {
        let errorMessage = 'Unable to get your location. Please enter coordinates manually.';
        switch (error.code) {
          case error.PERMISSION_DENIED:
            errorMessage = 'Permission denied. Please enable location access in browser settings.';
            break;
          case error.POSITION_UNAVAILABLE:
            errorMessage = 'Location information is unavailable.';
            break;
          case error.TIMEOUT:
            errorMessage = 'Request timed out. Try again.';
            break;
          case error.UNKNOWN_ERROR:
            errorMessage = 'An unknown error occurred.';
            break;
        }

        console.error('Geolocation error:', error.code, errorMessage);
        alert(errorMessage);

        getLongLatButton.innerHTML = 'Detection Failed';
        getLongLatButton.className = 'btn btn-sm btn-danger mt-2';

        setTimeout(() => {
          getLongLatButton.innerHTML = 'Retry Detection';
          getLongLatButton.className = 'btn btn-sm btn-outline-secondary mt-2';
          getLongLatButton.disabled = false;
        }, 3000);
      },
      { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
    );
  });

  // Insert the geolocation button after the latitude field
  const latitudeField = document.getElementById('restaurantLatitude');
  if (latitudeField) {
    latitudeField.parentNode.insertAdjacentElement('beforeend', getLongLatButton);
  } else {
    console.error('Could not find latitude field to attach button');
  }
}

// Run once the DOM is fully loaded
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', addGeolocationButton);
} else {
  addGeolocationButton();
}
    // Add form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });

    // Add password confirmation validation
    const password = document.getElementById('registerPassword');
    const confirmPassword = document.getElementById('confirmPassword');

    function validatePassword() {
        if (password.value !== confirmPassword.value) {
            confirmPassword.setCustomValidity("Passwords don't match");
        } else {
            confirmPassword.setCustomValidity('');
        }
    }

    if (password && confirmPassword) {
        password.addEventListener('change', validatePassword);
        confirmPassword.addEventListener('keyup', validatePassword);
    }

    // Manual tab switching for additional verification
    const loginTab = document.getElementById('login-tab');
    const registerTab = document.getElementById('register-tab');

    if (loginTab && registerTab) {
        loginTab.addEventListener('click', function() {
            document.getElementById('login-panel').classList.add('show', 'active');
            document.getElementById('register-panel').classList.remove('show', 'active');
            this.classList.add('active');
            registerTab.classList.remove('active');
        });

        registerTab.addEventListener('click', function() {
            document.getElementById('register-panel').classList.add('show', 'active');
            document.getElementById('login-panel').classList.remove('show', 'active');
            this.classList.add('active');
            loginTab.classList.remove('active');
        });
    }

});
// Add this to the existing JavaScript section
$(document).ready(function() {
    // Opening hours time picker
    flatpickr('#restaurant_opening_hours', {
        enableTime: true,
        noCalendar: true,
        dateFormat: 'H:i',
        altInput: true,
        altFormat: 'H:i',
        allowInput: false,
        time_24hr: true,
    });

    // Profile picture preview
    $('#profilePicture').on('change', function(evt) {
    const [file] = this.files;
    if (file) {
        let preview = $('#profilePreviewImg');
        if (!preview.length) {
            $('#profilePicture').parent().append('<img id="profilePreviewImg" class="img-thumbnail mt-2" style="max-height: 150px;">');
            preview = $('#profilePreviewImg');
        }
        preview.attr('src', URL.createObjectURL(file));
    }
});

// Restaurant image preview
$('#restaurantImage').on('change', function(evt) {
    const [file] = this.files;
    if (file) {
        let preview = $('#restaurantPreviewImg');
        if (!preview.length) {
            $('#restaurantImage').parent().append('<img id="restaurantPreviewImg" class="img-thumbnail mt-2" style="max-height: 150px;">');
            preview = $('#restaurantPreviewImg');
        }
        preview.attr('src', URL.createObjectURL(file));
    }
});

// Phone number mask
$('#phoneNumber, #restaurantPhone').mask('(000) 000-0000');

// Add the missing validation methods for file validation
// This fixes the "Cannot read properties of undefined (reading 'call')" error
$.validator.addMethod('extension', function(value, element, param) {
    param = typeof param === 'string' ? param.replace(/,/g, '|') : 'png|jpe?g';
    return this.optional(element) || value.match(new RegExp('\\.(' + param + ')$', 'i'));
}, $.validator.format("Please upload files with valid extensions."));

$.validator.addMethod('maxsize', function(value, element, param) {
    if (this.optional(element)) {
        return true;
    }

    if (element.files && element.files[0]) {
        return element.files[0].size <= param;
    }

    return true;
}, $.validator.format("File size must be less than {0} bytes."));

// Form validation
$('#registerForm').validate({
    rules: {
        first_name: {
            required: true,
        },
        last_name: {
            required: true,
        },
        email: {
            required: true,
            email: true,
        },
        restaurant_opening_hours:{
            required: function() {
                return $('input[name="role"]:checked').val() === 'restaurant_owner';
            }
        },
        phone_number: {
            required: true,
            minlength: 14,
        },
        profile_picture: {
            extension: "jpeg,jpg,png",
            maxsize: 5242880,
        },
        role: {
            required: true,
        },
        terms: {
            required: true,
        },
        "restaurant_name": {
            required: function() {
                return $('input[name="role"]:checked').val() === 'restaurant_owner';
            }
        },
        "restaurant_description": {
            required: function() {
                return $('input[name="role"]:checked').val() === 'restaurant_owner';
            }
        },
        "restaurant_address": {
            required: function() {
                return $('input[name="role"]:checked').val() === 'restaurant_owner';
            }
        },
        "restaurant_phone_number": {
            required: function() {
                return $('input[name="role"]:checked').val() === 'restaurant_owner';
            },
            minlength: 14
        },
        "restaurant_email": {
            required: function() {
                return $('input[name="role"]:checked').val() === 'restaurant_owner';
            },
            email: true
        },
        "restaurant_image": {
            extension: "jpeg,jpg,png",
            maxsize: 5242880
        }
    },
    messages: {
        first_name: {
            required: "Please enter your first name",
        },
        last_name: {
            required: "Please enter your last name",
        },
        email: {
            required: "Please enter an email address",
            email: "Please enter a valid email address",
        },
        phone_number: {
            required: "Please enter a phone number",
            minlength: "Please enter a valid phone number",
        },
        profile_picture: {
            extension: "Please upload jpg, jpeg, or png files only",
            maxsize: "File size must be less than 5 MB",
        },
        role: {
            required: "Please select a role",
        },
        terms: {
            required: "You must agree to the Terms and Conditions",
        },
        "restaurant_name": {
            required: "Please enter restaurant name",
        },
        "restaurant_description": {
            required: "Please enter restaurant description",
        },
        "restaurant_opening_hours": {
            required: "Please enter restaurant opening hours",
        },
        "restaurant_address": {
            required: "Please enter restaurant address",
        },
        "restaurant_phone_number": {
            required: "Please enter restaurant phone number",
            minlength: "Please enter a valid phone number",
        },
        "restaurant_email": {
            required: "Please enter restaurant email",
            email: "Please enter a valid email address"
        },
        "restaurant_image": {
            extension: "Please upload jpg, jpeg, or png files only",
            maxsize: "File size must be less than 5 MB",
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

        var form_data = new FormData(form);

        // Set up CSRF token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Make AJAX request
        $.ajax({
            url: "{{ route('signup') }}", // Use form action or fallback
            type: "POST",
            dataType: "json",
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,

            beforeSend: function() {
                $('#send_btn2').html("<i class='fa fa-spin fa-spinner'></i> Submit");
                $('#loader').show();
                $('#send_btn2').prop('disabled', true);
                $('.alert-dismissible').hide(); // Hide any previous alerts
                // Re-enable the button after a specific delay (e.g., 5000ms = 5 seconds)
    setTimeout(function() {
        $('#send_btn2').html("Submit"); // Reset button text
        $('#send_btn2').prop('disabled', false); // Re-enable the button
        $('#loader').hide(); // Hide the loader
    }, 5000);
            },
            success: function(result) {
                if (result.status == 202) {
                    $('#message-container').html(
                        '<div class="alert alert-success">' +
                        '<i class="fa fa-envelope-circle-check me-2"></i>' +
                        '<strong>Account created!</strong> A verification email has been sent to <strong>' + ($('#registerEmail').val()) + '</strong>. ' +
                        'Please check your inbox and click the link to activate your account.' +
                        '</div>'
                    );
                    $('#registerForm')[0].reset();
                    $('#send_btn2').html("Register").prop('disabled', false);
                    $('#loader').hide();
                } else {
                    $('#message-container').html('<div class="alert alert-danger">' + (result.message || 'An error occurred. Please try again.') + '</div>');
                    $('#send_btn2').html("Register").prop('disabled', false);
                    $('#loader').hide();
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

// Toggle restaurant fields based on role selection
$('input[name="role"]').on('change', function() {
    if ($(this).val() === 'restaurant_owner') {
        $('#restaurantFields').removeClass('d-none');
        // Make restaurant fields required
        $('#restaurantFields input, #restaurantFields textarea').not('[type="file"]').not('#restaurantWebsite').prop('required', true);
    } else {
        $('#restaurantFields').addClass('d-none');
        // Make restaurant fields not required
        $('#restaurantFields input, #restaurantFields textarea').prop('required', false);
    }
});

// Trigger change on page load to set initial state
$('input[name="role"]:checked').trigger('change');
// Function to reset the form
function resetForm() {
    document.getElementById("registerForm").reset();
    $('#profilePreviewImg, #restaurantPreviewImg').attr('src', '');
}
//login
$('#loginForm').validate({
    rules: {

        email: {
            required: true,
            email: true,
        },

        password: {
            required: true,
            minlength: 8
        },


    },
    messages: {

        email: {
            required: "Please enter an email address",
            email: "Please enter a valid email address",
        },
        password: {
            required: "Please enter a password",
            minlength: "Password must be at least 8 characters"
        },

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


                $('#loginForm input').each(function(i, e) {
                var getID = $(this).attr('id');
                var name = $(this).attr('name');
                form_data.append(name, $("#" + getID).val());
                });
                // Append CSRF token explicitly
                form_data.append('_token', $('meta[name="csrf-token"]').attr('content'));



        // Set up CSRF token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Make AJAX request
        $.ajax({
            url: "{{ route('admin-login-post') }}", // Use form action or fallback
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
                setTimeout(function() {
        $('#send_btn').html("Submit"); // Reset button text
        $('#send_btn').prop('disabled', false); // Re-enable the button
        $('#loader').hide(); // Hide the loader
    }, 5000);
            },
            success: function(result) {
                console.log(result.status);

                if (result.status == '201') {
                    $('#message-container-login').html('<div class="alert alert-success">Logged in! Redirecting...</div>');
                    setTimeout(function() {
                        window.location.href = result.redirect || '/dashboard';
                    }, 1000);
                } else if (result.status == 403 && result.unverified) {
                    var email = result.email || '';
                    $('#message-container-login').html(
                        '<div class="alert alert-warning" id="unverified-alert">' +
                        '<i class="fa fa-envelope me-2"></i>' +
                        '<strong>Email not verified.</strong> Please check your inbox for the verification link.' +
                        '<div class="mt-2">' +
                        '<button type="button" class="btn btn-sm btn-outline-warning" id="resendVerifyBtn" data-email="' + email + '">' +
                        '<i class="fa fa-rotate-right me-1"></i>Resend verification email' +
                        '</button>' +
                        '</div></div>'
                    );
                } else {
                    $('#message-container-login').html('<div class="alert alert-danger">' + (result.msg || 'An error occurred. Please try again.') + '</div>');

                    // Reset button
                    $('#send_btn2').html("Register");
                    $('#loader').hide();
                    $('#send_btn2').prop('disabled', false);
                }
            },
            error: function(xhr) {
                var response = xhr.responseJSON || {};
                $('#send_btn').html("<i class='fa fa-sign-in-alt me-2'></i>Sign In").prop('disabled', false);
                $('#loader').hide();

                if (xhr.status === 403 && response.unverified) {
                    var email = response.email || '';
                    $('#message-container-login').html(
                        '<div class="alert alert-warning">' +
                        '<i class="fa fa-envelope me-2"></i>' +
                        '<strong>Email not verified.</strong> Please check your inbox for the verification link.' +
                        '<div class="mt-2">' +
                        '<button type="button" class="btn btn-sm btn-outline-warning" id="resendVerifyBtn" data-email="' + email + '">' +
                        '<i class="fa fa-rotate-right me-1"></i>Resend verification email' +
                        '</button></div></div>'
                    );
                } else {
                    $('#message-container-login').html('<div class="alert alert-danger">' + (response.msg || 'An error occurred. Please try again.') + '</div>');
                }
            }
        });

        return false; // Prevent default form submission
    }
});

// Toggle restaurant fields based on role selection
$('input[name="role"]').on('change', function() {
    if ($(this).val() === 'restaurant_owner') {
        $('#restaurantFields').removeClass('d-none');
        // Make restaurant fields required
        $('#restaurantFields input, #restaurantFields textarea').not('[type="file"]').not('#restaurantWebsite').prop('required', true);
    } else {
        $('#restaurantFields').addClass('d-none');
        // Make restaurant fields not required
        $('#restaurantFields input, #restaurantFields textarea').prop('required', false);
    }
});

// Resend verification email
$(document).on('click', '#resendVerifyBtn', function() {
    var btn = $(this);
    var email = btn.data('email');
    btn.prop('disabled', true).html('<i class="fa fa-spin fa-spinner me-1"></i>Sending...');
    $.ajax({
        url: "{{ route('resend-verification') }}",
        type: 'POST',
        dataType: 'json',
        data: { email: email, _token: $('meta[name="csrf-token"]').attr('content') },
        success: function(res) {
            btn.closest('.alert').html(
                '<i class="fa fa-check-circle me-2 text-success"></i>' + (res.message || 'Verification email sent. Check your inbox.')
            );
        },
        error: function(xhr) {
            var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Failed to resend. Please try again.';
            btn.prop('disabled', false).html('<i class="fa fa-rotate-right me-1"></i>Resend verification email');
            btn.after('<div class="text-danger small mt-1">' + msg + '</div>');
        }
    });
});

// Trigger change on page load to set initial state
$('input[name="role"]:checked').trigger('change');
// Function to reset the form
function resetForm() {
    document.getElementById("registerForm").reset();
    $('#profilePreviewImg, #restaurantPreviewImg').attr('src', '');
}

// Show/hide password toggle for login
$(document).ready(function() {
    $('#toggleLoginPassword').on('click', function() {
        const passwordInput = $('#loginPassword');
        const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
        passwordInput.attr('type', type);
        $(this).toggleClass('fa-eye fa-eye-slash');
    });
});

// Intercept login form submission for offline support
$('#loginForm').on('submit', async function(e) {
    e.preventDefault();
    var formData = $(this).serializeArray();
    var loginData = {};
    formData.forEach(function(item) { loginData[item.name] = item.value; });
    if (!navigator.onLine) {
        let queue = await localforage.getItem('loginQueue') || [];
        queue.push(loginData);
        await localforage.setItem('loginQueue', queue);
        $('#message-container-login').html('<div class="alert alert-info">You are offline. Login will be processed when you are back online.</div>');
        return false;
    }
    // ... existing AJAX login code ...
});

// Sync queued logins when back online
window.addEventListener('online', async function() {
    let queue = await localforage.getItem('loginQueue') || [];
    for (const loginData of queue) {
        $.ajax({
            url: $('#loginForm').attr('action'),
            type: 'POST',
            data: loginData,
            success: function(response) {
                // Handle login success (redirect, etc.)
                window.location.reload();
            },
            error: function(xhr) {
                // Handle login error
                $('#message-container-login').html('<div class="alert alert-danger">Login failed while syncing.</div>');
            }
        });
    }
    await localforage.setItem('loginQueue', []);
});

// Forgot Password: swap Register tab → Forgot Password tab
$('#showForgotBtn').on('click', function() {
    // Rename and retarget the register tab button to forgot
    $('#register-tab')
        .text('Forgot Password')
        .attr('data-bs-target', '#forgot-panel')
        .attr('aria-controls', 'forgot-panel');
    // Activate the (now relabelled) tab
    new bootstrap.Tab(document.getElementById('register-tab')).show();
    $('#message-container-forgot').html('');
    $('#forgotForm')[0].reset();
});

// When Sign In tab is clicked, restore Register tab
$('#login-tab').on('click', function() {
    $('#register-tab')
        .text('Register')
        .attr('data-bs-target', '#register-panel')
        .attr('aria-controls', 'register-panel');
});

// Forgot Password — live email validation
var forgotEmailTimer = null;
var forgotEmailValid = false;

function setForgotEmailState(state, message) {
    var $input    = $('#forgotEmail');
    var $status   = $('#forgotEmailStatus');
    var $feedback = $('#forgotEmailFeedback');
    var $btn      = $('#forgot_btn');

    $input.removeClass('is-valid is-invalid');
    $feedback.removeClass('text-success text-danger text-muted').text('');
    $status.removeClass('fa fa-check-circle fa-times-circle fa-spin fa-spinner').text('');

    if (state === 'checking') {
        $status.addClass('fa fa-spin fa-spinner text-muted');
        $feedback.addClass('text-muted').text('Checking...');
        $btn.prop('disabled', true);
        forgotEmailValid = false;
    } else if (state === 'valid') {
        $input.addClass('is-valid');
        $status.addClass('fa fa-check-circle').css('color', '#198754');
        $feedback.addClass('text-success').text(message || 'Account found.');
        $btn.prop('disabled', false);
        forgotEmailValid = true;
    } else if (state === 'invalid') {
        $input.addClass('is-invalid');
        $status.addClass('fa fa-times-circle').css('color', '#dc3545');
        $feedback.addClass('text-danger').text(message || 'Email not found.');
        $btn.prop('disabled', true);
        forgotEmailValid = false;
    } else {
        $btn.prop('disabled', true);
        forgotEmailValid = false;
    }
}

$('#forgotEmail').on('input', function() {
    var email = $(this).val().trim();
    clearTimeout(forgotEmailTimer);

    // Reset on empty
    if (!email) {
        setForgotEmailState('reset');
        return;
    }

    // Basic format check before hitting server
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        setForgotEmailState('invalid', 'Please enter a valid email address.');
        return;
    }

    setForgotEmailState('checking');

    forgotEmailTimer = setTimeout(function() {
        $.ajax({
            url: "{{ route('check-email-exists') }}",
            type: "POST",
            dataType: "json",
            data: { email: email, _token: $('meta[name="csrf-token"]').attr('content') },
            success: function(result) {
                if (result.exists) {
                    setForgotEmailState('valid', 'Account found. You can send the reset link.');
                } else {
                    setForgotEmailState('invalid', result.message || 'No account found with that email address.');
                }
            },
            error: function() {
                setForgotEmailState('invalid', 'Could not verify email. Please try again.');
            }
        });
    }, 500);
});

// Forgot Password form submit
$('#forgotForm').on('submit', function(e) {
    e.preventDefault();
    if (!forgotEmailValid) return false;

    var form_data = new FormData();
    form_data.append('email', $('#forgotEmail').val().trim());
    form_data.append('_token', $('meta[name="csrf-token"]').attr('content'));

    $.ajax({
        url: "{{ route('forgot-password-post') }}",
        type: "POST",
        dataType: "json",
        data: form_data,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function() {
            $('#forgot_btn').html("<i class='fa fa-spin fa-spinner'></i> Sending...").prop('disabled', true);
            $('#message-container-forgot').html('');
        },
        success: function(result) {
            if (result.status == '201') {
                $('#message-container-forgot').html('<div class="alert alert-success"><i class="fa fa-check-circle me-2"></i>Reset link sent! Please check your inbox.</div>');
                $('#forgotForm')[0].reset();
                setForgotEmailState('reset');
            } else {
                $('#message-container-forgot').html('<div class="alert alert-danger">' + (result.message || 'An error occurred.') + '</div>');
                $('#forgot_btn').html("<i class='fa fa-paper-plane me-2'></i><span>Send Reset Link</span>").prop('disabled', false);
            }
        },
        error: function(xhr) {
            var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'An error occurred. Please try again.';
            $('#message-container-forgot').html('<div class="alert alert-danger">' + msg + '</div>');
            $('#forgot_btn').html("<i class='fa fa-paper-plane me-2'></i><span>Send Reset Link</span>").prop('disabled', false);
        }
    });
    return false;
});


// ── Sign-up live email validation ───────────────────────────────────────────
var registerEmailTimer = null;
var registerEmailValid = true; // allow submit until user types something
var restaurantEmailTimer = null;
var restaurantEmailValid = true;

function setSignupEmailState(statusId, feedbackId, state, message) {
    var $status   = $('#' + statusId);
    var $feedback = $('#' + feedbackId);
    $status.removeClass('state-checking state-valid state-invalid').html('');
    $feedback.removeClass('text-success text-danger').html('');

    if (state === 'checking') {
        $status.addClass('state-checking').html('<i class="fa fa-spin fa-spinner text-muted"></i>');
    } else if (state === 'valid') {
        $status.addClass('state-valid').html('<i class="fa fa-check-circle text-success"></i>');
        if (message) $feedback.addClass('text-success').html(message);
    } else if (state === 'invalid') {
        $status.addClass('state-invalid').html('<i class="fa fa-times-circle text-danger"></i>');
        if (message) $feedback.addClass('text-danger').html(message);
    } else {
        // reset
    }
}

$('#registerEmail').on('input', function() {
    var email = $(this).val().trim();
    clearTimeout(registerEmailTimer);
    setSignupEmailState('registerEmailStatus', 'registerEmailFeedback', 'reset');

    if (!email) {
        registerEmailValid = true;
        return;
    }

    setSignupEmailState('registerEmailStatus', 'registerEmailFeedback', 'checking');

    registerEmailTimer = setTimeout(function() {
        $.ajax({
            url: "{{ route('check-email-taken') }}",
            type: "POST",
            dataType: "json",
            data: { email: email, type: 'user', _token: $('meta[name="csrf-token"]').attr('content') },
            success: function(result) {
                if (result.taken) {
                    registerEmailValid = false;
                    setSignupEmailState('registerEmailStatus', 'registerEmailFeedback', 'invalid', result.message || 'This email is already registered.');
                } else {
                    registerEmailValid = true;
                    setSignupEmailState('registerEmailStatus', 'registerEmailFeedback', 'valid', 'Email is available.');
                }
            },
            error: function() {
                registerEmailValid = true;
                setSignupEmailState('registerEmailStatus', 'registerEmailFeedback', 'reset');
            }
        });
    }, 500);
});

$('#restaurantEmail').on('input', function() {
    var email = $(this).val().trim();
    clearTimeout(restaurantEmailTimer);
    setSignupEmailState('restaurantEmailStatus', 'restaurantEmailFeedback', 'reset');

    if (!email) {
        restaurantEmailValid = true;
        return;
    }

    setSignupEmailState('restaurantEmailStatus', 'restaurantEmailFeedback', 'checking');

    restaurantEmailTimer = setTimeout(function() {
        $.ajax({
            url: "{{ route('check-email-taken') }}",
            type: "POST",
            dataType: "json",
            data: { email: email, type: 'restaurant', _token: $('meta[name="csrf-token"]').attr('content') },
            success: function(result) {
                if (result.taken) {
                    restaurantEmailValid = false;
                    setSignupEmailState('restaurantEmailStatus', 'restaurantEmailFeedback', 'invalid', result.message || 'This email is already registered.');
                } else {
                    restaurantEmailValid = true;
                    setSignupEmailState('restaurantEmailStatus', 'restaurantEmailFeedback', 'valid', 'Email is available.');
                }
            },
            error: function() {
                restaurantEmailValid = true;
                setSignupEmailState('restaurantEmailStatus', 'restaurantEmailFeedback', 'reset');
            }
        });
    }, 500);
});

// Block sign-up submit if any taken email is detected
$('#signupForm').on('submit', function(e) {
    if (!registerEmailValid) {
        e.preventDefault();
        setSignupEmailState('registerEmailStatus', 'registerEmailFeedback', 'invalid', 'Please use a different email address.');
        $('#registerEmail').focus();
        return false;
    }
    var isRestaurantOwner = $('#roleRestaurantOwner').is(':checked');
    if (isRestaurantOwner && !restaurantEmailValid) {
        e.preventDefault();
        setSignupEmailState('restaurantEmailStatus', 'restaurantEmailFeedback', 'invalid', 'Please use a different restaurant email address.');
        $('#restaurantEmail').focus();
        return false;
    }
});

// Searchable cuisine type dropdown
if (document.getElementById('cuisineType')) {
    new TomSelect('#cuisineType', {
        placeholder: 'Search cuisine type...',
        allowEmptyOption: true,
        maxOptions: null,
        create: false,
    });
}

});
    </script>

    <style>
        /* Add some custom styles to ensure tab functionality */
        .nav-tabs .nav-link {
            cursor: pointer;
            color: #6c757d;
            font-weight: 500;
        }

        .nav-tabs .nav-link.active {
            color: #0d6efd;
            border-bottom: 2px solid #0d6efd;
        }

        /* Ensure scrolling works for long forms */
        .district-right-content {
            overflow-y: auto;
            max-height: 100vh;
        }

        /* Forgot email live validation */
        .forgot-email-status {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 15px;
            pointer-events: none;
        }
        .forgot-email-feedback {
            font-size: 12.5px;
            margin-top: 4px;
            min-height: 18px;
        }

        /* Forgot password link */
        .forgot-password-link {
            font-size: 13px;
            font-weight: 500;
            color: #184C55;
            text-decoration: none;
            transition: color 0.2s;
        }
        .forgot-password-link:hover {
            color: #0d6efd;
            text-decoration: underline;
        }

        /* Lock icon circle */
        .forgot-icon-wrap {
            text-align: center;
        }
        .forgot-icon-circle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: linear-gradient(135deg, #e8f4f6 0%, #d0eaf0 100%);
            border: 2px solid #b8dde5;
        }
        .forgot-icon-circle .fa {
            font-size: 26px;
            color: #184C55;
        }

        /* Adjust form layout for better spacing */
        form {
            width: 100%;
        }

        /* Improved styling for tabs */
        #authTabs {
            border-bottom: 1px solid #dee2e6;
        }

        /* Ensure asterisks are visible */
        .asterik {
            color: red;
        }

        /* Adjust animation timing */
        .animate__animated.animate__pulse.animate__infinite {
            animation-duration: 2s;
        }
    </style>
{{-- @endsection --}}
</body>

<!-- Terms and Conditions Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        @php
            $terms = \App\Models\TermsAndConditions::where('is_active', true)->latest()->first();
        @endphp
        @if($terms)
            {!! nl2br(e($terms->content)) !!}
        @else
            <p>No terms and conditions found.</p>
        @endif
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
