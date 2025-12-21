# 🔧 Guide de Test et Correction PWA

## ✅ Modifications effectuées

### 1. Manifest.json corrigé
- ✅ Icônes séparées (any + maskable)
- ✅ Start URL simplifiée
- ✅ Theme color orange (#ff6b35)
- ✅ Orientation portrait

### 2. Bouton d'installation flottant ajouté
- Position : En bas à droite (au-dessus de la nav mobile)
- Couleur : Orange avec gradient
- Animation : Pulse subtil
- Responsive : Se réduit en cercle sur petit écran

### 3. Meta tags iOS améliorés
- apple-mobile-web-app-capable
- apple-mobile-web-app-status-bar-style
- apple-mobile-web-app-title
- Multiples tailles d'icônes

### 4. Détection améliorée
- Vérification du prompt beforeinstallprompt
- Bouton visible dès que le prompt est disponible
- Modal avec délai de 3 secondes

---

## 🧪 Comment tester le PWA

### Sur Android (Chrome)

1. **Ouvrir le site sur votre téléphone**
   ```
   http://localhost:8001
   ou
   http://[votre-ip]:8001
   ```

2. **Critères requis pour l'installation**
   - ✅ HTTPS (ou localhost)
   - ✅ Service Worker enregistré
   - ✅ Manifest.json valide
   - ✅ Icônes 192x192 et 512x512

3. **Méthodes d'installation**
   
   **Méthode 1 : Bouton flottant orange**
   - Apparaît en bas à droite après 2-3 secondes
   - Cliquez sur "Installer"
   
   **Méthode 2 : Menu Chrome**
   - Menu (⋮) → "Installer l'application"
   - Ou "Ajouter à l'écran d'accueil"
   
   **Méthode 3 : Modal automatique**
   - Modal apparaît après 3 secondes
   - Cliquer sur "Installer Maintenant"

4. **Vérifier l'installation**
   - Icône JB Shop sur l'écran d'accueil
   - Ouvrir = expérience plein écran (pas de barre Chrome)

### Sur iOS (Safari)

1. **Ouvrir Safari sur iPhone**
   ```
   http://[votre-ip]:8001
   ```

2. **Installation manuelle**
   - Cliquer sur l'icône Partage (carré avec flèche)
   - Défiler et choisir "Sur l'écran d'accueil"
   - Modifier le nom si souhaité
   - Appuyer sur "Ajouter"

3. **Vérifier**
   - Icône JB Shop sur l'écran d'accueil
   - Ouvrir = expérience app native

---

## 🔍 Diagnostic des problèmes

### Le bouton "Installer" n'apparaît pas

**Vérifier dans la console Chrome (F12)**
```javascript
// Ouvrir DevTools > Console
// Chercher ces messages :
[PWA] Service Worker registered successfully
[PWA] Install prompt available
```

**Si pas de prompt disponible :**

1. **Vérifier les critères Chrome**
   ```
   DevTools > Application > Manifest
   ```
   - Vérifier que tout est vert
   - Pas d'erreurs ni de warnings

2. **Vérifier le Service Worker**
   ```
   DevTools > Application > Service Workers
   ```
   - Doit être "activated and running"

3. **Forcer le prompt (Debug)**
   ```javascript
   // Dans la console
   window.addEventListener('beforeinstallprompt', (e) => {
       console.log('PROMPT AVAILABLE!', e);
   });
   ```

4. **Réinitialiser**
   ```
   DevTools > Application > Storage
   Cliquer "Clear site data"
   Recharger la page (Ctrl+Shift+R)
   ```

### Le site n'est pas en HTTPS

**Solution 1 : Tester sur localhost**
```bash
# Depuis votre PC
http://localhost:8001
```

**Solution 2 : Utiliser ngrok (pour mobile)**
```bash
# Installer ngrok
npm install -g ngrok

# Créer un tunnel HTTPS
ngrok http 8001

# Utiliser l'URL HTTPS fournie
https://xxxx-xx-xx-xx-xx.ngrok.io
```

**Solution 3 : Déployer sur serveur HTTPS**
- Hostinger, Vercel, Netlify, etc.

### Service Worker ne s'enregistre pas

1. **Vérifier le chemin**
   ```javascript
   // Doit être à la racine
   /service-worker.js
   ```

2. **Vérifier dans DevTools**
   ```
   Application > Service Workers
   ```

3. **Désinstaller et réinstaller**
   ```javascript
   // Console DevTools
   navigator.serviceWorker.getRegistrations().then(function(registrations) {
       for(let registration of registrations) {
           registration.unregister();
       }
   });
   location.reload();
   ```

### Modal PWA ne s'affiche jamais

1. **Vérifier localStorage**
   ```javascript
   // Console
   localStorage.getItem('pwa_install_modal_shown');
   // Si 'true', réinitialiser :
   localStorage.removeItem('pwa_install_modal_shown');
   localStorage.removeItem('pwa_install_remind_later');
   location.reload();
   ```

2. **Vérifier que c'est mobile**
   ```javascript
   // Console
   /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
   // Doit retourner true
   ```

---

## 📱 Tester depuis votre mobile

### Méthode 1 : IP locale (même réseau WiFi)

1. **Trouver votre IP PC**
   ```bash
   # Linux/Mac
   ip addr show | grep inet
   
   # Windows
   ipconfig
   ```

2. **Ouvrir sur mobile**
   ```
   http://192.168.X.X:8001
   ```

### Méthode 2 : Ngrok (Internet)

```bash
ngrok http 8001

# URL générée :
https://abc123.ngrok.io
```

### Méthode 3 : Déployer temporairement

**Vercel (gratuit)**
```bash
npm i -g vercel
vercel --prod
```

---

## ✅ Checklist de vérification PWA

Avant de dire "ça ne fonctionne pas", vérifier :

- [ ] Le site est en HTTPS (ou localhost)
- [ ] Le fichier `/manifest.json` est accessible
- [ ] Le fichier `/service-worker.js` est accessible
- [ ] Les icônes `/img/logo.png` existent (192x192 et 512x512)
- [ ] Chrome DevTools > Application > Manifest = tout vert
- [ ] Service Worker enregistré et activé
- [ ] Test sur mobile (pas desktop)
- [ ] Pas déjà installé (désinstaller si déjà fait)
- [ ] localStorage vidé si besoin

---

## 🎯 Commandes utiles

### Vérifier les fichiers PWA
```bash
cd /home/fobs/Desktop/Projects/jbEcommerce/public

# Vérifier manifest
cat manifest.json | grep "name\|start_url\|display"

# Vérifier service worker
head -20 service-worker.js

# Vérifier icônes
ls -lh img/logo.png
```

### Debug dans le navigateur
```javascript
// Console Chrome DevTools

// 1. Vérifier Service Worker
navigator.serviceWorker.getRegistrations().then(r => console.log(r));

// 2. Vérifier si déjà installé
console.log(window.matchMedia('(display-mode: standalone)').matches);

// 3. Vérifier localStorage
console.log(localStorage.getItem('pwa_install_modal_shown'));

// 4. Forcer réinitialisation
localStorage.clear();
location.reload();
```

---

## 🚀 Test final

1. ✅ Ouvrir Chrome sur mobile
2. ✅ Aller sur http://[votre-ip]:8001
3. ✅ Attendre 3-5 secondes
4. ✅ Voir le bouton orange "Installer" en bas à droite
5. ✅ OU voir le modal "Installer JB Shop"
6. ✅ Cliquer et installer
7. ✅ Vérifier l'icône sur l'écran d'accueil
8. ✅ Ouvrir et vérifier le mode standalone

**Si tout échoue :** Envoyer une capture d'écran de :
- Chrome DevTools > Console (avec les logs PWA)
- Chrome DevTools > Application > Manifest
- Chrome DevTools > Application > Service Workers

---

**Créé le 21 décembre 2025**
