# Test Notification System - Quick Guide

## 🎯 Overview
A complete test page has been created at `/test-notif` to verify your Firebase Cloud Messaging (FCM) notification system.

## 📍 Access the Test Page
**URL:** `http://your-domain.com/test-notif` (requires login)

## ✨ Features

### 1. System Status Dashboard
The page displays real-time status of:
- ✅ **Authentication Status** - Checks if user is logged in
- ✅ **Notification Permission** - Browser notification authorization
- ✅ **FCM Token** - Client-side token from Firebase
- ✅ **Database Token** - Server-side token storage

### 2. Four Test Notification Types

#### 🟢 Simple Test Notification
- Basic notification to verify the system works
- Title: "✅ Test Notification"
- Tests core Firebase functionality

#### 🔵 Order Notification
- Simulates a new order placement
- Uses your actual last order (if exists) or creates mock data
- Tests order-specific notification flow

#### 🟡 Status Change Notification
- Tests order status updates
- Choose from:
  - ⏳ Pending
  - 📦 Processing
  - 🚚 Shipped
  - ✅ Delivered

#### 🔴 Promotional Notification
- Custom promotional messages
- Editable title and body
- Tests marketing notification capability

## 🚀 How to Use

### Step 1: Access the Page
1. Make sure you're logged in
2. Navigate to `/test-notif`
3. The system will automatically check your notification status

### Step 2: Enable Notifications (First Time)
If notifications aren't enabled:
1. Click "Allow" when browser prompts for permission
2. Page will reload automatically
3. FCM token will be registered

### Step 3: Run Tests
1. Choose any test button (Simple, Order, Status, Promotion)
2. Click the button
3. Button shows loading state during send
4. Success/error message appears at top
5. Notification should appear within 1-2 seconds

### Step 4: Verify
- **Browser Notification:** Should appear in system tray
- **In-App Banner:** Custom notification banner (if page is open)
- **Click Action:** Clicking notification navigates to relevant page

## 🔧 Technical Details

### Routes
```php
GET  /test-notif  → Display test page
POST /test-notif  → Send test notification
```

### Controller: `TestNotificationController`
- **index()** - Display test page
- **send()** - Handle notification sending
- **sendSimpleNotification()** - Basic test
- **sendOrderNotification()** - Order notification
- **sendStatusNotification()** - Status change
- **sendPromotionNotification()** - Promotional message

### View: `test-notification.blade.php`
- Modern Bootstrap 5 design
- Real-time status checks
- AJAX form submissions
- Auto-dismiss alerts
- Responsive layout

## 📊 Status Indicators

| Status | Meaning |
|--------|---------|
| ✓ Green | Working correctly |
| ✗ Red | Not working/missing |
| ? Gray | Checking... |

## ⚠️ Troubleshooting

### No Token Registered?
**Problem:** Database shows no FCM token
**Solution:**
1. Check browser console (F12) for errors
2. Verify Firebase config in `/public/js/fcm-init.js`
3. Ensure service worker is registered
4. Check VAPID key is correct

### Notification Not Appearing?
**Problem:** Button says success but no notification
**Solutions:**
1. Check browser notification settings (should be "Allow")
2. Verify FCM token is valid (check status dashboard)
3. Review Laravel logs: `storage/logs/laravel.log`
4. Test in different browser
5. Ensure HTTPS (required for service workers in production)

### Firebase Config Missing?
**Problem:** Console shows Firebase initialization error
**Solution:**
Update these files with correct values:
- `/public/js/fcm-init.js`
- `/public/firebase-messaging-sw.js`

Required values:
```javascript
apiKey: "YOUR_API_KEY"
authDomain: "glow-and-chic.firebaseapp.com"
projectId: "glow-and-chic"
storageBucket: "glow-and-chic.appspot.com"
messagingSenderId: "YOUR_SENDER_ID"
appId: "YOUR_APP_ID"
vapidKey: "YOUR_VAPID_KEY"
```

### Service Worker Not Working?
**Problem:** Background notifications don't work
**Solutions:**
1. Check service worker registration in browser DevTools
2. Verify `/firebase-messaging-sw.js` has correct Firebase config
3. Clear browser cache and re-register
4. Check HTTPS requirement

## 📝 Logging

All test notifications are logged:
- **Success:** Log includes user ID, type, token
- **Failure:** Log includes error message and stack trace
- **Location:** `storage/logs/laravel.log`

Search logs for:
```bash
grep "Test notification" storage/logs/laravel.log
```

## 🔐 Security

- ✅ Protected by authentication middleware
- ✅ Only logged-in users can access
- ✅ FCM tokens validated before sending
- ✅ Rate limiting recommended (optional)

## 🎨 User Interface

The test page features:
- **Color-coded test cards** - Easy identification
- **Loading indicators** - Visual feedback during send
- **Auto-dismiss alerts** - Success/error messages
- **Responsive design** - Works on all devices
- **Status dashboard** - Real-time system checks
- **User info panel** - Shows current user and token

## 📱 Mobile Testing

To test on mobile:
1. Deploy to HTTPS domain (required)
2. Access `/test-notif` on mobile browser
3. Grant notification permission
4. Run tests as normal
5. Verify notifications appear in mobile notification tray

## 🔄 Next Steps

After successful testing:
1. ✅ Verify all notification types work
2. ✅ Test on multiple browsers (Chrome, Firefox, Safari)
3. ✅ Test on mobile devices
4. ✅ Update Firebase config values (if not done)
5. ✅ Implement React Native WebView (see FCM_IMPLEMENTATION_GUIDE.md)
6. ✅ Monitor logs for any issues
7. ✅ Set up automatic notifications on real orders

## 📚 Related Documentation

- **Full FCM Guide:** `FCM_IMPLEMENTATION_GUIDE.md`
- **Firebase Console:** https://console.firebase.google.com/project/glow-and-chic
- **Laravel Logs:** `storage/logs/laravel.log`

## 🎯 Expected Behavior

### Simple Test
- **Notification:** "✅ Test Notification"
- **Body:** "Ceci est une notification de test..."
- **Click:** Opens `/test-notif`

### Order Test
- **Notification:** "🎉 Nouvelle Commande #[ID]"
- **Body:** "Votre commande de [AMOUNT] DH..."
- **Click:** Opens `/orders`

### Status Test
- **Notification:** "[EMOJI] [STATUS] #[ID]"
- **Body:** Status-specific message
- **Click:** Opens `/orders`

### Promotion Test
- **Notification:** Custom title
- **Body:** Custom message
- **Click:** Opens `/shop`

## ✅ Success Checklist

- [ ] Can access `/test-notif` page
- [ ] All 4 status indicators show green ✓
- [ ] Simple notification works
- [ ] Order notification works
- [ ] Status notification works
- [ ] Promotion notification works
- [ ] Notifications appear in browser
- [ ] Click actions work correctly
- [ ] No errors in console
- [ ] Tokens saved in database

---

**Need Help?** Check the logs at `storage/logs/laravel.log` or review the comprehensive guide in `FCM_IMPLEMENTATION_GUIDE.md`.
