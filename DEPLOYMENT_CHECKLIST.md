# 🚀 FCM Notification System - Production Deployment Checklist

## ✅ Pre-Deployment Checklist

### 1. Firebase Configuration
- [ ] Verify Firebase project is created (glow-and-chic)
- [ ] Confirm Firebase credentials file exists: `storage/app/firebase_credentials.json`
- [ ] Verify Web App is registered in Firebase Console
- [ ] Confirm Server Key is available in Cloud Messaging settings
- [ ] **IMPORTANT**: Generate Web Push Certificate (VAPID key)
  - Go to: Firebase Console → Project Settings → Cloud Messaging → Web Push certificates
  - Click "Generate key pair"
  - Replace placeholder key in `fcm-init.js` (line 68)

### 2. HTTPS Requirement
- [ ] Ensure production site runs on HTTPS (required for service workers)
- [ ] Verify SSL certificate is valid
- [ ] Test service worker registration on HTTPS
- [ ] Confirm manifest.json is accessible

### 3. Database
- [ ] Verify `fcm_token` column exists in `users` table
- [ ] Check column type is TEXT (not VARCHAR) to handle long tokens
- [ ] Confirm User model has `fcm_token` in `$fillable`

### 4. File Permissions
- [ ] `storage/app/firebase_credentials.json` is readable by web server
- [ ] `storage/logs/` is writable
- [ ] `/public/firebase-messaging-sw.js` is accessible
- [ ] `/public/img/logo.svg` exists and is accessible

### 5. Routes
- [ ] `/fcm/token` (POST) - working
- [ ] `/fcm/token` (DELETE) - working
- [ ] `/test-notif` - accessible for testing

### 6. Observer Registration
- [ ] OrderObserver is registered in AppServiceProvider
- [ ] Verify observer methods trigger on order events

---

## 🧪 Testing Checklist

### Desktop Browser Testing
1. **Chrome/Edge:**
   - [ ] Visit site on HTTPS
   - [ ] Login to account
   - [ ] Grant notification permission
   - [ ] Verify FCM token is stored in database
   - [ ] Test notification from `/test-notif`
   - [ ] Verify notification appears
   - [ ] Test notification click action
   - [ ] Close browser and test background notification

2. **Firefox:**
   - [ ] Repeat all Chrome tests
   - [ ] Verify service worker registers correctly

3. **Safari:**
   - [ ] Test on macOS (Safari 16+)
   - [ ] Verify push notifications work

### Mobile Browser Testing
1. **Android Chrome:**
   - [ ] Visit site on mobile browser (HTTPS required)
   - [ ] Login to account
   - [ ] Grant notification permission when prompted
   - [ ] Check FCM token in database
   - [ ] Send test notification
   - [ ] Verify notification appears in system tray
   - [ ] Test with screen locked
   - [ ] Test with app in background
   - [ ] Click notification and verify navigation

2. **Android Firefox:**
   - [ ] Repeat Android Chrome tests

3. **iOS Safari (iOS 16.4+):**
   - [ ] Visit site on iPhone/iPad
   - [ ] Add to Home Screen (required for iOS push)
   - [ ] Grant notification permission
   - [ ] Test notifications

### Hybrid Mobile App Testing
1. **React Native WebView (Android):**
   - [ ] Implement `MOBILE_APP_FCM_INTEGRATION.js` guide
   - [ ] Build and install APK
   - [ ] Check FCM token is generated
   - [ ] Verify token is sent to WebView
   - [ ] Confirm token saved in database
   - [ ] Send test notification
   - [ ] Verify foreground notification
   - [ ] Verify background notification
   - [ ] Test notification click action

2. **React Native WebView (iOS):**
   - [ ] Configure iOS push certificates in Firebase
   - [ ] Build and install on physical device
   - [ ] Repeat Android tests

---

## 🔧 Production Configuration

### Environment Variables (.env)
```env
FIREBASE_PROJECT_ID=glow-and-chic
FIREBASE_CREDENTIALS=storage/app/firebase_credentials.json
APP_URL=https://your-production-domain.com
```

### Update Firebase Config
**Files to update with production values:**

1. `/public/js/fcm-init.js` (lines 5-11):
```javascript
const firebaseConfig = {
    apiKey: "AIzaSyBw_0MnK82NiYCwIphSzFShoMVFDNwfgEI",
    authDomain: "glow-and-chic.firebaseapp.com",
    projectId: "glow-and-chic",
    storageBucket: "glow-and-chic.firebasestorage.app",
    messagingSenderId: "1364631713",
    appId: "1:1364631713:web:f8bd3db73cec67b76b50e0"
};
```

2. **CRITICAL**: Update VAPID key (line 68):
```javascript
vapidKey: 'YOUR_ACTUAL_VAPID_KEY_HERE'
```

3. `/public/firebase-messaging-sw.js` (lines 8-15):
```javascript
const firebaseConfig = {
    apiKey: "AIzaSyBw_0MnK82NiYCwIphSzFShoMVFDNwfgEI",
    authDomain: "glow-and-chic.firebaseapp.com",
    projectId: "glow-and-chic",
    storageBucket: "glow-and-chic.firebasestorage.app",
    messagingSenderId: "1364631713",
    appId: "1:1364631713:web:f8bd3db73cec67b76b50e0",
    measurementId: "G-3B6N2DS03Y"
};
```

