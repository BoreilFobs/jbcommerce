<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-authenticated" content="{{ Auth::check() ? 'true' : 'false' }}">
    <title>FCM Debug Test</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            background: #1e1e1e;
            color: #d4d4d4;
            padding: 20px;
            margin: 0;
        }
        .console {
            background: #252526;
            border: 1px solid #3c3c3c;
            border-radius: 5px;
            padding: 15px;
            margin: 10px 0;
            max-height: 500px;
            overflow-y: auto;
        }
        .log {
            padding: 5px;
            margin: 2px 0;
            border-left: 3px solid #4EC9B0;
        }
        .error {
            border-left-color: #f48771;
            color: #f48771;
        }
        .warn {
            border-left-color: #ffc66d;
            color: #ffc66d;
        }
        .success {
            border-left-color: #6A9955;
            color: #6A9955;
        }
        h1 {
            color: #4EC9B0;
        }
        button {
            background: #0e639c;
            color: white;
            border: none;
            padding: 10px 20px;
            margin: 5px;
            cursor: pointer;
            border-radius: 3px;
        }
        button:hover {
            background: #1177bb;
        }
        .status {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            margin: 0 5px;
        }
        .status.ok {
            background: #16825d;
        }
        .status.fail {
            background: #be1100;
        }
    </style>
