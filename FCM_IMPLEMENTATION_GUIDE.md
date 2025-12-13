# Firebase Cloud Messaging Integration Guide

## 📱 Complete FCM Setup for JB Shop

### ✅ What's Been Implemented

**Backend (Laravel):**
- ✅ Firebase Admin SDK installed (`kreait/firebase-php`)
- ✅ FCM token storage in users table
- ✅ Firebase service for sending notifications
- ✅ Order notification service with automatic triggers
- ✅ Order observer for status change notifications
- ✅ API endpoints for token registration

**Web App:**
- ✅ Firebase Messaging SDK integration
- ✅ Service worker for background notifications
- ✅ In-app notification banners
- ✅ Token registration on login
- ✅ Permission request handling

**React Native WebView:**
- 📝 Integration instructions below

---

## 🔧 Firebase Console Setup

### Step 1: Get Your Firebase Web Config

1. Go to [Firebase Console](https://console.firebase.google.com/)
2. Select your project: **glow-and-chic**
3. Click the gear icon ⚙️ → **Project Settings**
4. Scroll to "Your apps" section
5. Click "Web app" or add new web app if needed
6. Copy the `firebaseConfig` object

### Step 2: Update Configuration Files

**File: `/public/js/fcm-init.js` (Line 5-11)**
```javascript
const firebaseConfig = {
    apiKey: "AIzaSyXXXXXXXXXXXXXXXXXXXXXXXXXXXXX", // Replace with your key
    authDomain: "glow-and-chic.firebaseapp.com",
    projectId: "glow-and-chic",
    storageBucket: "glow-and-chic.appspot.com",
    messagingSenderId: "123456789012", // Replace with your sender ID
    appId: "1:123456789012:web:xxxxxxxxxxxxx" // Replace with your app ID
};
```

**File: `/public/firebase-messaging-sw.js` (Line 7-13)**
Same configuration as above.

### Step 3: Get VAPID Key

1. In Firebase Console → Project Settings
2. Go to **Cloud Messaging** tab
3. Scroll to "Web configuration"
4. Click "Generate key pair" under "Web Push certificates"
5. Copy the generated VAPID key

**Update in `/public/js/fcm-init.js` (Line 37)**
```javascript
vapidKey: 'BPxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx' // Your VAPID key
```

---

## 📱 React Native WebView Integration

### Complete Example

```javascript
// App.js or your WebView screen
import React, { useEffect, useState } from 'react';
import { View, Platform, PermissionsAndroid } from 'react-native';
import { WebView } from 'react-native-webview';
import messaging from '@react-native-firebase/messaging';
import notifee, { AndroidImportance } from '@notifee/react-native';

const JBShopWebView = () => {
  const [webViewRef, setWebViewRef] = useState(null);
  const [fcmToken, setFcmToken] = useState(null);

  useEffect(() => {
    setupNotifications();
  }, []);

  // Setup FCM notifications
  const setupNotifications = async () => {
    try {
      // Request permission
      const authStatus = await messaging().requestPermission();
      const enabled =
        authStatus === messaging.AuthorizationStatus.AUTHORIZED ||
        authStatus === messaging.AuthorizationStatus.PROVISIONAL;

      if (enabled) {
        console.log('FCM Authorization status:', authStatus);
        await getFCMToken();
        setupNotificationHandlers();
      }
    } catch (error) {
      console.error('FCM Setup error:', error);
    }
  };

  // Get FCM token
  const getFCMToken = async () => {
    try {
      const token = await messaging().getToken();
      console.log('FCM Token:', token);
      setFcmToken(token);

      // Send token to WebView
      if (webViewRef) {
        webViewRef.injectJavaScript(`
          (function() {
            localStorage.setItem('fcm_token_native', '${token}');
            if (typeof window.registerNativeFCMToken === 'function') {
              window.registerNativeFCMToken('${token}');
            }
          })();
        `);
      }
    } catch (error) {
      console.error('Error getting FCM token:', error);
    }
  };

  // Setup notification handlers
  const setupNotificationHandlers = () => {
    // Foreground notifications
    const unsubscribeForeground = messaging().onMessage(async (remoteMessage) => {
      console.log('Foreground notification:', remoteMessage);
      await displayNotification(remoteMessage);
    });

    // Background/Quit state notifications
    messaging().setBackgroundMessageHandler(async (remoteMessage) => {
      console.log('Background notification:', remoteMessage);
      await displayNotification(remoteMessage);
    });

    // Notification opened app
    messaging().onNotificationOpenedApp((remoteMessage) => {
      console.log('Notification opened app:', remoteMessage);
      handleNotificationClick(remoteMessage);
    });

    // Check if app was opened by notification
    messaging()
      .getInitialNotification()
      .then((remoteMessage) => {
        if (remoteMessage) {
          console.log('App opened by notification:', remoteMessage);
          handleNotificationClick(remoteMessage);
        }
      });

    return () => {
      unsubscribeForeground();
    };
  };

  // Display notification using Notifee
  const displayNotification = async (remoteMessage) => {
    try {
      // Create channel for Android
      const channelId = await notifee.createChannel({
        id: 'jbshop-orders',
        name: 'JB Shop Orders',
        importance: AndroidImportance.HIGH,
        sound: 'default',
        vibration: true,
        badge: true,
      });

      // Display notification
      await notifee.displayNotification({
        title: remoteMessage.notification?.title || 'JB Shop',
        body: remoteMessage.notification?.body || 'Nouvelle notification',
        android: {
          channelId,
          importance: AndroidImportance.HIGH,
          pressAction: {
            id: 'default',
            launchActivity: 'default',
          },
          smallIcon: 'ic_notification', // Add this icon to your Android project
          color: '#f28b00',
          sound: 'default',
          vibrationPattern: [200, 100, 200],
        },
        ios: {
          sound: 'default',
          badgeCount: 1,
          categoryId: 'ORDER_UPDATE',
        },
        data: remoteMessage.data,
      });
    } catch (error) {
      console.error('Error displaying notification:', error);
    }
  };

  // Handle notification click
  const handleNotificationClick = (remoteMessage) => {
    const data = remoteMessage.data || {};
    let url = 'https://jbshop237.com/orders';

    if (data.type === 'order_placed' || data.type === 'order_status_changed') {
      url = `https://jbshop237.com/orders/${data.order_id}`;
    } else if (data.type === 'promotion') {
      url = 'https://jbshop237.com/store';
    }

    // Navigate WebView to URL
    if (webViewRef) {
      webViewRef.injectJavaScript(`
        window.location.href = '${url}';
      `);
    }
  };

  // Handle messages from WebView
  const handleWebViewMessage = (event) => {
    try {
      const message = JSON.parse(event.nativeEvent.data);
      
      if (message.type === 'REQUEST_FCM_TOKEN') {
        // WebView requests FCM token
        if (fcmToken && webViewRef) {
          webViewRef.injectJavaScript(`
            (function() {
              if (typeof window.receiveFCMToken === 'function') {
                window.receiveFCMToken('${fcmToken}');
              }
            })();
          `);
        }
      } else if (message.type === 'REGISTER_FCM_TOKEN') {
        // WebView wants to register token on server
        const token = message.token || fcmToken;
        if (token) {
          registerTokenOnServer(token);
        }
      }
    } catch (error) {
      console.error('Error handling WebView message:', error);
    }
  };

  // Register token on Laravel backend
  const registerTokenOnServer = (token) => {
    if (webViewRef) {
      webViewRef.injectJavaScript(`
        (function() {
          fetch('/fcm/token', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ token: '${token}' })
          })
          .then(response => response.json())
          .then(data => console.log('FCM token registered:', data))
          .catch(error => console.error('FCM token registration error:', error));
        })();
      `);
    }
  };

  // Inject JavaScript to integrate with web app
  const injectedJavaScript = `
    (function() {
      // Function to receive FCM token from React Native
      window.receiveFCMToken = function(token) {
        console.log('[WebView] Received FCM token from native:', token);
        localStorage.setItem('fcm_token_native', token);
        
        // Register token on server
        if (document.querySelector('meta[name="user-authenticated"]')?.content === 'true') {
          fetch('/fcm/token', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ token: token })
          })
          .then(response => response.json())
          .then(data => console.log('[WebView] Token registered:', data))
          .catch(error => console.error('[WebView] Token registration error:', error));
        }
      };

      // Request FCM token on page load
      window.addEventListener('load', function() {
        if (window.ReactNativeWebView) {
          setTimeout(() => {
            window.ReactNativeWebView.postMessage(JSON.stringify({
              type: 'REQUEST_FCM_TOKEN'
            }));
          }, 2000);
        }
      });

      // Override browser notification API for native handling
      if (window.Notification) {
        const originalNotification = window.Notification;
        window.Notification = function(title, options) {
          // Send to React Native instead
          if (window.ReactNativeWebView) {
            window.ReactNativeWebView.postMessage(JSON.stringify({
              type: 'SHOW_NOTIFICATION',
              title: title,
              options: options
            }));
          }
        };
        window.Notification.permission = 'granted';
        window.Notification.requestPermission = () => Promise.resolve('granted');
      }

      console.log('[WebView] JB Shop native integration loaded');
    })();
    true;
  `;

  return (
    <View style={{ flex: 1 }}>
      <WebView
        ref={(ref) => setWebViewRef(ref)}
        source={{ uri: 'https://jbshop237.com' }}
        injectedJavaScript={injectedJavaScript}
        onMessage={handleWebViewMessage}
        javaScriptEnabled={true}
        domStorageEnabled={true}
        cacheEnabled={true}
        startInLoadingState={true}
        allowsBackForwardNavigationGestures={true}
      />
    </View>
  );
};

