// Initialize Firebase globally

let messaging;
let firebase;
if (typeof window.messaging === 'undefined') {
    window.messaging = null;
}
if (typeof window.firebase === 'undefined') {
    window.firebase = null;
}
// firebase-config.js
// Initialize Firebase configuration globally if not already defined
if (typeof window.firebaseConfig === 'undefined') {
    window.firebaseConfig = {
        apiKey: "AIzaSyBYZUhBBvFtGbaCHlvehFzVr6EUAfNqejI",
        authDomain: "resto-finder-d4214.firebaseapp.com",
        projectId: "resto-finder-d4214",
        storageBucket: "resto-finder-d4214.appspot.com",
        messagingSenderId: "322982009700",
        appId: "1:322982009700:web:838ccc92907296c9f593871",
        measurementId: "G-N5TB40170J"
    };
    ;
}

// Check if user is authenticated
function isUserAuthenticated() {
    // Check for auth token or any other authentication indicator
    const authElement = document.querySelector('meta[name="user-auth"]');
    return authElement && authElement.content === 'authenticated';
}

// Initialize Firebase and messaging
async function initializeMessaging() {
    try {
        // Check if Firebase is defined
        if (typeof window.firebase === 'undefined') {
            console.error('Firebase SDK not loaded');
            return;
        }

        // Set local firebase reference
        firebase = window.firebase;

        // Initialize Firebase if not already initialized
        if (!firebase.apps.length) {
            firebase.initializeApp(window.firebaseConfig);
            console.log('Firebase initialized successfully');
        } else {
            console.log('Firebase already initialized');
        }

        // Initialize messaging
        if (!messaging) {
            messaging = firebase.messaging();

            // Register service worker
            const registration = await navigator.serviceWorker.register('/firebase-messaging-sw.js');
            console.log('Service Worker registered');

            // Set the service worker for messaging
            messaging.useServiceWorker(registration);

            // Request notification permission
            const permission = await Notification.requestPermission();
            console.log('Notification permission:', permission);

            if (permission === 'granted') {
                // Get the token
                const token = await messaging.getToken();
                console.log('FCM Token:', token);

                // Only try to store token if user is authenticated
                if (token && isUserAuthenticated()) {
                    try {
                        const csrf_token = document.querySelector('meta[name="csrf-token"]').content;
                        const response = await fetch('/api/store-fcm-token', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrf_token
                            },
                            body: JSON.stringify({ fcm_token: token })
                        });

                        if (!response.ok) {
                            const errorText = await response.text();
                            console.error('Server response:', response.status, errorText);
                            throw new Error(`Network response was not ok: ${response.status}`);
                        }

                        console.log('FCM token stored successfully');
                    } catch (error) {
                        // Only log error if user is authenticated
                        if (isUserAuthenticated()) {
                            console.error('Error storing FCM token:', error);
                        } else {
                            console.log('User not authenticated. Token storage skipped.');
                        }
                    }
                } else if (!isUserAuthenticated()) {
                    console.log('User not authenticated. Token storage skipped.');
                }

                // Handle incoming messages
                messaging.onMessage((payload) => {
                    console.log('Message received:', payload);
                    const notificationTitle = payload.notification.title;
                    const notificationOptions = {
                        body: payload.notification.body,
                        icon: '/assets/images/logo.png'
                    };
                    new Notification(notificationTitle, notificationOptions);
                });
            }
        }
    } catch (error) {
        console.error('Error in FCM setup:', error);
        // Only throw error if it's not related to authentication
        if (error.message && !error.message.includes('401')) {
            throw error;
        }
    }
}

// Initialize messaging when the page loads
document.addEventListener('DOMContentLoaded', () => {
    // Add a delay to ensure Firebase SDK is fully loaded
    setTimeout(() => {
        initializeMessaging().catch(error => {
            // Only log error if it's not related to authentication
            if (error && !error.message.includes('401')) {
                console.error('Failed to initialize messaging:', error);
            }
        });
    }, 1000);
});
