import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: true
});

// Listen for database changes
window.Echo.channel('database-changes')
    .listen('.database.changed', (e) => {
        console.log('Database change detected:', e);
        // Handle the database change event
        // You can update your UI or trigger a refresh here
        if (e.action === 'insert') {
            console.log(`New record inserted in ${e.table}:`, e.data);
        } else if (e.action === 'update') {
            console.log(`Record updated in ${e.table}:`, e.data);
        } else if (e.action === 'delete') {
            console.log(`Record deleted from ${e.table}:`, e.data);
        }
    });
