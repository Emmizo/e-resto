@extends('layouts.app')
<!-- Main Content -->
<main class="content-wrapper">
    <div class="main-content manage-users">

        <!-- Breadcrumb -->
        <div class="breadcrumb-section mb-2 mb-xl-4">
            <ul class="breadcrumb-lists d-flex align-items-center flex-wrap">
                <li class="breadcrumb-item position-relative">
                    <a href="javascript:;" class="breadcrumb-link font-dmsans fw-medium xsmall text-primary-v1" title="Home">Home</a>
                </li>
                <li class="breadcrumb-item position-relative">
                    <a href="javascript:;" class="breadcrumb-link font-dmsans fw-medium xsmall text-primary-v1" title="Manage Users">Manage Users</a>
                </li>
            </ul>
        </div>

        <!-- Content -->
        <div class="content-inner">
            <div class="content-header">
                <div class="heading text-start">
                    <h4 class="font-dmsans fw-medium text-primary-v1 mb-2">Manage Menu</h4>
                </div>
                <div class="filter-header-options d-flex align-items-center justify-content-between flex-wrap">
                    <div class="search-option">
                        <div class="search-container position-relative">
                            <input type="search" class="custom-search" placeholder="Search Article" aria-controls="manageUsersTable">
                        </div>
                    </div>
                    <div class="btn-options mt-3 mt-xl-0">

                        <a href="javascript:;" class="btn btn-primary btn-xsmall font-dmsans fw-medium position-relative rounded-3" data-bs-toggle="modal" data-bs-target="#addUser" title="Add User">Add Menu</a>

                    </div>
                </div>
                <div class="filter-col-options">
                    <div class="filter-col-options-inner pt-2 mt-1">

                    </div>
                </div>
                <div class="table-block">
                    <table id="manageUsersTable" class="display custom-datatable" style="width:100%">
                        <thead>
                            <tr>
                                <th>
                                    <span>Name</span>
                                </th>
                                <th>
                                    <span>Description</span>
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
                            @foreach ($menus as $menu)

                            <tr>
                                <td>
                                    <span>{{$menu->menu_name}}</span>
                                </td>
                                <td>
                                    <span>{{ $menu->menu_description }}{{ $menu->is_active}}</span>
                                </td>
                                <td>

                                    <div class="toggle-switch d-flex align-items-center">
                                        <div class="toggle-button toggle-front d-flex align-items-center position-relative">
                                            <label for="status{{ $menu->id }}" class="form-check-label font-dmsans text-primary-v1 visually-hidden">Status</label>
                                            <label class="switch">
                                                <input type="checkbox" id="status{{ $menu->id }}" class="status-toggle" data-id="{{ $menu->id }}" {{ $menu->is_active == 1 ? 'checked' : '' }}>
                                                <span class="slider"></span>
                                                <span class="active font-dmsans fw-medium">Active</span>
                                                <span class="inactive font-dmsans fw-medium">Inactive</span>
                                            </label>
                                        </div>
                                    </div>
                                </td>
                                <td class="action-cell text-center">
                                    <div class="action-col position-relative d-inline-block">
                                        <a href="javascript:;" class="p-1 action-btn" data-bs-toggle="popover" data-bs-placement="top" data-id="{{ $menu->id }}">
                                            <svg class="action-icon cursor-pointer" width="20" height="4" viewBox="0 0 20 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M10 0C11.1046 0 12 0.89543 12 2C12 3.10457 11.1046 4 10 4C8.89543 4 8 3.10457 8 2C8 0.89543 8.89543 0 10 0Z" fill="#2D264B"/>
                                                <path d="M2 -4.76837e-07C3.10457 -4.76837e-07 4 0.89543 4 2C4 3.10457 3.10457 4 2 4C0.89543 4 0 3.10457 0 2C0 0.89543 0.89543 -4.76837e-07 2 -4.76837e-07Z" fill="#2D264B"/>
                                                <path d="M18 2.38419e-07C19.1046 2.38419e-07 20 0.895431 20 2C20 3.10457 19.1046 4 18 4C16.8954 4 16 3.10457 16 2C16 0.895431 16.8954 2.38419e-07 18 2.38419e-07Z" fill="#2D264B"/>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</main>