export default JBShopWebView;
```

---

## 📦 Required React Native Packages

```bash
# Install Firebase Messaging
npm install @react-native-firebase/app @react-native-firebase/messaging

# Install Notifee for advanced notifications
npm install @notifee/react-native

# React Native WebView (if not already installed)
npm install react-native-webview
```

---

## ⚙️ Android Configuration

### android/build.gradle
```gradle
buildscript {
    dependencies {
        classpath 'com.google.gms:google-services:4.3.15'
    }
}
```

### android/app/build.gradle
```gradle
apply plugin: 'com.google.gms.google-services'

dependencies {
    implementation platform('com.google.firebase:firebase-bom:32.2.0')
}
```

### android/app/src/main/AndroidManifest.xml
```xml
<manifest>
    <uses-permission android:name="android.permission.INTERNET" />
    <uses-permission android:name="android.permission.POST_NOTIFICATIONS" />
    <uses-permission android:name="android.permission.VIBRATE" />

    <application>
        <!-- Firebase Messaging Service -->
        <service
            android:name="com.google.firebase.messaging.FirebaseMessagingService"
            android:exported="false">
            <intent-filter>
                <action android:name="com.google.firebase.MESSAGING_EVENT" />
            </intent-filter>
        </service>

        <!-- Notification Icon (optional) -->
        <meta-data
            android:name="com.google.firebase.messaging.default_notification_icon"
            android:resource="@mipmap/ic_notification" />

        <!-- Notification Color -->
        <meta-data
            android:name="com.google.firebase.messaging.default_notification_color"
            android:resource="@color/jbshop_orange" />
    </application>
