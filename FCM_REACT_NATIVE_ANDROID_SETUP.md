# 📱 Configuration FCM pour React Native Android - JB Shop WebView

## 🎯 Objectif
Implémenter les notifications push Firebase Cloud Messaging dans l'application React Native qui utilise WebView pour afficher JB Shop (Laravel).

## 📋 Prérequis
- ✅ Fichier `google-services.json` déjà placé dans `android/app/`
- ✅ Projet Firebase "glow-and-chic" configuré
- ✅ Backend Laravel avec endpoints FCM fonctionnels (`/fcm/token`)
- ✅ Node.js et npm installés
- ✅ React Native CLI configuré

---

## 🚀 ÉTAPE 1: Installation des Packages

```bash
# Installer les dépendances Firebase et WebView
npm install @react-native-firebase/app
npm install @react-native-firebase/messaging
npm install react-native-webview

# Synchroniser les dépendances natives
cd android && ./gradlew clean && cd ..
```

---

## 🔧 ÉTAPE 2: Configuration Android

### 2.1 - Modifier `android/build.gradle`

Ouvrir le fichier et ajouter la dépendance Google Services:

```gradle
// android/build.gradle
buildscript {
    ext {
        buildToolsVersion = "33.0.0"
        minSdkVersion = 21
        compileSdkVersion = 33
        targetSdkVersion = 33
    }
    repositories {
        google()
        mavenCentral()
    }
    dependencies {
        classpath("com.android.tools.build:gradle")
        classpath("com.facebook.react:react-native-gradle-plugin")
        
        // ✅ AJOUTER CETTE LIGNE
        classpath 'com.google.gms:google-services:4.3.15'
    }
}
```

### 2.2 - Modifier `android/app/build.gradle`

```gradle
// android/app/build.gradle
apply plugin: "com.android.application"
apply plugin: "com.facebook.react"

android {
    namespace "com.jbshop" // ⚠️ Remplacer par votre package
    compileSdkVersion rootProject.ext.compileSdkVersion
    
    defaultConfig {
        applicationId "com.jbshop" // ⚠️ Remplacer par votre package
        minSdkVersion rootProject.ext.minSdkVersion
        targetSdkVersion rootProject.ext.targetSdkVersion
        versionCode 1
        versionName "1.0"
    }
}

dependencies {
    implementation("com.facebook.react:react-android")
    
    // ✅ AJOUTER CES LIGNES SI ABSENTES
    implementation 'com.google.firebase:firebase-messaging:23.1.2'
    implementation 'com.google.firebase:firebase-analytics'
}

// ✅ AJOUTER CETTE LIGNE À LA TOUTE FIN DU FICHIER
apply plugin: 'com.google.gms.google-services'
```

### 2.3 - Modifier `android/app/src/main/AndroidManifest.xml`

```xml
<manifest xmlns:android="http://schemas.android.com/apk/res/android"
    package="com.jbshop"> <!-- ⚠️ Remplacer par votre package -->
    
    <!-- ✅ AJOUTER CES PERMISSIONS -->
    <uses-permission android:name="android.permission.INTERNET" />
    <uses-permission android:name="android.permission.POST_NOTIFICATIONS"/>
    <uses-permission android:name="android.permission.VIBRATE"/>
    <uses-permission android:name="android.permission.RECEIVE_BOOT_COMPLETED"/>

    <application
        android:name=".MainApplication"
        android:label="@string/app_name"
        android:icon="@mipmap/ic_launcher"
        android:roundIcon="@mipmap/ic_launcher_round"
        android:allowBackup="false"
        android:theme="@style/AppTheme">
        
        <!-- ✅ AJOUTER CE SERVICE POUR NOTIFICATIONS BACKGROUND -->
        <service
            android:name=".MyFirebaseMessagingService"
            android:exported="false">
            <intent-filter>
                <action android:name="com.google.firebase.MESSAGING_EVENT" />
            </intent-filter>
        </service>

        <!-- ✅ AJOUTER LES METADATA FIREBASE -->
        <meta-data
            android:name="com.google.firebase.messaging.default_notification_icon"
            android:resource="@mipmap/ic_launcher" />
        <meta-data
            android:name="com.google.firebase.messaging.default_notification_color"
            android:resource="@color/notification_color" />
        <meta-data
            android:name="com.google.firebase.messaging.default_notification_channel_id"
            android:value="jbshop_orders" />

        <activity
            android:name=".MainActivity"
            android:label="@string/app_name"
            android:configChanges="keyboard|keyboardHidden|orientation|screenSize|uiMode"
            android:launchMode="singleTask"
            android:windowSoftInputMode="adjustResize"
            android:exported="true">
            <intent-filter>
                <action android:name="android.intent.action.MAIN" />
                <category android:name="android.intent.category.LAUNCHER" />
            </intent-filter>
        </activity>
    </application>
</manifest>
```

