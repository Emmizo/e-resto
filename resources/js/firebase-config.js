// Firebase configuration
const firebaseConfig = {
    apiKey: "{{ env('FIREBASE_API_KEY') }}",
    authDomain: "{{ env('FIREBASE_AUTH_DOMAIN') }}",
    projectId: "{{ env('FIREBASE_PROJECT') }}",
    storageBucket: "{{ env('FIREBASE_STORAGE_BUCKET') }}",
    messagingSenderId: "{{ env('FIREBASE_MESSAGING_SENDER_ID') }}",
    appId: "{{ env('FIREBASE_APP_ID') }}",
    measurementId: "{{ env('FIREBASE_MEASUREMENT_ID') }}"
};

// Initialize Firebase
firebase.initializeApp(firebaseConfig);

// Initialize Firebase Cloud Messaging
const messaging = firebase.messaging();

// Request permission for notifications
messaging.requestPermission()
    .then(() => {
        console.log('Notification permission granted.');
        return messaging.getToken();
    })
    .then((token) => {
        console.log('FCM Token:', token);
        // Store the token in your database or use it as needed
        // You can send this token to your backend to store it
        $.ajax({
            url: '/api/store-fcm-token',
            method: 'POST',
            data: {
                fcm_token: token,
                _token: csrf_token
            },
            success: function(response) {
                console.log('FCM token stored successfully');
            },
            error: function(error) {
                console.error('Error storing FCM token:', error);
            }
        });
    })
    .catch((err) => {
        console.error('Unable to get permission to notify.', err);
    });

// Handle incoming messages
messaging.onMessage((payload) => {
    console.log('Message received. ', payload);
    // Handle the received message
    const notificationTitle = payload.notification.title;
    const notificationOptions = {
        body: payload.notification.body,
        icon: '/assets/images/logo.png'
    };

    new Notification(notificationTitle, notificationOptions);
});
