# 📱 WhatsApp Notification System - JB Shop

## Vue d'ensemble

Ce système intègre les notifications WhatsApp automatisées pour JB Shop en utilisant l'API Evolution. Il comprend :

1. **Vérification OTP lors de l'inscription** - Code à 6 chiffres envoyé via WhatsApp
2. **Notifications de commandes** - Confirmation et suivi automatique
3. **Messages de bienvenue** - Accueil personnalisé pour nouveaux utilisateurs

---

## 🔧 Configuration

### Variables d'environnement (.env)

```env
EVOLUTION_API_URL=https://whatsapi.fobs.dev
EVOLUTION_INSTANCE_NAME=Jumeau
EVOLUTION_API_KEY=D95E71B2F84A46DCA30E89B15C72D648
```

### Configuration Laravel (config/services.php)

```php
'evolution' => [
    'api_url' => env('EVOLUTION_API_URL'),
    'instance_name' => env('EVOLUTION_INSTANCE_NAME'),
    'api_key' => env('EVOLUTION_API_KEY'),
],
```

---

## 📊 Architecture

### Services créés

#### 1. WhatsAppService (`app/Services/WhatsAppService.php`)

Service principal pour communiquer avec l'API Evolution.

**Méthodes principales :**

- `sendTextMessage($phoneNumber, $message)` - Envoyer un message texte
- `sendOTP($phoneNumber, $name)` - Générer et envoyer un code OTP
- `verifyOTP($phoneNumber, $otp)` - Vérifier un code OTP
- `sendOrderNotification($order, $user)` - Notification de nouvelle commande
- `sendOrderStatusUpdate($order, $user, $newStatus)` - Mise à jour de statut
- `sendWelcomeMessage($user)` - Message de bienvenue
- `formatPhoneNumber($phoneNumber)` - Formatage international (+237...)
- `checkInstanceStatus()` - Vérifier la connexion API

**Exemple d'utilisation :**

```php
use App\Services\WhatsAppService;

// Injection dans un contrôleur
public function __construct(WhatsAppService $whatsappService)
{
    $this->whatsappService = $whatsappService;
}

// Envoyer un message simple
$result = $this->whatsappService->sendTextMessage(
    '+237657528859',
    'Bonjour ! Votre commande est prête.'
);

// Envoyer un OTP
$result = $this->whatsappService->sendOTP('+237657528859', 'John Doe');
```

#### 2. OrderNotificationService (mis à jour)

Intègre maintenant WhatsApp en plus de Firebase FCM.

**Flux d'exécution :**

```
Nouvelle commande
    ↓
notifyOrderPlaced()
    ├── Notification FCM (si fcm_token existe)
    └── Notification WhatsApp (si phone existe)
```

---

## 🔐 Système OTP

### Flux d'inscription avec OTP

```
1. Utilisateur remplit le formulaire d'inscription
   ↓
2. RegisteredUserController::store()
   - Valide les données
   - Génère un code OTP à 6 chiffres
   - Enregistre l'OTP dans `otp_verifications`
   - Envoie l'OTP via WhatsApp
   - Stocke les données en session
   - Redirige vers /verify-otp
   ↓
3. Page de vérification OTP (resources/views/auth/verify-otp.blade.php)
   - Interface moderne avec 6 champs
   - Timer de 60 secondes
   - Bouton "Renvoyer le code"
   ↓
4. RegisteredUserController::verifyOtp()
   - Vérifie le code OTP
   - Crée le compte utilisateur
   - Envoie le message de bienvenue
   - Connecte l'utilisateur automatiquement
```

### Table `otp_verifications`

| Colonne      | Type      | Description                              |
|--------------|-----------|------------------------------------------|
| id           | bigint    | Clé primaire                             |
| phone        | string    | Numéro de téléphone                      |
| code         | string(6) | Code OTP                                 |
| type         | enum      | registration / password_reset / etc.     |
| expires_at   | timestamp | Date d'expiration (10 minutes)           |
| verified     | boolean   | OTP vérifié ou non                       |
| verified_at  | timestamp | Date de vérification                     |
| attempts     | integer   | Nombre de tentatives (max 5)             |

