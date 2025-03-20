@extends('layouts.app')

<!-- Main Content -->
<main class="content-wrapper">
    <div class="main-content manage-events">

        <div class="d-flex align-items-start justify-content-between flex-wrap">
            <!-- Breadcrumb -->
            <div class="breadcrumb-section mb-2 mb-xl-4">
                <ul class="breadcrumb-lists d-flex align-items-center flex-wrap">
                    <li class="breadcrumb-item position-relative">
                        <a href="javascript:;" class="breadcrumb-link font-dmsans fw-medium xsmall text-primary-v1" title="Home">Home</a>
                    </li>
                    <li class="breadcrumb-item position-relative">
                        <a href="javascript:;" class="breadcrumb-link font-dmsans fw-medium xsmall text-primary-v1" title="List Events - November 2024">List Events - November 2024</a>
                    </li>
                </ul>
            </div>

            <!-- Date Buttons -->
            <div class="btn-options mb-4">
                <a href="javascript:;" class="btn btn-white btn-xsmall font-dmsans fw-medium position-relative rounded-3 border border-grey-v1 d-inline-flex align-items-center" title="Previous">
                    <svg class="me-2" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3.37295 8.75005L8.5422 13.9193C8.69087 14.068 8.76429 14.242 8.76245 14.4413C8.76045 14.6406 8.68195 14.8179 8.52695 14.9731C8.37179 15.1179 8.19612 15.1929 7.99995 15.1981C7.80379 15.2032 7.62812 15.1282 7.47295 14.9731L1.1327 8.6328C1.03904 8.53914 0.973036 8.44039 0.934703 8.33655C0.896203 8.23272 0.876953 8.12055 0.876953 8.00005C0.876953 7.87955 0.896203 7.76739 0.934703 7.66355C0.973036 7.55972 1.03904 7.46097 1.1327 7.3673L7.47295 1.02705C7.61145 0.888555 7.78295 0.817721 7.98745 0.814555C8.19195 0.811388 8.37179 0.882221 8.52695 1.02705C8.68195 1.18222 8.75945 1.36039 8.75945 1.56155C8.75945 1.76289 8.68195 1.94114 8.52695 2.0963L3.37295 7.25005H14.75C14.9628 7.25005 15.141 7.32189 15.2845 7.46555C15.4281 7.60905 15.5 7.78722 15.5 8.00005C15.5 8.21289 15.4281 8.39105 15.2845 8.53455C15.141 8.67822 14.9628 8.75005 14.75 8.75005H3.37295Z" fill="#06152B" fill-opacity="0.7"/>
                    </svg>
                    <span class="ps-1">Previous</span>
                </a>
                <a href="javascript:;" class="btn btn-white btn-xsmall font-dmsans fw-medium position-relative rounded-3 border border-grey-v1 d-inline-flex align-items-center" title="Current Month">
                    <svg class="me-2" width="18" height="20" viewBox="0 0 18 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M11.6923 16C11.0499 16 10.5048 15.776 10.0568 15.328C9.60875 14.8798 9.38475 14.3346 9.38475 13.6923C9.38475 13.0499 9.60875 12.5048 10.0568 12.0568C10.5048 11.6088 11.0499 11.3848 11.6923 11.3848C12.3346 11.3848 12.8798 11.6088 13.328 12.0568C13.776 12.5048 14 13.0499 14 13.6923C14 14.3346 13.776 14.8798 13.328 15.328C12.8798 15.776 12.3346 16 11.6923 16ZM2.30775 19.5C1.80258 19.5 1.375 19.325 1.025 18.975C0.675 18.625 0.5 18.1974 0.5 17.6923V4.30777C0.5 3.8026 0.675 3.37502 1.025 3.02502C1.375 2.67502 1.80258 2.50002 2.30775 2.50002H3.69225V1.15377C3.69225 0.934599 3.76567 0.751599 3.9125 0.604765C4.05933 0.458099 4.24233 0.384766 4.4615 0.384766C4.68083 0.384766 4.86383 0.458099 5.0105 0.604765C5.15733 0.751599 5.23075 0.934599 5.23075 1.15377V2.50002H12.8077V1.13477C12.8077 0.921932 12.8795 0.743682 13.023 0.600015C13.1667 0.456515 13.3449 0.384766 13.5577 0.384766C13.7706 0.384766 13.9488 0.456515 14.0922 0.600015C14.2359 0.743682 14.3077 0.921932 14.3077 1.13477V2.50002H15.6923C16.1974 2.50002 16.625 2.67502 16.975 3.02502C17.325 3.37502 17.5 3.8026 17.5 4.30777V17.6923C17.5 18.1974 17.325 18.625 16.975 18.975C16.625 19.325 16.1974 19.5 15.6923 19.5H2.30775ZM2.30775 18H15.6923C15.7692 18 15.8398 17.9679 15.9038 17.9038C15.9679 17.8398 16 17.7693 16 17.6923V8.30777H2V17.6923C2 17.7693 2.03208 17.8398 2.09625 17.9038C2.16025 17.9679 2.23075 18 2.30775 18ZM2 6.80777H16V4.30777C16 4.23077 15.9679 4.16026 15.9038 4.09626C15.8398 4.0321 15.7692 4.00002 15.6923 4.00002H2.30775C2.23075 4.00002 2.16025 4.0321 2.09625 4.09626C2.03208 4.16026 2 4.23077 2 4.30777V6.80777Z" fill="#06152B" fill-opacity="0.7"/>
                    </svg>
                    <span class="ps-1">Current Month</span>
                </a>
                <a href="javascript:;" class="btn btn-white btn-xsmall font-dmsans fw-medium position-relative rounded-3 border border-grey-v1 d-inline-flex align-items-center" title="Next">
                    <span class="pe-1">Next</span>
                    <svg class="ms-2" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13.0039 8.75005L7.83469 13.9193C7.68602 14.068 7.61261 14.242 7.61444 14.4413C7.61644 14.6406 7.69494 14.8179 7.84994 14.9731C8.00511 15.1179 8.18077 15.1929 8.37694 15.1981C8.57311 15.2032 8.74877 15.1282 8.90394 14.9731L15.2442 8.6328C15.3379 8.53914 15.4039 8.44039 15.4422 8.33655C15.4807 8.23272 15.4999 8.12055 15.4999 8.00005C15.4999 7.87955 15.4807 7.76739 15.4422 7.66355C15.4039 7.55972 15.3379 7.46097 15.2442 7.3673L8.90394 1.02705C8.76544 0.888555 8.59394 0.817721 8.38944 0.814555C8.18494 0.811388 8.00511 0.882221 7.84994 1.02705C7.69494 1.18222 7.61744 1.36039 7.61744 1.56155C7.61744 1.76289 7.69494 1.94114 7.84994 2.0963L13.0039 7.25005H1.62694C1.41411 7.25005 1.23594 7.32189 1.09244 7.46555C0.948772 7.60905 0.876939 7.78722 0.876939 8.00005C0.876939 8.21289 0.948772 8.39105 1.09244 8.53455C1.23594 8.67822 1.41411 8.75005 1.62694 8.75005H13.0039Z" fill="#06152B" fill-opacity="0.7"/>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Content -->
        <div class="content-inner">
            <div class="content-header">
                <div class="heading text-start">
                    <h4 class="font-dmsans fw-medium text-primary-v1 mb-2">List Events - November 2024</h4>
                </div>
                <div class="filter-header-options d-flex align-items-center justify-content-between flex-wrap">
                    <div class="search-option">
                        <div class="search-container position-relative">
                            <input type="search" class="custom-search" placeholder="Search Article" aria-controls="manageEventsTable">
                        </div>
                    </div>
                    <div class="btn-options mt-3 mt-xl-0">
                        <a href="javascript:;" class="btn btn-primary btn-xsmall font-dmsans fw-medium position-relative rounded-3" data-bs-toggle="modal" data-bs-target="#addEvent" title="Add Event">Add Event</a>
                        <a href="javascript:;" class="btn btn-primary btn-xsmall font-dmsans fw-medium position-relative rounded-3" title="View Calendar">View Calendar</a>
                        <a href="javascript:;" class="btn btn-primary btn-xsmall font-dmsans fw-medium position-relative rounded-3" title="Archive Events">Archive Events</a>
                    </div>
                </div>
                <div class="table-block">
                    <table id="manageEventsTable" class="display custom-datatable" style="width:100%">
                        <thead>
                            <tr>
                                <th>
                                    <span>Title</span>
                                </th>
                                <th>
                                    <span>Location</span>
                                </th>
                                <th>
                                    <span>Start Date</span>
                                </th>
                                <th>
                                    <span>End Date</span>
                                </th>
                                <th>
                                    <span>Company</span>
                                </th>
                                <th>
                                    <span>Status</span>
                                </th>
                                <th class="action-cell text-center">
                                    <span>Action</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <span>ALB office closed - HAPPY THANKSGIVING</span>
                                </td>
                                <td>
                                    <span>Cincinnati, OH</span>
                                </td>
                                <td>
                                    <span>11/28/2024 08:30:00</span>
                                </td>
                                <td>
                                    <span>11/29/2024 00:00:00</span>
                                </td>
                                <td>
                                    <span>American Line Builders</span>
                                </td>
                                <td>
                                    <div class="toggle-switch d-flex align-items-center">
                                        <div class="toggle-button toggle-front d-flex align-items-center position-relative">
                                            <label for="status1" class="form-check-label font-dmsans text-primary-v1 visually-hidden">Status</label>
                                            <label class="switch">
                                                <input type="checkbox" id="status1" checked>
                                                <span class="slider"></span>
                                                <span class="active font-dmsans fw-medium">Active</span>
                                                <span class="inactive font-dmsans fw-medium">Inactive</span>
                                            </label>
                                        </div>
                                    </div>
                                </td>
                                <td class="action-cell text-center">
                                    <div class="action-col position-relative d-inline-block">
                                        <a href="javascript:;" class="p-1" data-bs-toggle="popover" data-bs-placement="top">
                                            <svg class="action-icon cursor-pointer" width="20" height="4" viewBox="0 0 20 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M10 0C11.1046 0 12 0.89543 12 2C12 3.10457 11.1046 4 10 4C8.89543 4 8 3.10457 8 2C8 0.89543 8.89543 0 10 0Z" fill="#2D264B"/>
                                                <path d="M2 -4.76837e-07C3.10457 -4.76837e-07 4 0.89543 4 2C4 3.10457 3.10457 4 2 4C0.89543 4 0 3.10457 0 2C0 0.89543 0.89543 -4.76837e-07 2 -4.76837e-07Z" fill="#2D264B"/>
                                                <path d="M18 2.38419e-07C19.1046 2.38419e-07 20 0.895431 20 2C20 3.10457 19.1046 4 18 4C16.8954 4 16 3.10457 16 2C16 0.895431 16.8954 2.38419e-07 18 2.38419e-07Z" fill="#2D264B"/>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</main>