### 2.4 - Créer `android/app/src/main/res/values/colors.xml`

Si le fichier n'existe pas, créez-le:

```xml
<?xml version="1.0" encoding="utf-8"?>
<resources>
    <!-- ✅ Couleur orange de JB Shop pour les notifications -->
    <color name="notification_color">#FF7E00</color>
</resources>
```

### 2.5 - Créer le Service Firebase

Créer le fichier: `android/app/src/main/java/com/jbshop/MyFirebaseMessagingService.java`

**⚠️ IMPORTANT: Remplacer `com.jbshop` par le nom de votre package réel!**

```java
package com.jbshop; // ⚠️ REMPLACER PAR VOTRE PACKAGE

import com.google.firebase.messaging.FirebaseMessagingService;
import com.google.firebase.messaging.RemoteMessage;
import android.app.NotificationChannel;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.content.Context;
import android.content.Intent;
import android.media.RingtoneManager;
import android.net.Uri;
import android.os.Build;
import androidx.core.app.NotificationCompat;
import android.util.Log;

public class MyFirebaseMessagingService extends FirebaseMessagingService {
    
    private static final String TAG = "FCMService";
    private static final String CHANNEL_ID = "jbshop_orders";
    
    @Override
    public void onMessageReceived(RemoteMessage remoteMessage) {
        Log.d(TAG, "Message reçu de: " + remoteMessage.getFrom());
        
        // Vérifier si le message contient une notification
        if (remoteMessage.getNotification() != null) {
            Log.d(TAG, "Notification Body: " + remoteMessage.getNotification().getBody());
            sendNotification(
                remoteMessage.getNotification().getTitle(),
                remoteMessage.getNotification().getBody(),
                remoteMessage.getData()
            );
        }
        
        // Vérifier si le message contient des données
        if (remoteMessage.getData().size() > 0) {
            Log.d(TAG, "Message data: " + remoteMessage.getData());
        }
    }
    
    @Override
    public void onNewToken(String token) {
        Log.d(TAG, "Nouveau token FCM: " + token);
        // Le token sera envoyé au serveur via le WebView
    }
    
    private void sendNotification(String title, String messageBody, java.util.Map<String, String> data) {
        Intent intent = new Intent(this, MainActivity.class);
        intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
        
        // Ajouter les données au intent
        for (java.util.Map.Entry<String, String> entry : data.entrySet()) {
            intent.putExtra(entry.getKey(), entry.getValue());
        }
        
        PendingIntent pendingIntent = PendingIntent.getActivity(
            this, 
            0, 
            intent,
            PendingIntent.FLAG_IMMUTABLE
        );
        
        Uri defaultSoundUri = RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION);
        
        NotificationCompat.Builder notificationBuilder =
            new NotificationCompat.Builder(this, CHANNEL_ID)
                .setSmallIcon(R.mipmap.ic_launcher)
                .setContentTitle(title != null ? title : "JB Shop")
                .setContentText(messageBody)
                .setAutoCancel(true)
                .setSound(defaultSoundUri)
                .setContentIntent(pendingIntent)
                .setPriority(NotificationCompat.PRIORITY_HIGH)
                .setVibrate(new long[]{1000, 1000, 1000, 1000, 1000});
        
        NotificationManager notificationManager =
            (NotificationManager) getSystemService(Context.NOTIFICATION_SERVICE);
        
        // Créer le canal de notification pour Android O+
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
            NotificationChannel channel = new NotificationChannel(
                CHANNEL_ID,
                "Commandes JB Shop",
                NotificationManager.IMPORTANCE_HIGH
            );
            channel.setDescription("Notifications pour vos commandes JB Shop");
            channel.enableVibration(true);
            channel.setVibrationPattern(new long[]{1000, 1000, 1000, 1000, 1000});
            notificationManager.createNotificationChannel(channel);
        }
        
        notificationManager.notify(0, notificationBuilder.build());
    }
}
```

