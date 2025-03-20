@extends('layouts.theme')
<body>
    <!-- Login -->
    <div class="login-wrapper">
        <div class="login-content">

            <div class="row m-0">
                <div class="col-xl-7 col-lg-6 col-sm-12 p-0">
                    <div class="district-left-content position-relative h-100">
                        <div class="login-left position-relative d-flex align-items-start justify-content-between flex-column h-100">
                            <div class="login-left-top mb-4">
                                <a href="javascript:;" class="district-logo" title="District 10">
                                    <img src="assets/images/logo.png" alt="District 10" width="96" height="134" class="district-logo-img">
                                </a>
                            </div>
                            <div class="login-left-bottom">
                                <h2 class="font-dmsans fw-bold medium text-white mb-4">Welcome to the District 10 app</h2>
                                <p class="font-dmsans fw-normal text-white lh-base">District 10 is made up of 7 Chapters of the National Electrical Contractors Association (NECA) representing the Outside Electrical Construction industry in the United States of America.</p>
                                <ul class="login-content-list my-3 ps-2">
                                    <li>
                                        <p class="font-dmsans fw-normal text-white lh-base position-relative ps-3">
                                            <span>American Line Builders Chapter</span>
                                        </p>
                                    </li>
                                    <li>
                                        <p class="font-dmsans fw-normal text-white lh-base position-relative ps-3">
                                            <span>Missouri Valley Chapter</span>
                                        </p>
                                    </li>
                                    <li>
                                        <p class="font-dmsans fw-normal text-white lh-base position-relative ps-3">
                                            <span>Northeast Line Constructors Chapter</span>
                                        </p>
                                    </li>
                                    <li>
                                        <p class="font-dmsans fw-normal text-white lh-base position-relative ps-3">
                                            <span>Northwest Line Constructors Chapter</span>
                                        </p>
                                    </li>
                                    <li>
                                        <p class="font-dmsans fw-normal text-white lh-base position-relative ps-3">
                                            <span>Southeastern Line Constructors Chapter</span>
                                        </p>
                                    </li>
                                    <li>
                                        <p class="font-dmsans fw-normal text-white lh-base position-relative ps-3">
                                            <span>Southwestern Line Constructors Chapter</span>
                                        </p>
                                    </li>
                                    <li>
                                        <p class="font-dmsans fw-normal text-white lh-base position-relative ps-3">
                                            <span>Western Line Constructors Chapter</span>
                                        </p>
                                    </li>
                                </ul>
                                <p class="font-dmsans fw-normal text-white lh-base">The D10 App will serve to provide you with access to Chapter content, Collective Bargaining Agreements, Jurisdictional Maps, and more.</p>
                                <div class="store-logo d-flex align-items-center mt-4">
                                    <a href="https://play.google.com/store/apps/details?id=com.neca.district10&hl=en&gl=US&pcampaignid=pcampaignidMKT-Other-global-all-co-prtnr-py-PartBadge-Mar2515-1" class="me-4" title="Google Play Store" target="_blank">
                                        <figure class="m-0">
                                            <img src="assets/images/play-store.png" alt="Google Play Store" width="174" height="53">
                                        </figure>
                                    </a>
                                    <a href="https://apps.apple.com/us/app/district-10-neca/id1568041607?itsct=apps_box_badge&itscg=30200" class="me-4" title="App Store" target="_blank">
                                        <figure class="m-0">
                                            <img src="assets/images/app-store.png" alt="App Store" width="174" height="53">
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
                                <form>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group m-0 mb-3">
                                                <label for="loginEmail" class="form-label">Email Address <span class="asterik">*</span></label>
                                                <input type="email" class="form-control rounded-3" id="loginEmail" placeholder="Enter Email Address" required>
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