<!-- Table Action Cell -->
<div class="popover-content" data-name="table-action-btn">
    <div class="action-menu">
        <ul class="action-menu-list position-relative bg-white rounded-1 p-2">
            <li class="action-menu-item text-start">
                <a href="javascript:;" class="action-menu-link font-dmsans fw-normal text-primary-v1 xsmall d-block p-1 edit-action" title="Edit">Edit</a>
            </li>
            <li class="action-menu-item text-start">
                <a href="javascript:;" class="action-menu-link font-dmsans fw-normal text-primary-v1 xsmall d-block p-1 delete-action" title="Delete">Delete</a>
            </li>
        </ul>
    </div>
</div>

<!-- Add Event Modal -->
<div class="modal fade" id="addEvent" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addEventLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h1 class="modal-title fs-5 font-dmsans fw-bold text-primary-v1" id="addEventLabel">Add Event</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="modal-form">
                    <form>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group m-0 mb-3 pb-1">
                                    <label for="eventTitle" class="form-label">Event Title <span class="asterik">*</span></label>
                                    <input type="text" class="form-control rounded-3" id="eventTitle" placeholder="Enter Event Title">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group m-0 mb-3 pb-1">
                                    <label for="eventLocation" class="form-label">Event Location <span class="asterik">*</span></label>
                                    <input type="text" class="form-control rounded-3" id="eventLocation" placeholder="Enter Event Location">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group m-0 mb-3 pb-1">
                                    <label for="eventStartDate" class="form-label">Event Start Date <span class="asterik">*</span></label>
                                    <input type="datetime-local" class="form-control rounded-3" id="eventStartDate">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group m-0 mb-3 pb-1">
                                    <label for="eventEndDate" class="form-label">Event End Date <span class="asterik">*</span></label>
                                    <input type="datetime-local" class="form-control rounded-3" id="eventEndDate">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group m-0 mb-3 pb-1">
                                    <label for="eventChapter" class="form-label">Chapter <span class="asterik">*</span></label>
                                    <select class="form-select" id="eventChapter">
                                        <option selected disabled>Select chapter</option>
                                        <option value="Chapter 1">Chapter 1</option>
                                        <option value="Chapter 2">Chapter 2</option>
                                        <option value="Chapter 3">Chapter 3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group m-0 mb-3 pb-1">
                                    <label for="eventLink" class="form-label">Event Link</label>
                                    <input type="text" class="form-control rounded-3" id="eventLink" placeholder="Enter Event Link">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group m-0 mb-3 pb-1">
                                    <label for="eventDescription" class="form-label">Event Description</label>
                                    <textarea class="form-control rounded-3" id="eventDescription" rows="4" placeholder="Write here"></textarea>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group m-0 mb-3 pb-1">
                                    <label for="eventCompanyName" class="form-label">Company Name</label>
                                    <div class="d-flex align-items-center pt-0 pt-md-3">
                                        <div class="form-check custom-radio p-0 m-0 me-3">
                                            <input type="radio" id="active" name="companyStatus" class="input-radio">
                                            <label for="active" class="form-radio-label">
                                                <span>
                                                    <svg viewBox="0 0 12 10" height="10px" width="12px">
                                                        <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                                                    </svg>
                                                </span>
                                                <span class="font-dmsans fw-normal text-primary-v1 position-relative d-inline-block">Active</span>
                                            </label>
                                        </div>
                                        <div class="form-check custom-radio p-0 m-0">
                                            <input type="radio" id="inactive" name="companyStatus" class="input-radio">
                                            <label for="inactive" class="form-radio-label">
                                                <span>
                                                    <svg viewBox="0 0 12 10" height="10px" width="12px">
                                                        <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                                                    </svg>
                                                </span>
                                                <span class="font-dmsans fw-normal text-primary-v1 position-relative d-inline-block">Inactive</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group m-0 mb-3 pb-1">
                                    <label for="eventDeactivate" class="form-label">Automatically deactivate this event after event end date?</label>
                                    <div class="d-flex align-items-center pt-0 pt-md-3">
                                        <div class="form-check custom-radio p-0 m-0 me-3">
                                            <input type="radio" id="eventYes" name="deactivateEvent" class="input-radio">
                                            <label for="eventYes" class="form-radio-label">
                                                <span>
                                                    <svg viewBox="0 0 12 10" height="10px" width="12px">
                                                        <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                                                    </svg>
                                                </span>
                                                <span class="font-dmsans fw-normal text-primary-v1 position-relative d-inline-block">Yes</span>
                                            </label>
                                        </div>
                                        <div class="form-check custom-radio p-0 m-0">
                                            <input type="radio" id="eventNo" name="deactivateEvent" class="input-radio">
                                            <label for="eventNo" class="form-radio-label">
                                                <span>
                                                    <svg viewBox="0 0 12 10" height="10px" width="12px">
                                                        <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
                                                    </svg>
                                                </span>
                                                <span class="font-dmsans fw-normal text-primary-v1 position-relative d-inline-block">No</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-start">
                <button type="submit" class="btn btn-primary btn-small fw-semibold text-uppercase rounded-3">Submit</button>
                <button type="button" class="btn btn-outline btn-small fw-semibold text-uppercase rounded-3 border border-grey-v1" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