---

## 💻 ÉTAPE 3: Code React Native (WebView Component)

### 3.1 - Créer ou Modifier le Composant Principal

Créer `App.js` ou `src/screens/WebViewScreen.js`:

```javascript
import React, { useEffect, useRef, useState } from 'react';
import { View, StyleSheet, Platform, PermissionsAndroid, Alert, BackHandler } from 'react-native';
import { WebView } from 'react-native-webview';
import messaging from '@react-native-firebase/messaging';

const WebViewScreen = () => {
  const webViewRef = useRef(null);
  const [fcmToken, setFcmToken] = useState(null);
  const [canGoBack, setCanGoBack] = useState(false);

  // ===== GESTION DES PERMISSIONS =====
  const requestUserPermission = async () => {
    if (Platform.OS === 'android') {
      // Android 13+ nécessite une permission explicite
      if (Platform.Version >= 33) {
        const granted = await PermissionsAndroid.request(
          PermissionsAndroid.PERMISSIONS.POST_NOTIFICATIONS
        );
        if (granted === PermissionsAndroid.RESULTS.GRANTED) {
          console.log('[FCM] Permission notifications accordée');
          return true;
        } else {
          console.log('[FCM] Permission notifications refusée');
          return false;
        }
      }
      return true; // Versions Android < 13
    }
    
    // iOS
    const authStatus = await messaging().requestPermission();
    const enabled =
      authStatus === messaging.AuthorizationStatus.AUTHORIZED ||
      authStatus === messaging.AuthorizationStatus.PROVISIONAL;
    
    console.log('[FCM] Statut permission iOS:', authStatus);
    return enabled;
  };

  // ===== OBTENIR LE TOKEN FCM =====
  const getFCMToken = async () => {
    try {
      const token = await messaging().getToken();
      console.log('[FCM] Token obtenu:', token);
      setFcmToken(token);
      
      // Envoyer le token au WebView
      if (webViewRef.current && token) {
        const script = `
          if (typeof window.receiveFCMToken === 'function') {
            window.receiveFCMToken('${token}');
            console.log('[WebView] Token FCM envoyé:', '${token}');
          } else {
            console.error('[WebView] Fonction receiveFCMToken non disponible');
          }
          true;
        `;
        webViewRef.current.injectJavaScript(script);
      }
      
      return token;
    } catch (error) {
      console.error('[FCM] Erreur lors de l\'obtention du token:', error);
      return null;
    }
  };

  // ===== CONFIGURATION FCM =====
  useEffect(() => {
    let unsubscribeTokenRefresh;
    let unsubscribeForeground;

    const setupFCM = async () => {
      // Demander les permissions
      const hasPermission = await requestUserPermission();
      
      if (hasPermission) {
        // Obtenir le token initial
        await getFCMToken();
        
        // Écouter les rafraîchissements de token
        unsubscribeTokenRefresh = messaging().onTokenRefresh(async token => {
          console.log('[FCM] Token rafraîchi:', token);
          setFcmToken(token);
          
          // Envoyer le nouveau token au WebView
          if (webViewRef.current) {
            const script = `
              if (typeof window.receiveFCMToken === 'function') {
                window.receiveFCMToken('${token}');
              }
              true;
            `;
            webViewRef.current.injectJavaScript(script);
          }
        });

        // Écouter les notifications en foreground (app ouverte)
        unsubscribeForeground = messaging().onMessage(async remoteMessage => {
          console.log('[FCM] Notification reçue (foreground):', remoteMessage);
          
          // Afficher une alerte simple
          Alert.alert(
            remoteMessage.notification?.title || 'JB Shop',
            remoteMessage.notification?.body || 'Nouvelle notification',
            [{ text: 'OK' }]
          );
        });

        // Gestionnaire pour notifications en background/quit
        messaging().setBackgroundMessageHandler(async remoteMessage => {
          console.log('[FCM] Notification reçue (background):', remoteMessage);
        });

      } else {
        console.log('[FCM] Permission refusée - notifications désactivées');
        Alert.alert(
          'Notifications désactivées',
          'Activez les notifications dans les paramètres pour recevoir les mises à jour de vos commandes.',
          [{ text: 'OK' }]
        );
      }
    };

    setupFCM();

    // Cleanup
    return () => {
      if (unsubscribeTokenRefresh) unsubscribeTokenRefresh();
      if (unsubscribeForeground) unsubscribeForeground();
    };
  }, []);

  // ===== GESTION DU BOUTON RETOUR ANDROID =====
  useEffect(() => {
    const backAction = () => {
      if (canGoBack && webViewRef.current) {
        webViewRef.current.goBack();
        return true;
      }
      return false;
    };

    const backHandler = BackHandler.addEventListener(
      'hardwareBackPress',
      backAction
    );

    return () => backHandler.remove();
  }, [canGoBack]);

  // ===== GÉRER LES MESSAGES DU WEBVIEW =====
  const handleWebViewMessage = (event) => {
    try {
      const data = JSON.parse(event.nativeEvent.data);
      console.log('[WebView] Message reçu:', data);
      
      switch (data.type) {
        case 'REQUEST_FCM_TOKEN':
          // Le WebView demande le token
          if (fcmToken && webViewRef.current) {
            const script = `
              if (typeof window.receiveFCMToken === 'function') {
                window.receiveFCMToken('${fcmToken}');
              }
              true;
            `;
            webViewRef.current.injectJavaScript(script);
          }
          break;
          
        case 'OPEN_URL':
          // Le WebView veut ouvrir une URL
          if (data.url && webViewRef.current) {
            webViewRef.current.injectJavaScript(`
              window.location.href = '${data.url}';
              true;
            `);
          }
          break;
          
        default:
          console.log('[WebView] Type de message inconnu:', data.type);
      }
    } catch (error) {
      console.error('[WebView] Erreur parsing message:', error);
    }
  };

  // ===== CODE JAVASCRIPT À INJECTER DANS LE WEBVIEW =====
  const injectedJavaScript = `
    (function() {
      console.log('[WebView] Script d\\'injection démarré');
      
      // Fonction pour recevoir le token du côté natif
      window.receiveFCMToken = function(token) {
        console.log('[WebView] Token FCM reçu:', token);
        
        // Stocker dans localStorage
        localStorage.setItem('fcm_token', token);
        
        // Envoyer au serveur Laravel
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        
        if (csrfToken) {
          fetch('/fcm/token', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': csrfToken,
              'Accept': 'application/json'
            },
            body: JSON.stringify({ 
              token: token,
              device_type: 'android_app'
            })
          })
          .then(response => response.json())
          .then(data => {
            console.log('[WebView] Token enregistré sur le serveur:', data);
          })
          .catch(error => {
            console.error('[WebView] Erreur enregistrement token:', error);
          });
        } else {
          console.warn('[WebView] CSRF token non trouvé');
        }
      };
      
      // Demander le token au chargement de la page
      setTimeout(() => {
        if (window.ReactNativeWebView) {
          window.ReactNativeWebView.postMessage(JSON.stringify({
            type: 'REQUEST_FCM_TOKEN'
          }));
          console.log('[WebView] Demande de token envoyée');
        } else {
          console.error('[WebView] ReactNativeWebView non disponible');
        }
      }, 2000);
      
      // Indiquer que c'est un WebView
      window.isWebView = true;
      window.isMobileApp = true;
      window.isAndroidApp = true;
      
      console.log('[WebView] Script d\\'injection terminé');
    })();
    true;
  `;

  return (
    <View style={styles.container}>
      <WebView
        ref={webViewRef}
        source={{ uri: 'http://127.0.0.1:8000' }} // ⚠️ REMPLACER PAR VOTRE URL
        injectedJavaScriptBeforeContentLoaded={injectedJavaScript}
        onMessage={handleWebViewMessage}
        onNavigationStateChange={navState => setCanGoBack(navState.canGoBack)}
        javaScriptEnabled={true}
        domStorageEnabled={true}
        startInLoadingState={true}
        scalesPageToFit={true}
        mixedContentMode="always"
        allowsBackForwardNavigationGestures={true}
        cacheEnabled={true}
        onError={(syntheticEvent) => {
          const { nativeEvent } = syntheticEvent;
          console.error('[WebView] Erreur:', nativeEvent);
        }}
        onHttpError={(syntheticEvent) => {
          const { nativeEvent } = syntheticEvent;
          console.error('[WebView] Erreur HTTP:', nativeEvent.statusCode);
        }}
        onLoadStart={() => console.log('[WebView] Chargement démarré')}
        onLoadEnd={() => console.log('[WebView] Chargement terminé')}
      />
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#fff',
  },
});

export default WebViewScreen;
```

