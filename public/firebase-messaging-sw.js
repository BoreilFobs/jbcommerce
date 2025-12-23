// Firebase Cloud Messaging Service Worker
// Handles background notifications when app is not in focus

importScripts('https://www.gstatic.com/firebasejs/9.22.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/9.22.0/firebase-messaging-compat.js');

// Firebase Configuration
const firebaseConfig = {
    apiKey: "AIzaSyBw_0MnK82NiYCwIphSzFShoMVFDNwfgEI",
    authDomain: "glow-and-chic.firebaseapp.com",
    projectId: "glow-and-chic",
    storageBucket: "glow-and-chic.firebasestorage.app",
    messagingSenderId: "1364631713",
    appId: "1:1364631713:web:f8bd3db73cec67b76b50e0",
    measurementId: "G-3B6N2DS03Y"
};

// Initialize Firebase
firebase.initializeApp(firebaseConfig);

// Get messaging instance
const messaging = firebase.messaging();

// Handle background messages
messaging.onBackgroundMessage((payload) => {
    console.log('[FCM SW] Received background message:', payload);

    const notificationTitle = payload.notification?.title || 'Nouvelle notification';
    const notificationOptions = {
        body: payload.notification?.body || '',
        icon: '/icons/icon-192x192.png',
        badge: '/icons/icon-96x96.png',
        tag: payload.data?.order_id || 'notification-' + Date.now(),
        data: payload.data || {},
        requireInteraction: true,
        vibrate: [200, 100, 200, 100, 200],
        timestamp: Date.now(),
        renotify: true,
        silent: false,
        actions: [
            {
                action: 'view',
                title: 'Voir',
                icon: '/icons/icon-96x96.png'
            },
            {
                action: 'close',
                title: 'Fermer'
            }
        ]
    };

    // Show notification
    return self.registration.showNotification(notificationTitle, notificationOptions);
});

// Handle notification click
self.addEventListener('notificationclick', (event) => {
    console.log('[FCM SW] Notification clicked:', event);
    
    event.notification.close();

    const data = event.notification.data || {};
    const baseUrl = self.location.origin;
    let urlToOpen = baseUrl + '/orders';

    // Determine URL based on notification type
    if (data.type === 'order_placed' || data.type === 'order_status_changed') {
        urlToOpen = baseUrl + `/orders/${data.order_id}`;
    } else if (data.type === 'promotion') {
        urlToOpen = baseUrl + '/shop';
    } else if (data.click_action && data.click_action !== 'ORDER_DETAILS') {
        urlToOpen = baseUrl + data.click_action;
    }

    console.log('[FCM SW] Opening URL:', urlToOpen);

    // Handle action buttons
    if (event.action === 'close') {
        console.log('[FCM SW] Close action clicked');
        return;
    }

    // Open or focus the app
    event.waitUntil(
        clients.matchAll({ 
            type: 'window', 
            includeUncontrolled: true 
        }).then((clientList) => {
            // Try to focus existing window with same origin
            for (const client of clientList) {
                if (client.url.startsWith(baseUrl) && 'focus' in client) {
                    console.log('[FCM SW] Focusing existing window');
                    return client.focus().then(() => {
                        // Navigate to the target URL
                        return client.navigate(urlToOpen);
                    });
                }
            }
            
            // Open new window if app is not open
            if (clients.openWindow) {
                console.log('[FCM SW] Opening new window');
                return clients.openWindow(urlToOpen);
            }
        }).catch(err => {
            console.error('[FCM SW] Error handling notification click:', err);
        })
    );
});

console.log('[FCM SW] Firebase Messaging Service Worker loaded');
