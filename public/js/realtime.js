// Real-time functionality using Laravel Echo
class RealtimeManager {
    constructor() {
        this.channels = {};
        this.apiBase = '/api/v1';
    }

    // Subscribe to orders channel
    subscribeToOrders(callback) {
        const channel = window.Echo.channel('orders');

        channel.listen('OrderCreated', (data) => {
            console.log('New order received:', data);
            if (callback) callback(data);
            this.refreshOrdersList();
        });

        channel.listen('OrderStatusUpdated', (data) => {
            console.log('Order status updated:', data);
            if (callback) callback(data);
            this.refreshOrdersList();
        });

        this.channels.orders = channel;
        return channel;
    }

    // Subscribe to restaurant-specific channel
    subscribeToRestaurant(restaurantId, callback) {
        const channelName = `restaurant.${restaurantId}`;
        const channel = window.Echo.channel(channelName);

        channel.listen('ReservationCreated', (data) => {
            console.log('New reservation received:', data);
            if (callback) callback(data);
            this.refreshReservationsList();
        });

        channel.listen('ServiceStatusUpdated', (data) => {
            console.log('Service status updated:', data);
            if (callback) callback(data);
        });

        this.channels[channelName] = channel;
        return channel;
    }

    // Subscribe to user-specific channel
    subscribeToUser(userId, callback) {
        const channelName = `App.Models.User.${userId}`;
        const channel = window.Echo.private(channelName);

        channel.listen('OrderStatusUpdated', (data) => {
            console.log('Your order status updated:', data);
            if (callback) callback(data);
        });

        this.channels[channelName] = channel;
        return channel;
    }

    // Subscribe to service status channel
    subscribeToServiceStatus(restaurantId, callback) {
        const channelName = `service-status.${restaurantId}`;
        const channel = window.Echo.channel(channelName);

        channel.listen('ServiceStatusUpdated', (data) => {
            console.log('Service status updated:', data);
            if (callback) callback(data);
        });

        this.channels[channelName] = channel;
        return channel;
    }

