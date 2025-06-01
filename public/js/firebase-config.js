// Initialize Firebase globally
let messaging;
let firebase;
if (typeof window.messaging === 'undefined') {
    window.messaging = null;
}
if (typeof window.firebase === 'undefined') {
    window.firebase = null;
}

// Fetch Firebase config from server
async function fetchFirebaseConfig() {
    try {
        const response = await fetch('/firebase-config');
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        window.firebaseConfig = await response.json();
        console.log('Firebase config loaded from server');
        return window.firebaseConfig;
    } catch (error) {
        console.error('Error loading Firebase config:', error);
        return null;
    }
}

// Initialize Firebase and messaging
async function initializeMessaging() {
    try {
        // Ensure Firebase config is loaded
        if (!window.firebaseConfig) {
            await fetchFirebaseConfig();
        }

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

        // Set window.messaging to the Firebase messaging instance
        window.messaging = firebase.messaging();
        document.dispatchEvent(new Event('messaging-ready'));

        // Rest of your initialization code remains the same
        // ...
    } catch (error) {
        console.error('Error in FCM setup:', error);
        // Only throw error if it's not related to authentication
        if (error.message && !error.message.includes('401')) {
            throw error;
        }
    }
}

// Initialize messaging when the page loads
document.addEventListener('DOMContentLoaded', async () => {
    // Add a delay to ensure Firebase SDK is fully loaded
    setTimeout(async () => {
        try {
            await initializeMessaging();
        } catch (error) {
            // Only log error if it's not related to authentication
            if (error && !error.message.includes('401')) {
                console.error('Failed to initialize messaging:', error);
            }
        }
    }, 1000);
});
