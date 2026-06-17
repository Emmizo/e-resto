@extends('layouts.app')

@section('content')
<main class="content-wrapper">
    <div class="main-content manage-users">

        <div class="breadcrumb-section mb-2 mb-xl-4">
            <ul class="breadcrumb-lists d-flex align-items-center flex-wrap">
                <li class="breadcrumb-item position-relative">
                    <a href="{{ route('dashboard') }}" class="breadcrumb-link font-dmsans fw-medium xsmall text-primary-v1">Home</a>
                </li>
                <li class="breadcrumb-item position-relative">
                    <a href="{{ route('promo-banners.index') }}" class="breadcrumb-link font-dmsans fw-medium xsmall text-primary-v1">Promo Banners</a>
                </li>
                <li class="breadcrumb-item position-relative">
                    <span class="breadcrumb-link font-dmsans fw-medium xsmall text-primary-v1">Add Banner</span>
                </li>
            </ul>
        </div>

        <div class="content-inner">
            <div class="row justify-content-center">
                <div class="col-xl-7 col-lg-9">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                            <h5 class="font-dmsans fw-bold text-primary-v1 mb-0">Add Promo Banner</h5>
                        </div>
                        <div class="card-body px-4 pb-4">
                            <div id="form-alert" class="mt-3"></div>
                            <form id="promoBannerForm" enctype="multipart/form-data" novalidate>
                                @csrf

                                <div class="form-group mb-3">
                                    <label for="title" class="form-label font-dmsans fw-medium text-primary-v1">Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title" id="title" class="form-control rounded-3" placeholder="Enter banner title" maxlength="255">
                                    <span class="invalid-feedback d-block" id="err-title"></span>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="description" class="form-label font-dmsans fw-medium text-primary-v1">Description</label>
                                    <textarea name="description" id="description" class="form-control rounded-3" rows="3" placeholder="Enter banner description" maxlength="1000"></textarea>
                                    <span class="invalid-feedback d-block" id="err-description"></span>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="image" class="form-label font-dmsans fw-medium text-primary-v1">Image</label>
                                    <input type="file" name="image" id="image" class="form-control rounded-3" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                                    <div class="form-text text-muted">Max 2MB. Accepted: jpeg, png, jpg, gif, webp.</div>
                                    <span class="invalid-feedback d-block" id="err-image"></span>
                                    <div id="image-preview" class="mt-2 d-none">
                                        <img src="" alt="Preview" class="rounded-3" style="max-height:120px;max-width:100%;object-fit:cover;">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="start_date" class="form-label font-dmsans fw-medium text-primary-v1">Start Date</label>
                                            <input type="text" name="start_date" id="start_date" class="form-control rounded-3" placeholder="dd/mm/yyyy">
                                            <span class="invalid-feedback d-block" id="err-start_date"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="end_date" class="form-label font-dmsans fw-medium text-primary-v1">End Date</label>
                                            <input type="text" name="end_date" id="end_date" class="form-control rounded-3" placeholder="dd/mm/yyyy">
                                            <span class="invalid-feedback d-block" id="err-end_date"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary font-dmsans fw-medium" id="submitBtn">
                                        <span class="btn-text">Save Banner</span>
                                        <span class="spinner-border spinner-border-sm ms-2 d-none" role="status"></span>
                                    </button>
                                    <a href="{{ route('promo-banners.index') }}" class="btn btn-outline-secondary font-dmsans fw-medium">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>
@endsection

@section('script')
<script>
$(document).ready(function() {

    $('#image').on('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#image-preview img').attr('src', e.target.result);
                $('#image-preview').removeClass('d-none');
            };
            reader.readAsDataURL(file);
        }
    });

    const startPicker = flatpickr('#start_date', {
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'd/m/Y',
        allowInput: false,
        onChange: function(selectedDates) {
            if (selectedDates[0]) {
                endPicker.set('minDate', selectedDates[0]);
            }
        }
    });
    const endPicker = flatpickr('#end_date', {
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'd/m/Y',
        allowInput: false,
    });

    function clearErrors() {
        $('.form-control').removeClass('is-invalid');
        $('[id^="err-"]').text('');
        $('#form-alert').html('');
    }

    function showFieldErrors(errors) {
        $.each(errors, function(field, messages) {
            $('#' + field).addClass('is-invalid');
            $('#err-' + field).text(messages[0]);
        });
    }

    $('#promoBannerForm').on('submit', function(e) {
        e.preventDefault();
        clearErrors();

        let valid = true;
        if (!$('#title').val().trim()) {
            $('#title').addClass('is-invalid');
            $('#err-title').text('Title is required.');
            valid = false;
        }
        const start = $('#start_date').val();
        const end   = $('#end_date').val();
        if (start && end && end < start) {
            $('#end_date').addClass('is-invalid');
            $('#err-end_date').text('End date must be on or after the start date.');
            valid = false;
        }
        if (!valid) return;

        const btn     = $('#submitBtn');
        const spinner = btn.find('.spinner-border');
        const btnText = btn.find('.btn-text');
        btn.prop('disabled', true);
        spinner.removeClass('d-none');
        btnText.text('Saving...');

        $.ajax({
            url: '{{ route("promo-banners.store") }}',
            method: 'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function(response) {
                toastr.success(response.message || 'Promo banner created!');
                setTimeout(function() {
                    window.location.href = response.redirect || '{{ route("promo-banners.index") }}';
                }, 1000);
            },
            error: function(xhr) {
                btn.prop('disabled', false);
                spinner.addClass('d-none');
                btnText.text('Save Banner');

                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    showFieldErrors(xhr.responseJSON.errors);
                } else {
                    $('#form-alert').html('<div class="alert alert-danger">' + (xhr.responseJSON?.message || 'An error occurred. Please try again.') + '</div>');
                }
            }
        });
    });
});
</script>
@endsection
