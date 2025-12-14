/**
 * Mobile App (React Native) FCM Integration Guide
 * 
 * This file contains the complete implementation for integrating
 * Firebase Cloud Messaging in your React Native WebView app
 */

// ============================================
// STEP 1: Install Required Packages
// ============================================
/*
npm install @react-native-firebase/app @react-native-firebase/messaging react-native-webview
# or
yarn add @react-native-firebase/app @react-native-firebase/messaging react-native-webview
*/

// ============================================
// STEP 2: Configure Firebase (Android)
// ============================================
/*
1. Download google-services.json from Firebase Console
2. Place it in: android/app/google-services.json
3. Update android/build.gradle:
   dependencies {
       classpath 'com.google.gms:google-services:4.3.15'
   }
4. Update android/app/build.gradle:
   apply plugin: 'com.google.gms.google-services'
*/

// ============================================
// STEP 3: Configure Firebase (iOS)
// ============================================
/*
1. Download GoogleService-Info.plist from Firebase Console
2. Add to Xcode project
3. Run: cd ios && pod install
4. Add push notification capability in Xcode
*/

// ============================================
// STEP 4: Request Permissions
// ============================================

import messaging from '@react-native-firebase/messaging';
import { PermissionsAndroid, Platform } from 'react-native';

async function requestUserPermission() {
  if (Platform.OS === 'ios') {
    const authStatus = await messaging().requestPermission();
    const enabled =
      authStatus === messaging.AuthorizationStatus.AUTHORIZED ||
      authStatus === messaging.AuthorizationStatus.PROVISIONAL;

    if (enabled) {
      console.log('iOS: Authorization status:', authStatus);
      return true;
    }
    return false;
  }

  if (Platform.OS === 'android') {
    if (Platform.Version >= 33) {
      const granted = await PermissionsAndroid.request(
        PermissionsAndroid.PERMISSIONS.POST_NOTIFICATIONS
      );
      return granted === PermissionsAndroid.RESULTS.GRANTED;
    }
    return true; // Auto-granted on Android < 13
  }

  return false;
}

// ============================================
// STEP 5: Get FCM Token
// ============================================

async function getFCMToken() {
  try {
    await messaging().registerDeviceForRemoteMessages();
    const token = await messaging().getToken();
    console.log('FCM Token:', token);
    return token;
  } catch (error) {
    console.error('Error getting FCM token:', error);
    return null;
  }
}

// ============================================
// STEP 6: Send Token to WebView
// ============================================

// In your App.js or main component
import React, { useEffect, useRef, useState } from 'react';
import { WebView } from 'react-native-webview';
import messaging from '@react-native-firebase/messaging';

export default function App() {
  const webViewRef = useRef(null);
  const [fcmToken, setFcmToken] = useState(null);

  useEffect(() => {
    initializeFCM();
    setupNotificationListeners();
  }, []);

  const initializeFCM = async () => {
    // Request permission
    const hasPermission = await requestUserPermission();
    if (!hasPermission) {
      console.log('Notification permission denied');
      return;
    }

    // Get token
    const token = await getFCMToken();
    if (token) {
      setFcmToken(token);
      sendTokenToWebView(token);
    }

    // Listen for token refresh
    const unsubscribe = messaging().onTokenRefresh(async (newToken) => {
      console.log('FCM Token refreshed:', newToken);
      setFcmToken(newToken);
      sendTokenToWebView(newToken);
    });

    return unsubscribe;
  };

  const sendTokenToWebView = (token) => {
    if (webViewRef.current) {
      const message = JSON.stringify({
        type: 'FCM_TOKEN',
        token: token,
      });
      webViewRef.current.postMessage(message);
      console.log('Sent token to WebView');
    }
  };

  const setupNotificationListeners = () => {
    // Foreground notifications
    const unsubscribeForeground = messaging().onMessage(async (remoteMessage) => {
      console.log('Foreground notification:', remoteMessage);
      // Handle notification (show alert, update UI, etc.)
      sendNotificationToWebView(remoteMessage);
    });

    // Background/Quit state notifications
    messaging().onNotificationOpenedApp((remoteMessage) => {
      console.log('Notification opened app from background:', remoteMessage);
      handleNotificationClick(remoteMessage);
    });

    // App opened from quit state
    messaging()
      .getInitialNotification()
      .then((remoteMessage) => {
        if (remoteMessage) {
          console.log('Notification opened app from quit state:', remoteMessage);
          handleNotificationClick(remoteMessage);
        }
      });

    return unsubscribeForeground;
  };

  const sendNotificationToWebView = (notification) => {
    if (webViewRef.current) {
      const message = JSON.stringify({
        type: 'FCM_NOTIFICATION',
        notification: notification,
      });
      webViewRef.current.postMessage(message);
    }
  };

  const handleNotificationClick = (notification) => {
    const data = notification.data || {};
    let url = '';

    // Navigate based on notification type
    if (data.type === 'order_placed' || data.type === 'order_status_changed') {
      url = `/orders/${data.order_id}`;
    } else if (data.type === 'promotion') {
      url = '/shop';
    } else {
      url = '/orders';
    }

    // Send navigation command to WebView
    if (webViewRef.current) {
      const message = JSON.stringify({
        type: 'NAVIGATE',
        url: url,
      });
      webViewRef.current.postMessage(message);
    }
  };

  const handleWebViewMessage = (event) => {
    try {
      const message = JSON.parse(event.nativeEvent.data);
      console.log('Message from WebView:', message);

      // Handle different message types
      if (message.type === 'REQUEST_TOKEN') {
        if (fcmToken) {
          sendTokenToWebView(fcmToken);
        }
      }
    } catch (error) {
      console.error('Error parsing WebView message:', error);
    }
  };

  return (
    <WebView
      ref={webViewRef}
      source={{ uri: 'https://your-domain.com' }}
      onMessage={handleWebViewMessage}
      javaScriptEnabled={true}
      domStorageEnabled={true}
      sharedCookiesEnabled={true}
      thirdPartyCookiesEnabled={true}
      onLoadEnd={() => {
        // Send token once WebView is loaded
        if (fcmToken) {
          sendTokenToWebView(fcmToken);
        }
      }}
    />
  );
}

