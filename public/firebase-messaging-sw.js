importScripts('https://www.gstatic.com/firebasejs/9.6.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/9.6.0/firebase-messaging-compat.js');

const firebaseConfig = {
    apiKey: "AIzaSyBYZUhBBvFtGbaCHlvehFzVr6EUAfNqejI",
    authDomain: "resto-finder-d4214.firebaseapp.com",
    projectId: "resto-finder-d4214",
    storageBucket: "resto-finder-d4214.appspot.com",
    messagingSenderId: "322982009700",
    appId: "1:322982009700:web:838ccc92907296c9f593871"
};

// Initialize Firebase
try {
    if (!self.firebase.apps.length) {
        self.firebase.initializeApp(firebaseConfig);
    }

    // Initialize Firebase Cloud Messaging
    const messaging = self.firebase.messaging(); // This is local to the service worker

    // Handle background messages
    messaging.onBackgroundMessage((payload) => {
        console.log('Received background message:', payload);

        const notificationTitle = payload.notification.title;
        const notificationOptions = {
            body: payload.notification.body,
            icon: '/assets/images/logo.png'
        };

        return self.registration.showNotification(notificationTitle, notificationOptions);
    });
} catch (error) {
    console.error('Error initializing Firebase in service worker:', error);
}