// public/firebase-messaging-sw.js
importScripts('https://www.gstatic.com/firebasejs/9.19.1/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/9.19.1/firebase-messaging-compat.js');

// TODO: Replace this configuration with your actual Firebase project Web App configuration.
// You can get these keys from your Firebase Console -> Project Settings -> Web App.
const firebaseConfig = {
    apiKey: "AIzaSyDOmyp_qpqtEPC_fEHff6jSi3FoePq6u_U",
    authDomain: "tuoora-8bc7a.firebaseapp.com",
    projectId: "tuoora-8bc7a",
    storageBucket: "tuoora-8bc7a.firebasestorage.app",
    messagingSenderId: "443775286225",
    appId: "1:443775286225:web:72098e23a0ce2add5cbef0"
};

firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();

// This handler will trigger when the app is in background or closed
messaging.onBackgroundMessage((payload) => {
    console.log('[firebase-messaging-sw.js] Background message received: ', payload);

    // If the payload already contains a notification object, the FCM SDK will
    // automatically display a notification. Calling showNotification here would
    // trigger a second, duplicate notification.
    if (payload.notification) {
        return;
    }

    const notificationTitle = payload.data.title || "New Notification";
    const notificationOptions = {
        body: payload.data.body || payload.data.message || "",
        icon: '/images/turooa.png', // Path to your logo/icon
        badge: '/images/turooa.png', // Small icon shown in Android notification bar
        data: payload.data
    };

    self.registration.showNotification(notificationTitle, notificationOptions);
});

// Force immediate update and activation of the new Service Worker
self.addEventListener('install', (event) => {
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(self.clients.claim());
});