</head>
<body>
    <h1>🔥 Firebase Cloud Messaging - Debug Console</h1>
    
    <div>
        <button onclick="checkConfig()">Check Configuration</button>
        <button onclick="testPermission()">Test Permission</button>
        <button onclick="testToken()">Test Token</button>
        <button onclick="clearConsole()">Clear Console</button>
        <button onclick="location.reload()">Reload Page</button>
    </div>

    <h3>Status Dashboard</h3>
    <div class="console">
        <div id="status-dashboard"></div>
    </div>

    <h3>Console Output</h3>
    <div class="console" id="debug-console"></div>

    <script>
        // Override console methods to capture output
        const originalLog = console.log;
        const originalError = console.error;
        const originalWarn = console.warn;

        function addLog(message, type = 'log') {
            const debugConsole = document.getElementById('debug-console');
            const logEntry = document.createElement('div');
            logEntry.className = `log ${type}`;
            logEntry.textContent = `[${new Date().toLocaleTimeString()}] ${message}`;
            debugConsole.appendChild(logEntry);
            debugConsole.scrollTop = debugConsole.scrollHeight;
        }

        console.log = function(...args) {
            originalLog.apply(console, args);
            addLog(args.join(' '), 'log');
        };

        console.error = function(...args) {
            originalError.apply(console, args);
            addLog(args.join(' '), 'error');
        };

        console.warn = function(...args) {
            originalWarn.apply(console, args);
            addLog(args.join(' '), 'warn');
        };

        function updateStatus() {
            const statusDashboard = document.getElementById('status-dashboard');
            const checks = [];

            // Check Firebase
            checks.push({
                name: 'Firebase Library',
                status: typeof firebase !== 'undefined' ? 'ok' : 'fail',
                details: typeof firebase !== 'undefined' ? 'Loaded' : 'Not loaded'
            });

            // Check Messaging Support
            checks.push({
                name: 'Messaging Support',
                status: (typeof firebase !== 'undefined' && firebase.messaging && firebase.messaging.isSupported()) ? 'ok' : 'fail',
                details: (typeof firebase !== 'undefined' && firebase.messaging && firebase.messaging.isSupported()) ? 'Supported' : 'Not supported'
            });

            // Check Notification API
            checks.push({
                name: 'Notification API',
                status: 'Notification' in window ? 'ok' : 'fail',
                details: 'Notification' in window ? `Permission: ${Notification.permission}` : 'Not available'
            });

            // Check Authentication
            const isAuth = document.querySelector('meta[name="user-authenticated"]')?.content === 'true';
            checks.push({
                name: 'User Authentication',
                status: isAuth ? 'ok' : 'fail',
                details: isAuth ? 'Authenticated' : 'Not authenticated'
            });

            // Check CSRF Token
            const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
            checks.push({
                name: 'CSRF Token',
                status: csrf ? 'ok' : 'fail',
                details: csrf ? 'Present' : 'Missing'
            });

            // Check Service Worker
            checks.push({
                name: 'Service Worker',
                status: 'serviceWorker' in navigator ? 'ok' : 'fail',
                details: 'serviceWorker' in navigator ? 'Supported' : 'Not supported'
            });

            // Check Local Storage Token
            const localToken = localStorage.getItem('fcm_token') || localStorage.getItem('fcm_token_native');
            checks.push({
                name: 'Local Storage Token',
                status: localToken ? 'ok' : 'fail',
                details: localToken ? 'Token stored' : 'No token'
            });

            statusDashboard.innerHTML = checks.map(check => `
                <div class="log">
                    <span class="status ${check.status}">${check.status.toUpperCase()}</span>
                    <strong>${check.name}:</strong> ${check.details}
                </div>
            `).join('');
        }

        function checkConfig() {
            console.log('=== Checking Firebase Configuration ===');
            
            if (typeof firebase === 'undefined') {
                console.error('Firebase library is not loaded!');
                console.error('Make sure the Firebase scripts are loaded before fcm-init.js');
                return;
            }

            console.log('Firebase version:', firebase.SDK_VERSION || 'Unknown');
            
            try {
                const app = firebase.apps[0];
                if (app) {
                    console.log('Firebase initialized successfully');
                    const config = app.options;
                    console.log('Project ID:', config.projectId);
                    console.log('API Key:', config.apiKey ? config.apiKey.substring(0, 20) + '...' : 'Not set');
                    console.log('Messaging Sender ID:', config.messagingSenderId);
                    console.log('App ID:', config.appId);
                } else {
                    console.error('Firebase app not initialized');
                }
            } catch (e) {
                console.error('Error checking Firebase config:', e.message);
            }
        }

        function testPermission() {
            console.log('=== Testing Notification Permission ===');
            
            if (!('Notification' in window)) {
                console.error('Notification API not available in this browser');
                return;
            }

            console.log('Current permission:', Notification.permission);

            if (Notification.permission === 'default') {
                console.log('Requesting permission...');
                Notification.requestPermission().then(permission => {
                    console.log('Permission result:', permission);
                    updateStatus();
                    if (permission === 'granted') {
                        console.log('✓ Permission granted! You can now receive notifications');
                    }
                });
            } else if (Notification.permission === 'granted') {
                console.log('✓ Permission already granted');
                // Test notification
                try {
                    new Notification('Test Notification', {
                        body: 'This is a test notification from FCM Debug',
                        icon: '/img/logo.png'
                    });
                    console.log('✓ Test notification displayed');
                } catch (e) {
                    console.error('Error showing test notification:', e.message);
                }
            } else {
                console.error('✗ Permission denied. Please reset in browser settings.');
            }
        }

        function testToken() {
            console.log('=== Testing FCM Token Generation ===');
            
            if (typeof firebase === 'undefined' || !firebase.messaging) {
                console.error('Firebase Messaging not available');
                return;
            }

            try {
                const messaging = firebase.messaging();
                console.log('Messaging instance created');
                
                messaging.getToken({
                    vapidKey: 'BFgL0kPWLG1VlQYRkzLLGYD-Xx4e-E-Aui8xvWBZz7W3L4f8Gm5t3NXMkMF-6OzQF8-kZF0vBjUxQvMfHwJXLXY'
                }).then(token => {
                    if (token) {
                        console.log('✓ Token generated successfully');
                        console.log('Token (first 50 chars):', token.substring(0, 50) + '...');
                        console.log('Token length:', token.length);
                        localStorage.setItem('fcm_token', token);
                        updateStatus();
                    } else {
                        console.warn('No token generated. Check permission status.');
                    }
                }).catch(err => {
                    console.error('Error getting token:', err.code, err.message);
                    if (err.code === 'messaging/permission-blocked') {
                        console.error('Permission is blocked. Reset in browser settings.');
                    }
                });
            } catch (e) {
                console.error('Error testing token:', e.message);
            }
        }

        function clearConsole() {
            document.getElementById('debug-console').innerHTML = '';
            console.log('Console cleared');
        }

        // Initialize
        console.log('=== FCM Debug Console Initialized ===');
        console.log('User Agent:', navigator.userAgent);
        console.log('Page URL:', window.location.href);
        updateStatus();

        // Auto-check after 1 second
        setTimeout(() => {
            console.log('=== Running automatic checks ===');
            checkConfig();
        }, 1000);
    </script>

    <!-- Firebase Scripts -->
    @auth
    <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-messaging-compat.js"></script>
    <script src="{{ asset('js/fcm-init.js') }}"></script>
    @else
    <script>
        console.warn('User is not authenticated. Firebase scripts will not load.');
        console.warn('Please login first: ' + window.location.origin + '/login');
    </script>
    @endauth
</body>
</html>