### Generate VAPID Key
```bash
# In Firebase Console:
1. Go to Project Settings
2. Click "Cloud Messaging" tab
3. Scroll to "Web Push certificates"
4. Click "Generate key pair"
5. Copy the key
6. Update in fcm-init.js line 68
```

---

## 📱 Mobile App Deployment

### Android
1. **Firebase Setup:**
   - Download `google-services.json` from Firebase Console
   - Place in `android/app/google-services.json`
   - Add Google Services plugin to gradle

2. **Permissions:**
   - Add POST_NOTIFICATIONS permission (Android 13+)
   - Configure AndroidManifest.xml

3. **Build:**
   ```bash
   cd android
   ./gradlew assembleRelease
   ```

4. **Test:**
   - Install APK on physical device
   - Verify notifications work

### iOS
1. **Firebase Setup:**
   - Download `GoogleService-Info.plist`
   - Add to Xcode project

2. **Certificates:**
   - Generate APNs certificate in Apple Developer
   - Upload to Firebase Console

3. **Capabilities:**
   - Enable Push Notifications
   - Enable Background Modes → Remote notifications

4. **Build:**
   - Build with Xcode or
   ```bash
   cd ios
   pod install
   xcodebuild ...
   ```

---

## 🔍 Monitoring & Debugging

### Check Logs
```bash
# Laravel logs
tail -f storage/logs/laravel.log | grep FCM

# Filter for token saves
tail -f storage/logs/laravel.log | grep "FCM token saved"

# Filter for notification sends
tail -f storage/logs/laravel.log | grep "notification sent"
```

### Browser Console
Open DevTools (F12) and check for:
- `[FCM] Firebase initialized successfully`
- `[FCM] Service Worker registered`
- `[FCM] Token received:`
- `[FCM] Token registered on server`

### Database Verification
```sql
-- Check users with FCM tokens
SELECT id, name, email, LEFT(fcm_token, 30) as token_preview
FROM users
WHERE fcm_token IS NOT NULL;

-- Count users with tokens
SELECT COUNT(*) as users_with_tokens
FROM users
WHERE fcm_token IS NOT NULL;
```

### Firebase Console
- Monitor delivery in Cloud Messaging dashboard
- Check for any errors or warnings
- View delivery statistics

---

## 🚨 Troubleshooting Production Issues

### Issue: Notifications not received
**Diagnosis:**
```bash
# Check if token is in database
php artisan tinker
>>> User::find(USER_ID)->fcm_token

# Test Firebase service
>>> app('App\Services\FirebaseService')->sendToDevice($token, 'Test', 'Body', [])
```

**Solutions:**
- Verify HTTPS is enabled
- Check VAPID key is correct
- Confirm service worker is registered
- Verify Firebase credentials are valid

### Issue: Service worker not registering
**Check:**
- HTTPS is enabled (or using localhost)
- `/firebase-messaging-sw.js` is accessible
- No CORS errors in console
- Browser supports service workers

### Issue: Token not saved to database
**Check:**
- CSRF token meta tag exists
- User is authenticated
- `/fcm/token` route is accessible
- Database connection is working

### Issue: Mobile notifications not working
**Android:**
- Verify `google-services.json` is correct
- Check notification permission is granted
- Confirm app has internet permission
- Test with Firebase Console test message

**iOS:**
- Verify APNs certificate uploaded to Firebase
- Confirm provisioning profile includes push
- Test on physical device (not simulator)
- Check push capability is enabled

---

## 📊 Success Metrics

After deployment, monitor:
- [ ] Number of users with FCM tokens registered
- [ ] Notification delivery rate (Firebase Console)
- [ ] Notification click-through rate
- [ ] Error rate in logs
- [ ] User engagement with notifications

---

## ✅ Final Pre-Launch Checklist

- [ ] All tests passing on desktop browsers
- [ ] Mobile browser notifications working
- [ ] VAPID key updated in production
- [ ] HTTPS configured and working
- [ ] Firebase credentials secured
- [ ] Service worker accessible
- [ ] Database has fcm_token column
- [ ] Observer is triggering correctly
- [ ] Logs show successful sends
- [ ] No errors in browser console
- [ ] Mobile app integration tested (if applicable)
- [ ] Documentation updated
- [ ] Team trained on monitoring

---

## 🎯 Post-Deployment

1. **Monitor for 24 hours:**
   - Watch logs for errors
   - Check Firebase delivery stats
   - Gather user feedback

2. **Optimize:**
   - Adjust notification timing
   - Refine message content
   - A/B test notification styles

3. **Scale:**
   - Monitor Firebase quotas
   - Consider batch sending for promotions
   - Implement user preferences

---

## 📞 Support

If issues persist:
1. Check Firebase status: https://status.firebase.google.com/
2. Review Laravel logs: `storage/logs/laravel.log`
3. Check browser console for client-side errors
4. Test with Firebase Console "Send test message"

**System is READY for production deployment! 🚀**