// ============================================
// STEP 7: Background Handler (index.js)
// ============================================

// Add this to your index.js (before AppRegistry.registerComponent)
/*
import messaging from '@react-native-firebase/messaging';

messaging().setBackgroundMessageHandler(async (remoteMessage) => {
  console.log('Message handled in the background!', remoteMessage);
  // You can process the notification here if needed
});
*/

// ============================================
// STEP 8: Android Manifest Updates
// ============================================

/*
Add to android/app/src/main/AndroidManifest.xml:

<manifest>
  <uses-permission android:name="android.permission.INTERNET" />
  <uses-permission android:name="android.permission.POST_NOTIFICATIONS" />
  
  <application>
    <meta-data
      android:name="com.google.firebase.messaging.default_notification_channel_id"
      android:value="jb_shop_notifications" />
      
    <service
      android:name="com.google.firebase.messaging.FirebaseMessagingService"
      android:exported="false">
      <intent-filter>
        <action android:name="com.google.firebase.MESSAGING_EVENT" />
      </intent-filter>
    </service>
  </application>
</manifest>
*/

// ============================================
// STEP 9: iOS Capabilities
// ============================================

/*
1. Open Xcode
2. Select your project
3. Go to "Signing & Capabilities"
4. Add "Push Notifications" capability
5. Add "Background Modes" capability
6. Check "Remote notifications"
*/

// ============================================
// STEP 10: Testing
// ============================================

/*
1. Build and run the app
2. Check console for FCM token
3. Token should be sent to WebView automatically
4. WebView will register token with Laravel backend
5. Test notifications from /test-notif page
6. Verify notifications appear on mobile device
*/

// ============================================
// STEP 11: Troubleshooting
// ============================================

/*
Common Issues:

1. Token not generated:
   - Check Firebase configuration files are correct
   - Verify google-services.json/GoogleService-Info.plist
   - Check internet connectivity
   - Verify app has notification permissions

2. Notifications not received:
   - Verify token is registered in database
   - Check Firebase Console for errors
   - Test with Firebase Console "Cloud Messaging" tool
   - Verify background handler is registered

3. WebView communication issues:
   - Check postMessage is called after WebView loads
   - Verify javaScriptEnabled={true}
   - Check message format is valid JSON
   - Look for console errors in WebView

4. iOS notifications not working:
   - Verify APNs certificate in Firebase Console
   - Check Push Notifications capability is enabled
   - Verify provisioning profile includes push notifications
   - Test on physical device (simulator doesn't support push)

5. Android notifications not working:
   - Verify google-services.json is in android/app/
   - Check google-services plugin is applied
   - Verify notification permission is granted (Android 13+)
   - Check Firebase Console for valid server key
*/

export { requestUserPermission, getFCMToken };