<!-- Table Action Cell -->
<div class="popover-content d-none" id="table-action-popover">
    <div class="action-menu">
        <ul class="action-menu-list position-relative bg-white rounded-1 p-2">
            <li class="action-menu-item text-start">
                <a href="javascript:;" class="action-menu-link font-dmsans fw-normal text-primary-v1 xsmall d-block p-1 edit-menu" data-bs-toggle="modal" data-bs-target="#editMenuModal">Edit</a>
            </li>
            <li class="action-menu-item text-start">
                <a href="javascript:;" class="action-menu-link font-dmsans fw-normal text-primary-v1 xsmall d-block p-1 delete-menu" data-bs-toggle="modal" data-bs-target="#deleteMenuModal">Delete</a>
            </li>
        </ul>
    </div>
</div>

<!-- Add menu Modal -->
<div class="modal fade" id="addUser" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h1 class="modal-title fs-5 font-dmsans fw-bold text-primary-v1" id="addUserLabel">Add Menu Type</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="message-container-login"></div>
            <form action="" method="POST" id="addMenuForm">
                <div class="modal-body">
                    <div class="modal-form">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group m-0 mb-3 pb-1">
                                    <label for="userName" class="form-label">Name <span class="asterik">*</span></label>
                                    <input type="text" class="form-control rounded-3" id="userName" placeholder="Enter name" name="name" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group m-0 mb-3">
                                    <label for="restaurantDescription" class="form-label">Description<span class="asterik">*</span></label>
                                    <textarea class="form-control rounded-3" id="restaurantDescription" name="description" rows="3" placeholder="Enter Restaurant Description" required></textarea>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="m-0">Menu Items</h4>
                            <button type="button" class="btn btn-success btn-sm" id="addMenuItem">
                                <i class="bi bi-plus-circle me-1"></i>Add Menu Item
                            </button>
                        </div>
                        <div id="menuItemsContainer">
                            <div class="menu-item row mb-3">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="menu_items[0][name]" placeholder="Item Name" required>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" class="form-control" name="menu_items[0][price]" placeholder="Price" required>
                                </div>
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <input type="file" class="form-control" name="menu_items[0][image]" accept="image/*">
                                    </div>
                                </div>
                                <div class="col-md-4 mt-2">
                                    <input type="text" class="form-control" name="menu_items[0][category]" placeholder="Category">
                                </div>
                                <div class="col-md-3 mt-2">
                                    <input type="text" class="form-control" name="menu_items[0][dietary_info]" placeholder="Dietary Info">
                                </div>
                                <div class="col-md-3 mt-2">
                                    <select class="form-control" name="menu_items[0][is_available]">
                                        <option value="1">Available</option>
                                        <option value="0">Not Available</option>
                                    </select>
                                </div>
                                <div class="col-md-2 mt-2">
                                <button type="button" class="btn btn-danger remove-item-btn" onclick="removeItem(this)">
                                    <i class="fa fa-trash"></i>
                                </button>
                                </div>
                            </div>
                            <hr>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-start">
                    <button type="submit" class="btn btn-primary btn-small fw-semibold text-uppercase rounded-3" id="send_btn2">Submit</button>
                    <button type="button" class="btn btn-outline btn-small fw-semibold text-uppercase rounded-3 border border-grey-v1" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>




<!-- Edit Menu Modal -->
<div class="modal fade" id="editMenuModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editMenuForm">
                    @csrf
                    <input type="hidden" id="edit_menu_id" name="id">
                    <div class="mb-3">
                        <label for="edit_menu_name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="edit_menu_name" name="name">
                    </div>
                    <div class="mb-3">
                        <label for="edit_menu_description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit_menu_description" name="description"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-check-label">Status</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="edit_menu_status" name="is_active">
                            <label class="form-check-label" for="edit_menu_status">Active</label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="updateMenuBtn">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete User Modal -->