</manifest>
```

### Download google-services.json
1. Go to Firebase Console → Project Settings
2. Under "Your apps", click Android app
3. Download `google-services.json`
4. Place in `android/app/google-services.json`

---

## 🍎 iOS Configuration

### ios/Podfile
```ruby
pod 'Firebase/Messaging'
pod 'RNFBMessaging', :path => '../node_modules/@react-native-firebase/messaging'
```

Then run:
```bash
cd ios && pod install && cd ..
```

### Download GoogleService-Info.plist
1. Go to Firebase Console → Project Settings
2. Under "Your apps", click iOS app
3. Download `GoogleService-Info.plist`
4. Add to Xcode project

### Enable Push Notifications in Xcode
1. Open Xcode
2. Select your project → Signing & Capabilities
3. Click "+ Capability"
4. Add "Push Notifications"
5. Add "Background Modes" → Check "Remote notifications"

---

## 🧪 Testing Notifications

### Test from Firebase Console
1. Go to Firebase Console → Cloud Messaging
2. Click "Send your first message"
3. Enter notification details
4. Select your app
5. Send test message

### Test from Laravel
```php
// In tinker or controller
use App\Models\User;
use App\Services\OrderNotificationService;

$user = User::find(1);
$notificationService = app(OrderNotificationService::class);
$notificationService->notifyPromotion(
    $user,
    '🎉 Test Notification',
    'This is a test notification from JB Shop!',
    ['type' => 'test']
);
```

---

## 📊 Notification Types

### 1. Order Placed
```json
{
  "type": "order_placed",
  "order_id": "123",
  "order_status": "pending",
  "total": "50000",
  "title": "🎉 Commande Confirmée!",
  "body": "Votre commande #123 a été enregistrée avec succès."
}
```

### 2. Order Status Changed
```json
{
  "type": "order_status_changed",
  "order_id": "123",
  "old_status": "pending",
  "new_status": "processing",
  "title": "🔄 Commande en Traitement",
  "body": "Votre commande #123 est en cours de préparation."
}
```

### 3. Order Delivered
```json
{
  "type": "order_delivered",
  "order_id": "123",
  "title": "✅ Livraison Confirmée",
  "body": "Votre commande #123 a été livrée."
}
```

### 4. Promotion
```json
{
  "type": "promotion",
  "title": "🎁 Offre Spéciale!",
  "body": "Profitez de 20% de réduction sur tous les smartphones!"
}
```

---

## 🔐 Security Considerations

1. **Token Storage**: FCM tokens are stored encrypted in database
2. **Token Validation**: Tokens are validated before sending notifications
3. **Rate Limiting**: Implement rate limiting on notification endpoints
4. **User Consent**: Always ask for permission before sending notifications
5. **Token Refresh**: Tokens are automatically refreshed on changes

---

## 📱 WebView Communication Flow

```
User Login → WebView loads → JavaScript requests FCM token
     ↓