### Sécurité OTP

- ✅ Expiration après 10 minutes
- ✅ Maximum 5 tentatives
- ✅ Invalidation après vérification
- ✅ Stockage sécurisé en base de données
- ✅ Codes à 6 chiffres numériques uniquement

---

## 📬 Messages WhatsApp

### 1. Message OTP

```
🔐 JB Shop - Code de Vérification

Bonjour {Nom},

Votre code de vérification est :

*123456*

⏱️ Ce code est valide pendant 10 minutes.

⚠️ Ne partagez jamais ce code avec qui que ce soit.

Si vous n'avez pas demandé ce code, ignorez ce message.

Merci,
L'équipe JB Shop 🛍️
```

### 2. Notification de commande

```
🎉 Commande Confirmée - JB Shop

Bonjour {Nom},

Votre commande a été confirmée avec succès !

📦 Détails de la Commande
━━━━━━━━━━━━━━━━━━━━
🔖 Numéro : *ORD-12345*
📅 Date : 21/12/2025 à 18:06
💰 Montant : *50 000 FCFA*
📍 Adresse : Douala, Cameroun

📋 Articles Commandés
━━━━━━━━━━━━━━━━━━━━
1. Produit A
   × 2 - 20 000 FCFA
2. Produit B
   × 1 - 30 000 FCFA

🚚 Livraison
━━━━━━━━━━━━━━━━━━━━
📞 Contact : +237-657-528-859
⏱️ Délai estimé : 2-5 jours ouvrables

📱 Suivez votre commande :
https://jb-shop.com/orders/123

Merci pour votre confiance ! 🙏
L'équipe JB Shop
```

### 3. Mise à jour de statut

**Pour statut "Expédiée" :**

```
🚚 Mise à Jour Commande - JB Shop

Bonjour {Nom},

Le statut de votre commande a été mis à jour :

🔖 Numéro : *ORD-12345*
📊 Nouveau statut : *Expédiée*

📦 Numéro de suivi : *TRACK123456*

Votre commande est en route ! 🚚
Vous devriez la recevoir dans 2-3 jours.

📱 Voir les détails :
https://jb-shop.com/orders/123

Merci,
L'équipe JB Shop 🛍️
```

### 4. Message de bienvenue

```
🎉 Bienvenue sur JB Shop !

Bonjour {Nom},

Merci de nous avoir rejoint ! 🙏

Nous sommes ravis de vous compter parmi nous.

🛍️ Découvrez nos produits :
https://jb-shop.com/shop

💡 Astuce : Installez notre application pour :
• Un accès plus rapide ⚡
• Des notifications de commandes 🔔
• Mode hors ligne 📱

Besoin d'aide ? Contactez-nous :
📞 +237-657-528-859
📧 brayeljunior8@gmail.com

Bon shopping ! 🛒
L'équipe JB Shop
```

---

## 🚀 Déploiement

### 1. Vérifier la configuration

```bash
php artisan config:cache
php artisan route:cache
```

### 2. Tester la connexion API

```bash
php artisan tinker

$service = app(\App\Services\WhatsAppService::class);
$result = $service->checkInstanceStatus();
print_r($result);
```

### 3. Test d'envoi de message

```bash
php artisan tinker

$service = app(\App\Services\WhatsAppService::class);
$result = $service->sendTextMessage('+237657528859', 'Test de notification JB Shop');
print_r($result);
```

### 4. Test OTP complet

1. Aller sur `/register`
2. Remplir le formulaire
3. Soumettre
4. Vérifier le WhatsApp pour le code
5. Saisir le code sur `/verify-otp`
6. Vérifier la création du compte