<div class="modal fade" id="deleteUser" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h1 class="modal-title fs-5 font-dmsans fw-bold text-primary-v1" id="deleteUserLabel">Delete User</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="modal-form">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <p class="font-dmsans text-primary-v1 medium mb-4">Are you sure you want to delete this User?</p>
                            <div class="footer-btns">
                                <button type="submit" class="btn btn-primary btn-small fw-semibold text-uppercase rounded-3">Yes</button>
                                <button type="button" class="btn btn-outline btn-small fw-semibold text-uppercase rounded-3 border border-grey-v1" data-bs-dismiss="modal">No</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-start pt-0">
            </div>
        </div>
    </div>
</div>



</div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
   $(document).ready(function() {


            //Bootstrap Duallistbox
            $('#Permissions').bootstrapDualListbox({
                nonSelectedListLabel: 'Non-selected',
                selectedListLabel: 'Selected',
                preserveSelectionOnMove: 'moved',
                moveOnSelect: false
            });
// To access the non-selected list container (if needed)
var nonSelectedList = $('[id="bootstrap-duallistbox-nonselected-list_Permissions[]"]');

// To access the selected list container (if needed)
var selectedList = $('[id="bootstrap-duallistbox-selected-list_Permissions[]"]');


$('#addMenuForm').validate({
    rules: {
        name: {
            required: true,
            minlength: 2,
            maxlength: 50,
            pattern: /^[a-zA-Z0-9\s]+$/
        },
        description: {
            required: true,
            minlength: 10,
            maxlength: 250
        },
        'menu_items[0][name]': {
            required: true,
            minlength: 2,
            maxlength: 50
        },
        'menu_items[0][price]': {
            required: true,
            number: true,
            min: 0,
            max: 1000
        },
        'menu_items[0][category]': {
            maxlength: 30
        },
        'menu_items[0][dietary_info]': {
            maxlength: 50
        },
        'menu_items[0][image]': {
            accept: "image/*",
            filesize: 5 * 1024 * 1024 // 5MB max file size
        }
    },
    messages: {
        name: {
            required: "Please enter a menu type name",
            minlength: "Name must be at least 2 characters long",
            maxlength: "Name cannot exceed 50 characters",
            pattern: "Name can only contain letters, numbers, and spaces"
        },
        description: {
            required: "Please enter a description",
            minlength: "Description must be at least 10 characters long",
            maxlength: "Description cannot exceed 250 characters"
        },
        'menu_items[0][name]': {
            required: "Please enter a menu item name",
            minlength: "Item name must be at least 2 characters long",
            maxlength: "Item name cannot exceed 50 characters"
        },
        'menu_items[0][price]': {
            required: "Please enter a price",
            number: "Price must be a valid number",
            min: "Price cannot be negative",
            max: "Price is too high"
        },
        'menu_items[0][category]': {
            maxlength: "Category cannot exceed 30 characters"
        },
        'menu_items[0][dietary_info]': {
            maxlength: "Dietary info cannot exceed 50 characters"
        },
        'menu_items[0][image]': {
            accept: "Please upload a valid image file",
            filesize: "Image file must be less than 5MB"
        }
    },
    errorElement: 'span',
    errorPlacement: function(error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group, .col-md-4, .col-md-3, .col-md-5').append(error);
    },
    highlight: function(element) {
        $(element).addClass('is-invalid');
    },
    unhighlight: function(element) {
        $(element).removeClass('is-invalid');
    },
    submitHandler: function(form, e) {
        e.preventDefault();

        var form_data = new FormData(form);

        // Collect menu items dynamically
        $('#menuItemsContainer .menu-item').each(function(index) {
            $(this).find('input, select').each(function() {
                var name = $(this).attr('name').replace('[0]', `[${index}]`);
                form_data.append(name, $(this).val());
            });
        });

        // Set up CSRF token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Make AJAX request
        $.ajax({
            url: "{{ route('menu-store') }}",
            type: "POST",
            dataType: "json",
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function() {
                $('#send_btn2')
                    .html("<i class='fa fa-spin fa-spinner'></i> Submitting...")
                    .prop('disabled', true);
                $('#loader').show();
                $('.alert-dismissible').hide();
            },
            success: function(result) {
                if (result.status == '200') {
                    $('#message-container-login')
                        .html('<div class="alert alert-success alert-dismissible">Menu Type Added Successfully! <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>');

                    setTimeout(function() {
                        window.location.href = result.redirect || '/manage-menu';
                    }, 1500);
                } else {
                    $('#message-container-login')
                        .html('<div class="alert alert-danger alert-dismissible">' +
                            (result.message || 'An error occurred. Please try again.') +
                            ' <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>');

                    $('#send_btn2')
                        .html("Submit")
                        .prop('disabled', false);
                }
                $('#loader').hide();
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);

                var errorMessages = '';
                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                    $.each(xhr.responseJSON.errors, function(field, errors) {
                        errorMessages += errors.join('<br>') + '<br>';
                    });
                } else {
                    errorMessages = 'An unexpected error occurred. Please try again.';
                }

                $('#message-container-login')
                    .html('<div class="alert alert-danger alert-dismissible">' +
                        errorMessages +
                        ' <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>');

                $('#send_btn2')
                    .html("Submit")
                    .prop('disabled', false);

                $('#loader').hide();
            }
        });

        return false;
    }
});

