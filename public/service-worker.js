importScripts('https://storage.googleapis.com/workbox-cdn/releases/6.5.4/workbox-sw.js');

workbox.core.clientsClaim();
workbox.precaching.precacheAndRoute(self.__WB_MANIFEST || []);

// Cache static assets
workbox.routing.registerRoute(
  ({request}) => request.destination === 'script' ||
                 request.destination === 'style' ||
                 request.destination === 'image',
  new workbox.strategies.StaleWhileRevalidate()
);

// Cache API GET requests
workbox.routing.registerRoute(
  ({url}) => url.pathname.startsWith('/api/'),
  new workbox.strategies.NetworkFirst()
);

// Background sync for POST/PUT/DELETE
const bgSyncPlugin = new workbox.backgroundSync.BackgroundSyncPlugin('apiQueue', {
  maxRetentionTime: 24 * 60 // Retry for max of 24 Hours
});

workbox.routing.registerRoute(
  ({request}) => request.method === 'POST' || request.method === 'PUT' || request.method === 'DELETE',
  new workbox.strategies.NetworkOnly({
    plugins: [bgSyncPlugin]
  }),
  'POST'
);