React Native receives request → Gets FCM token from Firebase
     ↓
Token sent to WebView → WebView registers token with Laravel backend
     ↓
Laravel stores token in users table → Ready to receive notifications
     ↓
Order status changes → Observer triggers → Notification sent
     ↓
Firebase delivers to device → React Native displays notification
     ↓
User taps notification → WebView navigates to order details
```

---

## 🐛 Troubleshooting

### Notifications not received
1. Check Firebase service worker is registered
2. Verify FCM token is saved in database
3. Check browser/app has notification permission
4. Verify Firebase credentials are correct
5. Check Laravel logs for FCM errors

### Token registration fails
1. Ensure CSRF token is present
2. Check user is authenticated
3. Verify `/fcm/token` route exists
4. Check network requests in DevTools

### React Native specific issues
1. Verify `google-services.json` is in correct location
2. Check Android/iOS permissions are granted
3. Rebuild app after adding Firebase dependencies
4. Check React Native Firebase is properly linked

---

## 📈 Monitoring & Analytics

Track notification performance:
- Delivery rate
- Open rate
- Click-through rate
- Token refresh rate
- Error rate

Use Firebase Analytics and Laravel logs for monitoring.

---

**Version:** 1.0.0  
**Last Updated:** December 13, 2025  
**Status:** ✅ Production Ready
