# 🚀 Guide de Test Rapide - WhatsApp Integration

## Tests de Base

### 1. Vérifier le statut de l'API

```bash
php artisan whatsapp:test --check-status
```

**Résultat attendu :**
- ✅ Instance connectée avec succès
- Affiche les informations de connexion

---

### 2. Envoyer un message test

```bash
php artisan whatsapp:test +237657528859 --send-test
```

**Résultat attendu :**
- ✅ Message envoyé avec succès
- Message reçu sur WhatsApp dans les 2-5 secondes

---

### 3. Envoyer un OTP test

```bash
php artisan whatsapp:test +237657528859 --send-otp
```

**Résultat attendu :**
- ✅ OTP envoyé avec succès
- Code à 6 chiffres affiché dans le terminal
- Message OTP reçu sur WhatsApp

---

### 4. Menu interactif complet

```bash
php artisan whatsapp:test
```

Sélectionnez l'option souhaitée dans le menu.

---

## Test du Flux d'Inscription Complet

### Étape 1 : Démarrer le serveur

```bash
php artisan serve
```

### Étape 2 : Accéder à l'inscription

Ouvrir dans le navigateur :
```
http://localhost:8000/register
```

### Étape 3 : Remplir le formulaire

- **Nom** : Jean Dupont
- **Téléphone** : +237657528859 (ou votre numéro)
- **Mot de passe** : minimum 8 caractères
- **Confirmation** : même mot de passe

### Étape 4 : Vérifier WhatsApp

Vous devriez recevoir un message avec le code OTP à 6 chiffres.

### Étape 5 : Saisir l'OTP

- Page `/verify-otp` s'affiche automatiquement
- Saisir le code reçu
- Cliquer sur "Vérifier le Code"

### Étape 6 : Confirmation

- ✅ Compte créé avec succès
- ✅ Message de bienvenue reçu sur WhatsApp
- ✅ Connexion automatique
- ✅ Redirection vers la page d'accueil

---

## Test des Notifications de Commandes

### Via Tinker

```bash
php artisan tinker
```

```php
// Obtenir le service
$service = app(\App\Services\WhatsAppService::class);

// Obtenir un utilisateur de test
$user = \App\Models\User::first();

// Créer une commande fictive
$order = new \App\Models\Order([
    'order_number' => 'ORD-TEST-' . rand(1000, 9999),
    'total_amount' => 50000,
    'shipping_address' => 'Douala, Cameroun',
    'phone' => $user->phone,
    'created_at' => now(),
]);
$order->user_id = $user->id;

// Simuler des items
$order->items = collect([
    (object)[
        'product_name' => 'Produit Test A',
        'quantity' => 2,
        'price' => 15000,
    ],
    (object)[
        'product_name' => 'Produit Test B',
        'quantity' => 1,
        'price' => 20000,
    ],
]);

// Envoyer la notification
$result = $service->sendOrderNotification($order, $user);
print_r($result);
```

**Résultat attendu :**
- Message de confirmation de commande reçu sur WhatsApp
- Détails complets de la commande
- Formatage élégant avec emojis

---

## Test de Mise à Jour de Statut

```php
// Dans Tinker
$service = app(\App\Services\WhatsAppService::class);
$user = \App\Models\User::first();
$order = \App\Models\Order::first();

// Tester différents statuts
$service->sendOrderStatusUpdate($order, $user, 'processing');
$service->sendOrderStatusUpdate($order, $user, 'shipped');
$service->sendOrderStatusUpdate($order, $user, 'delivered');
```

---

## Vérification de la Base de Données

### Voir les OTPs créés

```bash
php artisan tinker
```

```php
// Tous les OTPs
\App\Models\OtpVerification::all();

// OTPs d'aujourd'hui
\App\Models\OtpVerification::whereDate('created_at', today())->get();

// OTPs vérifiés
\App\Models\OtpVerification::where('verified', true)->get();

// OTPs non expirés
\App\Models\OtpVerification::notExpired()->get();
```

### Voir les utilisateurs avec téléphone vérifié

```php
\App\Models\User::whereNotNull('phone_verified_at')->get();
```

---

## Vérification des Logs

```bash
# Logs en temps réel
tail -f storage/logs/laravel.log

# Filtrer les logs WhatsApp
grep "WhatsApp" storage/logs/laravel.log

# Logs OTP
grep "OTP" storage/logs/laravel.log
```

---

## Dépannage Rapide

### ❌ Message non reçu

**Solution 1 :** Vérifier le format du numéro
```php
$service = app(\App\Services\WhatsAppService::class);
$formatted = $service->formatPhoneNumber('657528859');
echo $formatted; // Devrait afficher : +237657528859
```

**Solution 2 :** Vérifier la connexion API
```bash
php artisan whatsapp:test --check-status
```

**Solution 3 :** Vérifier les variables d'environnement
```bash
php artisan config:clear
php artisan config:cache
```

### ❌ OTP invalide ou expiré

**Solution :** Nettoyer les anciens OTPs
```php
// Dans Tinker
\App\Models\OtpVerification::where('phone', '+237657528859')->delete();
```

### ❌ Erreur 401 (Unauthorized)

**Solution :** Vérifier la clé API dans `.env`
```env
EVOLUTION_API_KEY=D95E71B2F84A46DCA30E89B15C72D648
```

### ❌ Erreur 404 (Not Found)

**Solution :** Vérifier le nom de l'instance
```env
EVOLUTION_INSTANCE_NAME=Jumeau
```

---

## Tests de Performance

### Tester l'envoi groupé

```php
// Dans Tinker
$service = app(\App\Services\WhatsAppService::class);

$phones = [
    '+237657528859',
    '+237698765432',
    // Ajouter d'autres numéros
];

foreach ($phones as $phone) {
    $result = $service->sendTextMessage($phone, 'Test groupé JB Shop');
    echo $phone . ': ' . ($result['success'] ? '✅' : '❌') . "\n";
    sleep(2); // Pause de 2 secondes entre les envois
}
```

---

## Checklist de Validation ✅

Avant de passer en production, vérifier :

- [ ] `php artisan whatsapp:test --check-status` réussit
- [ ] Message test reçu sur WhatsApp
- [ ] OTP reçu sur WhatsApp
- [ ] Inscription complète fonctionne
- [ ] Message de bienvenue reçu
- [ ] Notification de commande reçue
- [ ] Mise à jour de statut reçue
- [ ] Numéros correctement formatés
- [ ] Logs sans erreurs
- [ ] Variables d'environnement correctes
- [ ] Migrations exécutées

---

## Commandes Utiles

```bash
# Nettoyer le cache
php artisan optimize:clear

# Recréer la configuration
php artisan config:cache

# Lister toutes les routes
php artisan route:list | grep otp

# Vérifier les migrations
php artisan migrate:status

# Rollback dernière migration
php artisan migrate:rollback --step=1
```

---

## Support

- 📧 brayeljunior8@gmail.com
- 📱 +237-657-528-859
- 📖 [WHATSAPP_INTEGRATION_GUIDE.md](./WHATSAPP_INTEGRATION_GUIDE.md)

---

**Happy Testing! 🚀**