    // Fetch orders from existing API
    async fetchOrders(params = {}) {
        try {
            const queryString = new URLSearchParams(params).toString();
            const response = await fetch(`${this.apiBase}/orders?${queryString}`, {
                headers: {
                    'Authorization': `Bearer ${this.getAuthToken()}`,
                    'Content-Type': 'application/json',
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            return data;
        } catch (error) {
            console.error('Error fetching orders:', error);
            return null;
        }
    }

    // Fetch reservations from existing API
    async fetchReservations(params = {}) {
        try {
            const queryString = new URLSearchParams(params).toString();
            const response = await fetch(`${this.apiBase}/reservations?${queryString}`, {
                headers: {
                    'Authorization': `Bearer ${this.getAuthToken()}`,
                    'Content-Type': 'application/json',
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            return data;
        } catch (error) {
            console.error('Error fetching reservations:', error);
            return null;
        }
    }

    // Refresh orders list using existing API
    async refreshOrdersList() {
        const ordersData = await this.fetchOrders();
        if (ordersData && ordersData.status === 'success') {
            this.updateOrdersTable(ordersData.data);
        }
    }

    // Refresh reservations list using existing API
    async refreshReservationsList() {
        const reservationsData = await this.fetchReservations();
        if (reservationsData && reservationsData.status === 'success') {
            this.updateReservationsTable(reservationsData.data);
        }
    }

    // Update orders table with new data
    updateOrdersTable(orders) {
        const table = $('#manageOrdersTable').DataTable();
        if (table) {
            // Clear existing data
            table.clear();

            // Add new data
            orders.forEach((order, index) => {
                const statusClass = this.getStatusBadgeClass(order.status);
                const statusText = order.status.charAt(0).toUpperCase() + order.status.slice(1);
                const customerName = `${order.user?.first_name || ''} ${order.user?.last_name || ''}`.trim();

                table.row.add([
                    index + 1,
                    customerName,
                    `$${parseFloat(order.total_amount).toFixed(2)}`,
                    `<span class="badge rounded-pill bg-${statusClass}">${statusText}</span>`,
                    new Date(order.created_at).toLocaleString(),
                    this.createOrderActionsHtml(order.id, order.status)
                ]);
            });

            table.draw();
        }
    }

    // Update reservations table with new data
    updateReservationsTable(reservations) {
        const table = $('#manageReservationsTable').DataTable();
        if (table) {
            // Clear existing data
            table.clear();

            // Add new data
            reservations.forEach((reservation, index) => {
                const statusClass = this.getStatusBadgeClass(reservation.status);
                const statusText = reservation.status.charAt(0).toUpperCase() + reservation.status.slice(1);
                const customerName = `${reservation.user?.first_name || ''} ${reservation.user?.last_name || ''}`.trim();
                const reservationTime = reservation.reservation_time ? new Date(reservation.reservation_time).toLocaleString() : 'N/A';

                table.row.add([
                    index + 1,
                    customerName,
                    reservationTime,
                    reservation.number_of_people,
                    reservation.phone_number || 'N/A',
                    `<span class="badge rounded-pill bg-${statusClass}">${statusText}</span>`,
                    new Date(reservation.created_at).toLocaleString(),
                    this.createReservationActionsHtml(reservation.id)
                ]);
            });

            table.draw();
        }
    }

    // Create order actions HTML
    createOrderActionsHtml(orderId, status) {
        const disabledAttr = status === 'completed' ? 'disabled style="pointer-events: none; opacity: 0.5;"' : '';
        return `
            <div class="dropdown">
                <button class="btn btn-sm btn-link" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <button type="button" class="dropdown-item view-action" data-order-id="${orderId}">
                            <i class="fas fa-eye"></i> View
                        </button>
                    </li>
                    <li>
                        <button type="button" class="dropdown-item update-status-action"
                            data-bs-toggle="modal"
                            data-bs-target="#updateStatus"
                            data-order-id="${orderId}"
                            ${disabledAttr}>
                            <i class="fas fa-edit"></i> <span class="update-status-text">Update Status</span>
                            <span class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
                        </button>
                    </li>
                    <li>
                        <button type="button" class="dropdown-item delete-action"
                            data-bs-toggle="modal"
                            data-bs-target="#deleteOrder"
                            data-order-id="${orderId}">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </li>
                </ul>
            </div>
        `;
    }

    // Create reservation actions HTML
    createReservationActionsHtml(reservationId) {
        return `
            <div class="action-col position-relative d-inline-block">
                <a href="javascript:;" class="p-1" data-bs-toggle="popover" data-bs-placement="top">
                    <svg class="action-icon cursor-pointer" width="20" height="4" viewBox="0 0 20 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10 0C11.1046 0 12 0.89543 12 2C12 3.10457 11.1046 4 10 4C8.89543 4 8 3.10457 8 2C8 0.89543 8.89543 0 10 0Z" fill="#2D264B"/>
                        <path d="M2 -4.76837e-07C3.10457 -4.76837e-07 4 0.89543 4 2C4 3.10457 3.10457 4 2 4C0.89543 4 0 3.10457 0 2C0 0.89543 0.89543 -4.76837e-07 2 -4.76837e-07Z" fill="#2D264B"/>
                        <path d="M18 2.38419e-07C19.1046 2.38419e-07 20 0.895431 20 2C20 3.10457 19.1046 4 18 4C16.8954 4 16 3.10457 16 2C16 0.895431 16.8954 2.38419e-07 18 2.38419e-07Z" fill="#2D264B"/>
                    </svg>
                </a>
                <div class="popover-content" data-name="table-action-btn">
                    <div class="action-menu">
                        <ul class="action-menu-list position-relative bg-white rounded-1 p-2">
                            <li class="action-menu-item text-start">
                                <a href="javascript:;" class="action-menu-link font-dmsans fw-normal text-primary-v1 xsmall d-block p-1 view-action" data-bs-toggle="modal" data-bs-target="#viewReservation" data-reservation-id="${reservationId}" title="View">View</a>
                            </li>
                            <li class="action-menu-item text-start">
                                <a href="javascript:;" class="action-menu-link font-dmsans fw-normal text-primary-v1 xsmall d-block p-1 update-status-action" data-bs-toggle="modal" data-bs-target="#updateStatus" data-reservation-id="${reservationId}" title="Update Status">Update Status</a>
                            </li>
                            <li class="action-menu-item text-start">
                                <a href="javascript:;" class="action-menu-link font-dmsans fw-normal text-primary-v1 xsmall d-block p-1 delete-action" data-bs-toggle="modal" data-bs-target="#deleteReservation" data-reservation-id="${reservationId}" title="Delete">Delete</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        `;
    }

    // Get authentication token
    getAuthToken() {
        // Try to get token from localStorage or sessionStorage
        return localStorage.getItem('auth_token') || sessionStorage.getItem('auth_token') || '';
    }

    // Unsubscribe from a channel
    unsubscribe(channelName) {
        if (this.channels[channelName]) {
            window.Echo.leave(channelName);
            delete this.channels[channelName];
        }
    }

    // Unsubscribe from all channels
    unsubscribeAll() {
        Object.keys(this.channels).forEach(channelName => {
            window.Echo.leave(channelName);
        });
        this.channels = {};
    }

    // Show notification
    showNotification(title, message, type = 'info') {
        // You can customize this to use your preferred notification library
        if (typeof toastr !== 'undefined') {
            toastr[type](message, title);
        } else if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: title,
                text: message,
                icon: type,
                timer: 3000,
                showConfirmButton: false
            });
        } else {
            // Fallback to browser notification
            if (Notification.permission === 'granted') {
                new Notification(title, { body: message });
            } else if (Notification.permission !== 'denied') {
                Notification.requestPermission().then(permission => {
                    if (permission === 'granted') {
                        new Notification(title, { body: message });
                    }
                });
            }
        }
    }

    // Get badge class based on status
    getStatusBadgeClass(status) {
        const statusClasses = {
            'pending': 'warning',
            'processing': 'info',
            'completed': 'success',
            'cancelled': 'danger',
            'confirmed': 'success'
        };
        return statusClasses[status] || 'secondary';
    }
}

// Initialize real-time manager when document is ready
document.addEventListener('DOMContentLoaded', function() {
    window.realtimeManager = new RealtimeManager();

    // Set up notifications based on current page
    const currentPage = window.location.pathname;

    if (currentPage.includes('/manage-orders')) {
        // Subscribe to orders for restaurant staff
        const restaurantId = document.querySelector('[data-restaurant-id]')?.dataset.restaurantId;
        if (restaurantId) {
            window.realtimeManager.subscribeToOrders((data) => {
                window.realtimeManager.showNotification(
                    'New Order',
                    `Order #${data.order.id} received - $${data.order.total_amount}`,
                    'success'
                );
            });
        }
    }

    if (currentPage.includes('/manage-reservations')) {
        // Subscribe to reservations for restaurant staff
        const restaurantId = document.querySelector('[data-restaurant-id]')?.dataset.restaurantId;
        if (restaurantId) {
            window.realtimeManager.subscribeToRestaurant(restaurantId, (data) => {
                window.realtimeManager.showNotification(
                    'New Reservation',
                    `Reservation #${data.reservation.id} for ${data.reservation.party_size} people`,
                    'info'
                );
            });
        }
    }

    if (currentPage.includes('/dashboard')) {
        // Subscribe to general updates for dashboard
        const restaurantId = document.querySelector('[data-restaurant-id]')?.dataset.restaurantId;
        if (restaurantId) {
            window.realtimeManager.subscribeToOrders((data) => {
                window.realtimeManager.showNotification(
                    'New Order',
                    `Order #${data.order.id} received - $${data.order.total_amount}`,
                    'success'
                );
            });

            window.realtimeManager.subscribeToRestaurant(restaurantId, (data) => {
                window.realtimeManager.showNotification(
                    'New Reservation',
                    `Reservation #${data.reservation.id} for ${data.reservation.party_size} people`,
                    'info'
                );
            });
        }
    }
});

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = RealtimeManager;
}
