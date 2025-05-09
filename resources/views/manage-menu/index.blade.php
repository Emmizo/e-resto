@extends('layouts.app')
@section('content')
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
                <a href="javascript:;" class="action-menu-link font-dmsans fw-normal text-primary-v1 xsmall d-block p-1 edit-menu" data-bs-toggle="modal" data-bs-target="#editMenuModal" data-menu-id="">Edit</a>
            </li>
            <li class="action-menu-item text-start">
                <a href="javascript:;" class="action-menu-link font-dmsans fw-normal text-primary-v1 xsmall d-block p-1 delete-menu" data-bs-toggle="modal" data-bs-target="#deleteMenuModal" data-menu-id="">Delete</a>
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
<div class="modal fade" id="editMenuModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editMenuModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h1 class="modal-title fs-5 font-dmsans fw-bold text-primary-v1" id="editMenuModalLabel">Edit Menu Type</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="edit-message-container"></div>
            <form action="" method="POST" id="editMenuForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_menu_id" name="id">
                <div class="modal-body">
                    <div class="modal-form">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group m-0 mb-3 pb-1">
                                    <label for="edit_menu_name" class="form-label">Name <span class="asterik">*</span></label>
                                    <input type="text" class="form-control rounded-3" id="edit_menu_name" placeholder="Enter name" name="name" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group m-0 mb-3">
                                    <label for="edit_menu_description" class="form-label">Description<span class="asterik">*</span></label>
                                    <textarea class="form-control rounded-3" id="edit_menu_description" name="description" rows="3" placeholder="Enter Menu Description" required></textarea>
                                </div>
                            </div>
                            {{-- <div class="col-md-12">
                                <div class="form-group m-0 mb-3">
                                    <label class="form-check-label">Status</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="edit_menu_status" name="is_active">
                                        <label class="form-check-label" for="edit_menu_status">Active</label>
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="m-0">Menu Items</h4>
                            <button type="button" class="btn btn-success btn-sm" id="editAddMenuItem">
                                <i class="bi bi-plus-circle me-1"></i>Add Menu Item
                            </button>
                        </div>
                        <div id="editMenuItemsContainer">
                            <!-- Menu items will be loaded here dynamically -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-start">
                    <button type="submit" class="btn btn-primary btn-small fw-semibold text-uppercase rounded-3" id="updateMenuBtn">Update</button>
                    <button type="button" class="btn btn-outline btn-small fw-semibold text-uppercase rounded-3 border border-grey-v1" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Menu Modal -->
<div class="modal fade" id="deleteMenuModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="font-dmsans text-primary-v1 medium mb-4">Are you sure you want to delete this menu?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteMenu">Delete</button>
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
    let menuItemCounter = 0;

    // Initialize popovers with click trigger
    $('[data-bs-toggle="popover"]').popover({
        html: true,
        trigger: 'click',
        content: function() {
            const menuId = $(this).data('id');
            const popoverContent = $('#table-action-popover').html();
            return popoverContent.replace(/data-menu-id=""/g, `data-menu-id="${menuId}"`);
        },
        sanitize: false
    }).on('shown.bs.popover', function () {
        // Store the current menu ID in a data attribute on the document
        $(document).data('currentMenuId', $(this).data('id'));
    });

    // Close popover when clicking outside
    $(document).on('click', function (e) {
        if ($(e.target).closest('[data-bs-toggle="popover"]').length === 0 &&
            $(e.target).closest('.popover').length === 0) {
            $('[data-bs-toggle="popover"]').popover('hide');
        }
    });

    // Handle edit button click
    $(document).on('click', '.edit-menu', function(e) {
        e.preventDefault();
        const menuId = $(document).data('currentMenuId');

        if (!menuId) {
            console.error('No menu ID available');
            return;
        }

        // Hide the popover
        $('[data-bs-toggle="popover"]').popover('hide');

        // Clear previous menu items
        $('#editMenuItemsContainer').empty();

        $.ajax({
            url: `/menu/${menuId}/edit`,
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.status === 200) {
                    const menu = response.menu;

                    // Set main menu details
                    $('#edit_menu_id').val(menu.id);
                    $('#edit_menu_name').val(menu.name);
                    $('#edit_menu_description').val(menu.description);
                    $('#edit_menu_status').prop('checked', menu.is_active == 1);

                    // Load menu items
                    if (menu.menu_items && menu.menu_items.length > 0) {
                        menu.menu_items.forEach((item, index) => {
                            addEditMenuItem(item, index);
                        });
                    } else {
                        // Add an empty menu item if none exist
                        addEditMenuItem(null, 0);
                    }

                    $('#editMenuModal').modal('show');
                } else {
                    alert('Failed to load menu data. Please try again.');
                }
            },
            error: function(xhr) {
                console.error('Error fetching menu data:', xhr.responseText);
                alert('Failed to load menu data. Please try again.');
            }
        });
    });

    // Function to add menu item in edit form
    function addEditMenuItem(item, index) {
        const menuItem = `
            <div class="menu-item row mb-3" data-index="${index}">
                <input type="hidden" name="menu_items[${index}][id]" value="${item ? item.id : ''}">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="menu_items[${index}][name]"
                           placeholder="Item Name" value="${item ? item.name : ''}" required>
                </div>
                <div class="col-md-3">
                    <input type="number" class="form-control" name="menu_items[${index}][price]"
                           placeholder="Price" value="${item ? item.price : ''}" required>
                </div>

                <div class="col-md-5">
                    <div class="input-group">
                        <input type="file" class="form-control" name="menu_items[${index}][image]" accept="image/*">
                        ${item && item.image ? `
                            <div class="mt-2 w-100">
                                <img src="${item.image}" alt="${item.name}" class="img-thumbnail" style="height: 100px; width: 100px;">
                                <input type="hidden" name="menu_items[${index}][existing_image]" value="${item.image}">
                            </div>
                        ` : ''}
                    </div>
                </div>
                <div class="col-md-4 mt-2">
                    <input type="text" class="form-control" name="menu_items[${index}][category]"
                           placeholder="Category" value="${item ? item.category || '' : ''}">
                </div>
                <div class="col-md-3 mt-2">
                    <input type="text" class="form-control" name="menu_items[${index}][dietary_info]"
                           placeholder="Dietary Info" value="${item ? item.dietary_info || '' : ''}">
                </div>
                <div class="col-md-3 mt-2">
                    <select class="form-control" name="menu_items[${index}][is_available]">
                        <option value="1" ${item && item.is_available == 1 ? 'selected' : ''}>Available</option>
                        <option value="0" ${item && item.is_available == 0 ? 'selected' : ''}>Not Available</option>
                    </select>
                </div>
                <div class="col-md-2 mt-2">
                    <button type="button" class="btn btn-danger remove-edit-item-btn" onclick="removeEditItem(this)">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
            <hr>
        `;

        $('#editMenuItemsContainer').append(menuItem);
    }

    // Add menu item button for edit form
    $('#editAddMenuItem').click(function() {
        const index = $('#editMenuItemsContainer .menu-item').length;
        addEditMenuItem(null, index);
    });

    // Remove menu item in edit form
    window.removeEditItem = function(btn) {
        // Prevent removing the last menu item
        if ($('#editMenuItemsContainer .menu-item').length > 1) {
            $(btn).closest('.menu-item').next('hr').remove();
            $(btn).closest('.menu-item').remove();
        } else {
            alert('At least one menu item is required.');
        }
    };

    // Update Menu
    $('#editMenuForm').submit(function(e) {
        e.preventDefault();
        const menuId = $('#edit_menu_id').val();

        // Create a FormData object to handle file uploads
        const formData = new FormData(this);
        formData.append('_method', 'PUT');

        $.ajax({
            url: `/menu/${menuId}`,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                $('#updateMenuBtn')
                    .html("<i class='fa fa-spin fa-spinner'></i> Updating...")
                    .prop('disabled', true);
            },
            success: function(response) {
                if (response.status === 200) {
                    $('#editMenuModal').modal('hide');
                    alert('Menu updated successfully!');
                    location.reload();
                }
            },
            error: function(xhr) {
                console.error('Error updating menu:', xhr.responseText);
                let errorMessage = 'Failed to update menu. Please try again.';

                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMessage = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                }

                $('#edit-message-container').html(
                    `<div class="alert alert-danger alert-dismissible">${errorMessage}
                     <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>`
                );

                $('#updateMenuBtn')
                    .html("Update")
                    .prop('disabled', false);
            }
        });
    });

    // Delete Menu functionality
    $(document).on('click', '.delete-menu', function() {
        const menuId = $(this).data('menu-id');
        console.log('Delete Menu ID:', menuId);

        if (!menuId) {
            console.error('No menu ID found');
            return;
        }

        // Set the current menu ID in the delete modal
        $('#deleteMenuModal').data('menu-id', menuId);
        $('#deleteMenuModal').modal('show');
    });

    // Confirm delete action
    $('#confirmDeleteMenu').click(function() {
        const menuId = $('#deleteMenuModal').data('menu-id');

        $.ajax({
            url: `/menus/${menuId}`,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#deleteMenuModal').modal('hide');
                alert('Menu deleted successfully!');
                location.reload();
            },
            error: function(xhr) {
                console.error('Error deleting menu:', xhr.responseText);
                alert('Failed to delete menu. Please try again.');
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
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                is_active: isActive
            },
            success: function(response) {
                console.log('Status updated successfully');
            },
            error: function(xhr) {
                console.error('Error updating status:', xhr.responseText);
                // Revert toggle if there was an error
                $(this).prop('checked', !isActive);
            }
        });
    });
});

  </script>
@endsection