---

## 🏗️ ÉTAPE 4: Rebuild et Test

```bash
# Nettoyer le build précédent
cd android && ./gradlew clean && cd ..

# Rebuild l'application
npx react-native run-android

# Dans un autre terminal, voir les logs
adb logcat | grep -E "(FCM|WebView|FirebaseMessaging)"
```

---

## ✅ CHECKLIST DE VÉRIFICATION

Avant de tester, vérifier que:

- [ ] `google-services.json` est dans `android/app/`
- [ ] Plugin Google Services ajouté dans `android/build.gradle`
- [ ] Plugin appliqué EN BAS de `android/app/build.gradle`
- [ ] Permissions ajoutées dans `AndroidManifest.xml`
- [ ] Service `MyFirebaseMessagingService.java` créé avec le BON package name
- [ ] Fichier `colors.xml` créé
- [ ] Package name remplacé partout (⚠️ important!)
- [ ] URL WebView mise à jour vers votre domaine
- [ ] App rebuild complètement
- [ ] Permissions notifications accordées sur le téléphone

---

## 🧪 PROCÉDURE DE TEST

### Test 1: Vérifier le Token FCM

```bash
# Lancer l'app et voir les logs
adb logcat | grep "Token obtenu"

# Vous devriez voir:
# [FCM] Token obtenu: fXXXXXXXXXXXXXXXXXXXXXXX...
```

