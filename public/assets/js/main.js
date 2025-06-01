jQuery(document).ready(function() {
    // Sidebar Icon View
    jQuery('.collapse-menu').on('click', function(e) {
        e.stopPropagation();
        jQuery(this).toggleClass('active');
        jQuery('body').toggleClass('sidebar-icon-only');
    });
    jQuery(document).on('click', function(e) {
        if (!jQuery('.collapse-menu').is(e.target) && jQuery('.collapse-menu').has(e.target).length === 0) {
            jQuery('.collapse-menu').removeClass('active');
            jQuery('body').removeClass('sidebar-icon-only');
        }
    });

    // DataTable Initialization and Changes
    var dataTable = jQuery('.custom-datatable').DataTable({
        'dom': '<"top"f>rt<"bottom"<"left"i><"center"p><"right"l>><"clear">',
        'language': {
            'lengthMenu': 'Show _MENU_ Entries',
            'search': ''
        },
        'columnDefs': [
            {
                'targets': -1,
                'orderable': false
            }
        ],
        'autoWidth': true,
        initComplete: function () {
            jQuery('.dataTables_filter').hide();
            jQuery('.dataTables_filter input').attr('placeholder', 'Search Article').wrap('<div class="search-container position-relative"></div>');
            jQuery('.custom-datatable').wrap('<div class="table-wrapper custom-scroll-content overflow-auto pb-3"></div>');
        },
        'oLanguage': {
            'oPaginate': {
                'sNext': '<svg width="33" height="32" viewBox="0 0 33 32" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M28.5 0C30.7091 0 32.5 1.79086 32.5 4V28C32.5 30.2091 30.7091 32 28.5 32H4.5C2.29086 32 0.5 30.2091 0.5 28V4C0.5 1.79086 2.29086 0 4.5 0H28.5ZM15.7924 21.2924C15.4014 21.6835 15.4014 22.3174 15.7924 22.7084C16.1835 23.0995 16.8174 23.0995 17.2084 22.7084L23.2084 16.7084C23.3965 16.5208 23.5022 16.2661 23.5022 16.0004C23.5022 15.7348 23.3965 15.4801 23.2084 15.2924L17.2084 9.29245C16.8174 8.90143 16.1835 8.90143 15.7924 9.29245C15.4014 9.68346 15.4014 10.3174 15.7924 10.7084L20.0858 15H10.5C9.94771 15 9.5 15.4477 9.5 16C9.5 16.5523 9.94771 17 10.5 17H20.0858L15.7924 21.2924Z" fill="#A5ACBC"/></svg>',
                'sPrevious': '<svg width="33" height="32" viewBox="0 0 33 32" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M4.5 0C2.29086 0 0.5 1.79086 0.5 4V28C0.5 30.2091 2.29086 32 4.5 32H28.5C30.7091 32 32.5 30.2091 32.5 28V4C32.5 1.79086 30.7091 0 28.5 0H4.5ZM17.2076 21.2924C17.5986 21.6835 17.5986 22.3174 17.2076 22.7084C16.8165 23.0995 16.1826 23.0995 15.7916 22.7084L9.79155 16.7084C9.60347 16.5208 9.49777 16.2661 9.49777 16.0004C9.49777 15.7348 9.60347 15.4801 9.79155 15.2924L15.7916 9.29245C16.1826 8.90143 16.8165 8.90143 17.2076 9.29245C17.5986 9.68346 17.5986 10.3174 17.2076 10.7084L12.9142 15H22.5C23.0523 15 23.5 15.4477 23.5 16C23.5 16.5523 23.0523 17 22.5 17H12.9142L17.2076 21.2924Z" fill="#A5ACBC"/></svg>'
            }
        }
    });
    // DataTable Custom Search
    jQuery('.custom-search').on('keyup', function() {
        var searchTerm = jQuery(this).val();
        dataTable.search(searchTerm).draw();
    });
    // DataTable Custom Filter
    jQuery('.chapter-filter select').on('change', function() {
        var selectedChapter = jQuery(this).val();
        var columnIndex = 1;
        if (selectedChapter === "All") {
            dataTable.column(columnIndex).search('').draw();
        }
        else if (selectedChapter) {
            dataTable.column(columnIndex).search(selectedChapter).draw();
        }
        else {
            dataTable.column(columnIndex).search('').draw();
        }
    });

    // Toggle Action button
    // jQuery('.action-icon').on('click', function(e) {
    //     e.stopPropagation();
    //     var actionMenu = jQuery(this).closest('td').find('.action-menu');
    //     actionMenu.toggle();
    // });
    // jQuery(document).on('click', function(e) {
    //     if (!jQuery(e.target).closest('.action-icon').length) {
    //         jQuery('.action-menu').hide();
    //     }
    // });

    // Toggle Filter Options
    jQuery('.filter-btn').click(function(e) {
        e.stopPropagation();
        jQuery(this).toggleClass('active');
        jQuery(this).closest('.filter-header-options').next('.filter-col-options').slideToggle();
    });

    // Profile Image Change
    function imageChange(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var profileIcon = jQuery(input).closest('.user-profile-icon');
                profileIcon.find('.user-profile-pic').attr('src', e.target.result).removeClass('d-none');
                profileIcon.find('.user-profile-delete').removeClass('d-none');
                profileIcon.find('.upload-button').addClass('d-none');
            }
            reader.readAsDataURL(input.files[0]);
        }
    };
    jQuery('.file-upload').on('change', function() {
        imageChange(this);
    });
    jQuery('.upload-button').on('click', function() {
        var uploadButton = jQuery(this).closest('.user-profile-add');
        uploadButton.find('.file-upload').click();
    });
    jQuery('.user-profile-delete').on('click', function() {
        var deleteIcon = jQuery(this).closest('.user-profile-icon');
        deleteIcon.find('.user-profile-pic').attr('src', '').addClass('d-none');
        deleteIcon.find('.user-profile-delete').addClass('d-none');
        deleteIcon.find('.upload-button').removeClass('d-none');
    });

    // Uploaded File Name
    jQuery('.custom-file-upload input').change(function() {
        jQuery(this).closest('.custom-file-upload').find('.selected-file').text(this.files[0].name);
    });

    // Image Loading
    jQuery('img').attr('loading','lazy');

    // Initialize Popove
    jQuery('[data-bs-toggle="popover"]').popover({
        html: true,
        customClass: 'action-list-options',
        content: jQuery('[data-name="table-action-btn"]')
    });
    // Closing the popover when clicking outside
    jQuery(document).on('click', function (e) {
        jQuery('[data-bs-toggle="popover"]').popover('hide');
    });
    jQuery(document).on('click', '.popover', function (e) {
        e.stopPropagation();
    });
    // When other row is clicked hide the actions of the previous one
    jQuery(document).on('click', '.action-icon', function (e) {
        jQuery('[data-bs-toggle="popover"]').popover('hide');
        var popover = jQuery(this).closest('td').find('[data-bs-toggle="popover"]').popover('show');
        e.stopPropagation();
    });
});

// Loader Hide
jQuery(window).on('load', function() {  
    jQuery('.loader-block').fadeOut();    
});