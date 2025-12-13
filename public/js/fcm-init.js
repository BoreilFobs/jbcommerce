// Firebase Cloud Messaging Configuration
// This handles push notifications for web and WebView

// Firebase Configuration
const firebaseConfig = {
    apiKey: "AIzaSyBw_0MnK82NiYCwIphSzFShoMVFDNwfgEI",
    authDomain: "glow-and-chic.firebaseapp.com",
    projectId: "glow-and-chic",
    storageBucket: "glow-and-chic.firebasestorage.app",
    messagingSenderId: "1364631713",
    appId: "1:1364631713:web:f8bd3db73cec67b76b50e0"
};

(function() {
    'use strict';
    
    // Check if Firebase and messaging are supported
    if (!('firebase' in window) || !firebase.messaging.isSupported()) {
        console.warn('[FCM] Firebase Messaging not supported in this browser');
        return;
    }

    // Initialize Firebase
    try {
        firebase.initializeApp(firebaseConfig);
        const messaging = firebase.messaging();
        
        console.log('[FCM] Firebase initialized successfully');

        // Request permission and get token
        function requestNotificationPermission() {
            return Notification.requestPermission().then(permission => {
                if (permission === 'granted') {
                    console.log('[FCM] Notification permission granted');
                    return getToken();
                } else {
                    console.log('[FCM] Notification permission denied');
                    return null;
                }
            });
        }

        // Get FCM token
        function getToken() {
            return messaging.getToken({
                vapidKey: 'BFa-RUn46d5nfESUlVj__OfNoYCZyeGVo9lLDhTOtRjpVYv8qt9s72oXmX96-qDG8j0gQ1qPj_WRHIy4jblmTpA' // Get from Firebase Console > Project Settings > Cloud Messaging
            }).then(currentToken => {
                if (currentToken) {
                    console.log('[FCM] Token received:', currentToken.substring(0, 20) + '...');
                    sendTokenToServer(currentToken);
                    return currentToken;
                } else {
                    console.warn('[FCM] No token available. Request permission to generate one.');
                    return null;
                }
            }).catch(err => {
                console.error('[FCM] Error getting token:', err);
                return null;
            });
        }

        // Send token to server
        function sendTokenToServer(token) {
            // Check if user is authenticated
            const metaTag = document.querySelector('meta[name="user-authenticated"]');
            const isAuthenticated = metaTag && metaTag.content === 'true';

            if (!isAuthenticated) {
                console.log('[FCM] User not authenticated, skipping token registration');
                return;
            }

            // Get CSRF token
            const csrfMetaTag = document.querySelector('meta[name="csrf-token"]');
            if (!csrfMetaTag) {
                console.error('[FCM] CSRF token meta tag not found');
                return;
            }

            fetch('/fcm/token', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfMetaTag.content
                },
                body: JSON.stringify({ token: token })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('[FCM] Token registered on server');
                    localStorage.setItem('fcm_token', token);
                } else {
                    console.error('[FCM] Failed to register token:', data.message);
                }
            })
            .catch(error => {
                console.error('[FCM] Error sending token to server:', error);
            });
        }

        // Handle incoming messages when app is in foreground
        messaging.onMessage(payload => {
            console.log('[FCM] Message received:', payload);
            
            const notificationTitle = payload.notification.title;
            const notificationOptions = {
                body: payload.notification.body,
                icon: '/img/logo.svg',
                badge: '/img/logo.svg',
                tag: payload.data?.order_id || 'notification',
                data: payload.data,
                requireInteraction: true,
                vibrate: [200, 100, 200]
            };

            // Show browser notification
            if (Notification.permission === 'granted') {
                const notification = new Notification(notificationTitle, notificationOptions);
                
                notification.onclick = function(event) {
                    event.preventDefault();
                    
                    // Handle click based on notification type
                    const data = payload.data || {};
                    
                    if (data.type === 'order_placed' || data.type === 'order_status_changed') {
                        window.location.href = `/orders/${data.order_id}`;
                    } else if (data.type === 'promotion') {
                        window.location.href = '/store';
                    } else {
                        window.location.href = '/orders';
                    }
                    
                    notification.close();
                };
            }

            // Show in-app notification banner
            showInAppNotification(notificationTitle, payload.notification.body, payload.data);
        });

        // Show in-app notification banner
        function showInAppNotification(title, body, data) {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = 'fcm-notification';
            notification.innerHTML = `
                <div class="fcm-notification-content">
                    <div class="fcm-notification-icon">
                        <i class="fas fa-bell"></i>
                    </div>
                    <div class="fcm-notification-text">
                        <strong>${title}</strong>
                        <p>${body}</p>
                    </div>
                    <button class="fcm-notification-close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;

            // Add click handler
            notification.querySelector('.fcm-notification-content').addEventListener('click', function(e) {
                if (!e.target.closest('.fcm-notification-close')) {
                    if (data?.type === 'order_placed' || data?.type === 'order_status_changed') {
                        window.location.href = `/orders/${data.order_id}`;
                    } else if (data?.type === 'promotion') {
                        window.location.href = '/store';
                    } else {
                        window.location.href = '/orders';
                    }
                }
            });

            // Add close handler
            notification.querySelector('.fcm-notification-close').addEventListener('click', function(e) {
                e.stopPropagation();
                notification.style.transform = 'translateX(400px)';
                setTimeout(() => notification.remove(), 300);
            });

            // Add to page
            document.body.appendChild(notification);

            // Show with animation
            setTimeout(() => {
                notification.style.transform = 'translateX(0)';
            }, 100);

            // Auto-hide after 8 seconds
            setTimeout(() => {
                notification.style.transform = 'translateX(400px)';
                setTimeout(() => notification.remove(), 300);
            }, 8000);
        }

        // Initialize on page load
        window.addEventListener('load', function() {
            // Only request permission if user is authenticated
            const metaTag = document.querySelector('meta[name="user-authenticated"]');
            const isAuthenticated = metaTag && metaTag.content === 'true';

            if (isAuthenticated) {
                // Check if we already have permission
                if (Notification.permission === 'granted') {
                    getToken();
                } else if (Notification.permission !== 'denied') {
                    // Show a custom prompt to ask user
                    setTimeout(() => {
                        if (confirm('Voulez-vous recevoir des notifications sur vos commandes?')) {
                            requestNotificationPermission();
                        }
                    }, 2000);
                }
            }
        });

        // Handle token refresh
        messaging.onTokenRefresh(() => {
            getToken();
        });

        // Remove token on logout
        window.addEventListener('beforeunload', function() {
            const logoutForm = document.querySelector('form[action*="logout"]');
            if (logoutForm && logoutForm.classList.contains('submitting')) {
                const token = localStorage.getItem('fcm_token');
                if (token) {
                    navigator.sendBeacon('/fcm/token', JSON.stringify({ 
                        _method: 'DELETE',
                        token: token 
                    }));
                    localStorage.removeItem('fcm_token');
                }
            }
        });

        // Expose FCM utilities globally
        window.FCM = {
            requestPermission: requestNotificationPermission,
            getToken: getToken,
            hasPermission: () => Notification.permission === 'granted'
        };

    } catch (error) {
        console.error('[FCM] Firebase initialization error:', error);
    }
})();

// CSS Styles for in-app notifications
const fcmStyles = document.createElement('style');
fcmStyles.textContent = `
    .fcm-notification {
        position: fixed;
        top: 80px;
        right: 20px;
        z-index: 99999;
        transform: translateX(400px);
        transition: transform 0.3s ease-out;
        max-width: 350px;
    }

    .fcm-notification-content {
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        padding: 16px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        cursor: pointer;
        border-left: 4px solid #f28b00;
    }

    .fcm-notification-icon {
        background: linear-gradient(135deg, #f28b00, #d67700);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
        flex-shrink: 0;
    }

    .fcm-notification-text {
        flex: 1;
    }

    .fcm-notification-text strong {
        display: block;
        color: #333;
        font-size: 15px;
        margin-bottom: 4px;
    }

    .fcm-notification-text p {
        margin: 0;
        color: #666;
        font-size: 13px;
        line-height: 1.4;
    }

    .fcm-notification-close {
        background: none;
        border: none;
        color: #999;
        cursor: pointer;
        padding: 4px;
        font-size: 16px;
        transition: color 0.2s;
    }

    .fcm-notification-close:hover {
        color: #333;
    }

    @media (max-width: 480px) {
        .fcm-notification {
            top: 60px;
            right: 10px;
            left: 10px;
            max-width: none;
        }
    }
`;
document.head.appendChild(fcmStyles);

console.log('[FCM] Notification system loaded');
