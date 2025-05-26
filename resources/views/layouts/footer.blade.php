<script src="{{asset('assets/js/jquery.min.js')}}"></script>
    <script src="{{asset('assets/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('assets/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/js/main.js')}}"></script>
    <script src="{{ asset('assets/js/bootstrapValidator.min.js') }}"></script>

    <script src="{{ asset('assets/js/slick.js') }}" type="text/javascript" charset="utf-8"></script>
<script src="{{ asset('assets/js/script.js') }}"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/additional-methods.min.js"></script>
    <script src="{{ asset('plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js') }}"></script>
<!-- Add Moment.js for date formatting -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            }
        });

    </script>

<script>
    // Request permission for notifications
    function requestNotificationPermission() {
        if (!window.messaging) {
            console.error('Firebase messaging is not initialized');
            return;
        }
        window.messaging.requestPermission()
            .then(() => {
                console.log('Notification permission granted.');
                return window.messaging.getToken();
            })
            .then((token) => {
                console.log('FCM Token:', token);
                // Send token to your Laravel backend
                $.ajax({
                    url: '/save-fcm-token',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        fcm_token: token,
                        user_id: '{{ Auth::id() }}'
                    },
                    success: function(response) {
                        console.log('Token saved successfully');
                    }
                });
            })
            .catch((err) => {
                console.log('Unable to get permission to notify.', err);
            });
    }

    // Wait for Firebase messaging to be ready
    document.addEventListener('messaging-ready', function() {
        requestNotificationPermission();
        window.messaging.onMessage((payload) => {
            // Play notification sound
            const audio = new Audio('/assets/sounds/notification.mp3');
            audio.play();

            // Update notification badge
            const notificationBadge = $('.notification-badge');
            const currentCount = parseInt(notificationBadge.text()) || 0;
            notificationBadge.text(currentCount + 1).show();

            // Add notification to dropdown
            const notificationList = $('.notification-list');
            const notification = `
                <li class=\"px-3 py-2 border-bottom\">
                    <div class=\"d-flex align-items-center\">
                        <div class=\"flex-grow-1\">
                            <h6 class=\"mb-1\">${payload.notification.title}</h6>
                            <p class=\"mb-0 small text-muted\">${payload.notification.body}</p>
                            <small class=\"text-muted\">${new Date().toLocaleTimeString()}</small>
                        </div>
                    </div>
                </li>
            `;
            notificationList.prepend(notification);
        });
    });
</script>

<script>
$(document).ready(function() {
    // Fetch notifications on page load
    let notificationsUrl = '/api/v1/notifications';
    if (window.currentRestaurantId) {
        notificationsUrl += '?restaurant_id=' + window.currentRestaurantId;
    }
    $.ajax({
        url: notificationsUrl,
        method: 'GET',
        success: function(response) {
            const notificationBadge = $('.notification-badge');
            const notificationList = $('.notification-list');
            notificationList.empty();
            let unreadCount = response.unread_count || 0;
            if (unreadCount > 0) {
                notificationBadge.text(unreadCount).show();
            } else {
                notificationBadge.text(0).hide();
            }
            response.notifications.forEach(function(notification) {
                const isUnread = !notification.is_read;
                const item = `
                    <li class="px-3 py-2 border-bottom${isUnread ? ' bg-light' : ''}" data-id="${notification.id}">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">${notification.title}</h6>
                                <p class="mb-0 small text-muted">${notification.body}</p>
                                <small class="text-muted">${new Date(notification.created_at).toLocaleTimeString()}</small>
                            </div>
                        </div>
                    </li>
                `;
                notificationList.append(item);
            });
        }
    });

    // Mark notifications as read when dropdown is opened
    $('#notificationsDropdown').on('show.bs.dropdown', function() {
        // Get all unread notification IDs
        const ids = [];
        $('.notification-list li.bg-light').each(function() {
            const notificationId = $(this).data('id');
            if (notificationId) ids.push(notificationId);
        });
        if (ids.length > 0) {
            $.ajax({
                url: '/api/v1/notifications/mark-as-read',
                method: 'POST',
                data: { ids: ids },
                success: function() {
                    $('.notification-badge').text(0).hide();
                    $('.notification-list li.bg-light').removeClass('bg-light');
                }
            });
        }
    });
});
</script>

@if(session('userData.users.restaurant_id'))
<script>
    window.currentRestaurantId = {{ session('userData.users.restaurant_id') }};
</script>
@endif