---

## 🔄 Intégration avec les commandes

### Observer de commandes (OrderObserver)

Pour envoyer automatiquement les notifications :

```php
// app/Observers/OrderObserver.php

use App\Services\OrderNotificationService;

public function created(Order $order)
{
    $notificationService = app(OrderNotificationService::class);
    $notificationService->notifyOrderPlaced($order);
}

public function updated(Order $order)
{
    if ($order->isDirty('status')) {
        $notificationService = app(OrderNotificationService::class);
        $notificationService->notifyOrderStatusChanged(
            $order,
            $order->getOriginal('status'),
            $order->status
        );
    }
}
```

---

## 📝 Logs et monitoring

Tous les envois WhatsApp sont loggés dans `storage/logs/laravel.log` :

```
[2025-12-21 18:06:45] local.INFO: WhatsApp message sent successfully  
[2025-12-21 18:06:45] local.INFO: OTP sent successfully  
[2025-12-21 18:07:12] local.INFO: OTP verified successfully  
```

---

## 🛠️ Maintenance

### Nettoyer les OTPs expirés

Créer une commande Artisan (optionnel) :

```bash
php artisan make:command CleanExpiredOtps
```

```php
// app/Console/Commands/CleanExpiredOtps.php

public function handle()
{
    $deleted = \App\Models\OtpVerification::cleanExpiredOtps();
    $this->info("Deleted {$deleted} expired OTPs");
}
```

Ajouter au scheduler (`app/Console/Kernel.php`) :

```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('clean:expired-otps')->daily();
}
```

---

## 🐛 Dépannage

### Message non reçu

1. Vérifier que l'instance Evolution est connectée :
   ```bash
   curl -H "apikey: YOUR_API_KEY" \
        https://whatsapi.fobs.dev/instance/connectionState/Jumeau
   ```

2. Vérifier le format du numéro :
   - Doit être au format international : `+237657528859`
   - Pas d'espaces ni de caractères spéciaux

3. Vérifier les logs Laravel :
   ```bash
   tail -f storage/logs/laravel.log
   ```

### OTP non valide

1. Vérifier que l'OTP n'est pas expiré (10 minutes)
2. Vérifier le nombre de tentatives (max 5)
3. Vérifier que le numéro correspond

### Erreur API Evolution

- **401 Unauthorized** : Vérifier la clé API
- **404 Not Found** : Vérifier le nom de l'instance
- **500 Server Error** : Contacter le support Evolution API

---

## 📊 Statistiques

Vous pouvez tracker les envois :

```php
// Nombre d'OTPs envoyés aujourd'hui
$otpsToday = OtpVerification::whereDate('created_at', today())->count();

// Taux de vérification
$verified = OtpVerification::where('verified', true)->count();
$total = OtpVerification::count();
$rate = ($verified / $total) * 100;
```

---

## 🔒 Sécurité

- ✅ Clés API stockées dans `.env` (pas de commit Git)
- ✅ Validation des numéros de téléphone
- ✅ Limitation des tentatives OTP
- ✅ Expiration automatique des OTPs
- ✅ Logs de tous les envois
- ✅ Données sensibles non loggées en production

---

## 📞 Support

Pour toute question ou problème :

- 📧 Email : brayeljunior8@gmail.com
- 📱 WhatsApp : +237-657-528-859

---

## ✅ Checklist de mise en production

- [ ] Variables d'environnement configurées
- [ ] Migrations exécutées
- [ ] Instance Evolution API connectée
- [ ] Tests d'envoi réussis
- [ ] Logs configurés
- [ ] Nettoyage automatique des OTPs planifié
- [ ] Documentation partagée avec l'équipe
- [ ] Numéros de test validés
- [ ] Messages en production validés
- [ ] Monitoring mis en place

---

**Système créé le 21 décembre 2025**  
**Version 1.0 - Production Ready** ✅