### Test 2: Vérifier l'Envoi au Serveur

1. Se connecter dans l'app
2. Vérifier dans la base de données Laravel:
```sql
SELECT * FROM users WHERE fcm_token IS NOT NULL;
```

### Test 3: Envoyer une Notification Test

1. Dans l'app, aller sur la page `/test-notif`
2. Remplir le formulaire de test
3. Cliquer sur "Envoyer Notification Test"
4. Fermer l'app (pas juste minimiser)
5. La notification doit apparaître! 🎉

### Test 4: Tester avec une Vraie Commande

1. Passer une commande
2. Admin change le statut de la commande
3. Notification automatique reçue

---

## 🐛 DÉPANNAGE

### Problème: Token non généré

```bash
# Vérifier les logs Firebase
adb logcat | grep -i firebase

# Vérifier google-services.json
cat android/app/google-services.json | grep project_id

# Vérifier que le plugin est appliqué
grep "google-services" android/app/build.gradle
```

**Solution:** Rebuild complet
```bash
cd android && ./gradlew clean && cd ..
rm -rf node_modules
npm install
npx react-native run-android
```

### Problème: Notifications ne s'affichent pas

```bash
# Vérifier les permissions
adb shell dumpsys package com.jbshop | grep POST_NOTIFICATIONS

# Vérifier le canal de notification
adb shell dumpsys notification_listener | grep jbshop
```

