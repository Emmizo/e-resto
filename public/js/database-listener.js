document.addEventListener('DOMContentLoaded', function() {
    // Listen for database changes using globally initialized window.Echo
    if (window.Echo && typeof window.Echo.channel === 'function') {
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
    } else {
        console.error('Echo is not initialized or channel is not a function');
    }
});
