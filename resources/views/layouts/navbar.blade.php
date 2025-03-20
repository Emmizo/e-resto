 <!-- Header -->
 <header class="header position-fixed w-100 top-0 start-0 bg-white">
    <div class="header-section position-relative">
        <div class="container-fluid p-0">
            <div class="header-container d-flex align-items-center justify-content-between">
                <div class="header-container-left">
                    <div class="collapse-menu me-3">
                        <button class="navbar-toggler align-self-center b-0 m-0 p-0" type="button" data-bs-toggle="sidebar">
                            <span class="hamburger-icon d-inline-block">
                                <span></span>
                                <span></span>
                                <span></span>
                            </span>
                        </button>
                    </div>
                </div>
                <div class="header-container-right d-flex align-items-center">
                    <div class="user-profile-details d-flex align-items-center pe-3 pe-sm-4">
                        <div class="user-image me-2">
                            <figure class="m-0">
                                <img src="assets/images/user.png" alt="Profile Image" width="38" height="38" class="img-fluid rounded-3">
                            </figure>
                        </div>
                        <div class="user-details ps-1">
                            <p class="font-dmsans fw-medium xsmall user-name text-truncate text-primary-v1">Welcome <span class="fw-bold">Test User</span></p>
                            <p class="font-dmsans fw-normal xs-small user-role text-truncate text-dark-v1">Admin</p>
                        </div>
                    </div>
                    <div class="logout-btn">
                        <a href="{{route('login')}}" title="Logout">
                            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect width="32" height="32" rx="8" fill="#007AFF" fill-opacity="0.14"/>
                                <path d="M15.3636 23C11.849 23 9 20.0899 9 16.5C9 12.9101 11.849 10 15.3636 10C16.3517 9.99924 17.3263 10.2339 18.21 10.6852C19.0938 11.1365 19.8623 11.7922 20.4545 12.6H18.73C17.9951 11.9381 17.0889 11.5069 16.1201 11.3581C15.1513 11.2093 14.161 11.3492 13.268 11.761C12.3751 12.1728 11.6175 12.839 11.086 13.6798C10.5546 14.5205 10.272 15.5 10.2721 16.5007C10.2722 17.5014 10.555 18.4809 11.0865 19.3215C11.6181 20.1622 12.3758 20.8283 13.2688 21.2399C14.1618 21.6516 15.1521 21.7913 16.1209 21.6423C17.0897 21.4933 17.9959 21.062 18.7306 20.4H20.4552C19.8629 21.2079 19.0943 21.8636 18.2104 22.315C17.3265 22.7663 16.3518 23.0009 15.3636 23ZM19.8182 19.1V17.15H14.7273V15.85H19.8182V13.9L23 16.5L19.8182 19.1Z" fill="#007AFF"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
