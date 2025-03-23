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
                                         @if (session()->has('error'))
        <div class="alert msg alert-danger"> {!! session('error') !!} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>
    @endif
                                        <form action="" method="POST" id="loginForm" name="loginForm">

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group m-0 mb-3">
                                                        <label for="loginEmail" class="form-label">Email Address <span class="asterik">*</span></label>
                                                        <input type="email" class="form-control rounded-3" id="loginEmail" placeholder="Enter Email Address" name="email" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group m-0 mb-3">
                                                        <label for="loginPassword" class="form-label">Password <span class="asterik">*</span></label>
                                                        <input type="password" class="form-control rounded-3" id="loginPassword" placeholder="Enter Password" name="password" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="m-0 mb-3 text-end">
                                                        <a href="{{route('forgot')}}" class="font-dmsans fw-medium small text-secondary position-relative d-inline-block" title="Forgot Password?">Forgot Password?</a>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <button type="submit" class="btn btn-primary btn-small fw-semibold text-uppercase rounded-3 w-100 text-center animate__animated animate__pulse animate__infinite animate__slow" id="send_btn">
                                                        <span>Sign In</span>
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
                                        @if (session()->has('success'))
    <div class="alert msg alert-success alert-dismissible"> {!! session('success') !!} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>
    @endif @if (session()->has('error'))
        <div class="alert msg alert-danger"> {!! session('error') !!} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>
    @endif
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
                                                        <input type="email" class="form-control rounded-3" id="registerEmail" name="email" placeholder="Enter Email Address" required>
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
                                                        <label for="registerPassword" class="form-label">Password <span class="asterik">*</span></label>
                                                        <input type="password" class="form-control rounded-3" id="password" name="password" placeholder="Enter Password" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group m-0 mb-3">
                                                        <label for="confirmPassword" class="form-label">Confirm Password <span class="asterik">*</span></label>
                                                        <input type="password" class="form-control rounded-3" id="confirmPassword" name="password_confirmation" placeholder="Confirm Password" required>
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
                                                            <input type="email" class="form-control rounded-3" id="restaurantEmail" name="restaurant_email" placeholder="Enter Restaurant Email" required>
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
                                                            <input type="text" class="form-control rounded-3" id="cuisineType" name="restaurant_cuisine_type" placeholder="Enter Cuisine Type">
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
                                                    <button type="submit" class="btn btn-primary btn-small fw-semibold text-uppercase rounded-3 w-100 text-center animate__animated animate__pulse animate__infinite animate__slow" id="send_btn2">
                                                        <span>Register</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="login-right-bottom d-flex flex-column justify-content-end w-100">
                                <ul class="login-content-links d-flex align-items-center justify-content-center flex-wrap my-3 my-xl-0 animate__animated animate__fadeInUp animate__delay-1s">
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

    <!-- Required JS Files -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
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

                // Disable registration button for regular users
                registerButton.disabled = true;

                // Show app download message
                appMessageContainer.classList.remove('d-none');
            }
        });
    });

    // Check the initial state when page loads
    const userRoleRadio = document.getElementById('roleUser');
    if (userRoleRadio && userRoleRadio.checked) {
        registerButton.disabled = true;
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
            required: true,
        },
        phone_number: {
            required: true,
            minlength: 14,
        },
        password: {
            required: true,
            minlength: 8
        },
        password_confirmation: {
            required: true,
            equalTo: "#password",
        },
        profile_picture: {
            extension: "jpeg,jpg,png",
            maxsize: 5242880,
        },
        role: {
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
        password: {
            required: "Please enter a password",
            minlength: "Password must be at least 8 characters"
        },
        password_confirmation: {
            required: "Please confirm your password",
            equalTo: "Passwords do not match",
        },
        profile_picture: {
            extension: "Please upload jpg, jpeg, or png files only",
            maxsize: "File size must be less than 5 MB",
        },
        role: {
            required: "Please select a role",
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

        var form_data = new FormData();


                $('#registerForm input').each(function(i, e) {
                var getID = $(this).attr('id');
                var name = $(this).attr('name');
                form_data.append(name, $("#" + getID).val());
                });

                $('#registerForm select').each(function() {
                var getID = $(this).attr('id');
                var name = $(this).attr('name');

                form_data.append(name, $("#" + getID).val());
                });
                // Loop through textarea elements
                $('#registerForm textarea').each(function() {
                var getID = $(this).attr('id');
                var name = $(this).attr('name');
                form_data.append(name, $("#" + getID).val());
                });
                $('#registerForm input[type="file"]').each(function() {
    var getID = $(this).attr('id');
    var name = $(this).attr('name');
    var file = $("#" + getID)[0].files[0]; // Get the first selected file

    if (file) {
        form_data.append(name, file); // Append the file to form_data
    }
});

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
            },
            success: function(result) {
                console.log(result.status);

                if (result.status == '201') {
                    // Show success message before redirect
                    $('.alert-dismissible').removeClass('alert-danger').addClass('alert-success');
                    $('.alert-dismissible').html('Registration successful! Redirecting to dashboard...');
                    $('.alert-dismissible').show();

                    // Redirect after a short delay
                    setTimeout(function() {
                        window.location.href = result.redirect || '/dashboard';
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
                        window.location.href = result.redirect || '/dashboard';
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