// Add custom validation method for file size
$.validator.addMethod('filesize', function(value, element, param) {
    return this.optional(element) || (element.files[0].size <= param);
});

function resetForm() {
            document.getElementById("addUserForm").reset();
            $('#Permissions').bootstrapDualListbox('destroy');
        }
        $('#userPhone').mask('(000) 000-0000');
});
document.addEventListener('DOMContentLoaded', function() {
            const menuItemsContainer = document.getElementById('menuItemsContainer');
            const addMenuItemBtn = document.getElementById('addMenuItem');
            let menuItemIndex = 1;

            addMenuItemBtn.addEventListener('click', function() {
                const newMenuItem = menuItemsContainer.children[0].cloneNode(true);

                // Reset input values
                newMenuItem.querySelectorAll('input, select').forEach(input => {
                    input.value = '';

                    // Update name attributes with new index
                    input.name = input.name.replace(/\[\d+\]/, `[${menuItemIndex}]`);
                });

                // Add remove button functionality
                const removeBtn = newMenuItem.querySelector('.remove-item-btn');
                removeBtn.addEventListener('click', function() {
                    this.closest('.menu-item').remove();
                });

                menuItemsContainer.appendChild(newMenuItem);
                menuItemIndex++;
            });
        });

        function removeItem(btn) {
            // Prevent removing the last menu item
            if (document.querySelectorAll('.menu-item').length > 1) {
                btn.closest('.menu-item').remove();
            } else {
                alert('At least one menu item is required.');
            }
        }
        $(document).ready(function() {
    // Initialize popover
    $('[data-bs-toggle="popover"]').popover({
        html: true,
        content: function() {
            return $('#table-action-popover').html();
        }
    });

    // Store menu ID when action button is clicked
    let currentMenuId;
    $(document).on('click', '.action-btn', function() {
        currentMenuId = $(this).data('id');
    });

    // Edit Menu - Fetch data and open modal
    $(document).on('click', '.edit-menu', function() {
        $.ajax({
            url: `/menus/${currentMenuId}/edit`,
            method: 'GET',
            success: function(response) {
                $('#edit_menu_id').val(response.id);
                $('#edit_menu_name').val(response.name);
                $('#edit_menu_description').val(response.description);
                $('#edit_menu_status').prop('checked', response.is_active == 1);
            }
        });
    });

    // Update Menu
    $('#updateMenuBtn').click(function() {
        $.ajax({
            url: `/menus/${currentMenuId}`,
            method: 'PUT',
            data: $('#editMenuForm').serialize(),
            success: function() {
                location.reload();
            }
        });
    });

    // Delete Menu
    $('#confirmDeleteBtn').click(function() {
        $.ajax({
            url: `/menus/${currentMenuId}`,
            method: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function() {
                location.reload();
            }
        });
    });

    // Toggle Status
    $('.status-toggle').change(function() {
        const menuId = $(this).data('id');
        const isActive = $(this).is(':checked') ? 1 : 0;

        $.ajax({
            url: `/menus/${menuId}/status`,
            method: 'PATCH',
            data: {
                is_active: isActive,
                _token: '{{ csrf_token() }}'
            }
        });
    });
});

  </script>