**Solution:** Vérifier que:
- Les permissions sont accordées dans les paramètres de l'app
- Le canal de notification est créé
- Le service `MyFirebaseMessagingService` est bien enregistré

### Problème: WebView ne reçoit pas le token

```bash
# Voir les logs du WebView
adb logcat | grep WebView
```

**Solution:** Vérifier que:
- Le script `injectedJavaScript` s'exécute
- La fonction `window.receiveFCMToken` existe
- Le CSRF token est présent dans la page Laravel

### Problème: Crash au démarrage

```bash
# Voir les erreurs
adb logcat *:E

# Voir les erreurs React Native
adb logcat | grep ReactNativeJS
```

**Solution:** Vérifier le package name dans:
- `AndroidManifest.xml` (attribut `package`)
- `MyFirebaseMessagingService.java` (première ligne)
- `android/app/build.gradle` (ligne `applicationId`)

### Problème: "FirebaseApp not initialized"

**Solution:** Rebuild complet et vérifier que `google-services.json` est au bon endroit:
```bash
ls -la android/app/google-services.json
```

---

## 📱 TROUVER VOTRE PACKAGE NAME

Le package name se trouve dans:

1. **AndroidManifest.xml**
```xml
<manifest package="com.votreapp">
```

2. **build.gradle**
```gradle
defaultConfig {
    applicationId "com.votreapp"
}
```

3. **Structure des dossiers**
```
android/app/src/main/java/com/votreapp/
```

⚠️ **REMPLACEZ TOUS LES `com.jbshop` PAR VOTRE PACKAGE!**

---

## 🎯 RÉSULTAT ATTENDU

Quand tout fonctionne correctement:

✅ Token FCM généré au démarrage de l'app  
✅ Token envoyé automatiquement au serveur Laravel  
✅ Token stocké dans la base de données  
✅ Notifications apparaissent quand l'app est ouverte  
✅ Notifications apparaissent quand l'app est fermée  
✅ Cliquer sur notification ouvre l'app  
✅ Changement de statut commande = notification automatique  

---

## 📚 Ressources Utiles

- [React Native Firebase Documentation](https://rnfirebase.io/)
- [Firebase Console](https://console.firebase.google.com/)
- [React Native WebView](https://github.com/react-native-webview/react-native-webview)
- [Android Notifications Guide](https://developer.android.com/develop/ui/views/notifications)

---

## 🔥 NOTES IMPORTANTES

1. **Permissions Android 13+**: Absolument nécessaire de demander `POST_NOTIFICATIONS`
2. **Package Name**: Doit être identique partout (Manifest, build.gradle, Service Java)
3. **Google Services Plugin**: Doit être en DERNIÈRE ligne de `app/build.gradle`
4. **Rebuild Obligatoire**: Après modification de `google-services.json` ou fichiers Android
5. **Debug sur Appareil Réel**: Les notifications ne fonctionnent pas toujours sur émulateur

---

**✨ Bon courage avec votre implémentation! N'hésitez pas à consulter les logs pour débugger.**
