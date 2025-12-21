# ✅ Checklist de Mise en Production - WhatsApp Notifications

## 📋 Actions Avant Déploiement

### 1. Vérification de l'API Evolution ⚠️

- [ ] **Tester la connexion API depuis le serveur de production**
  ```bash
  curl -H "apikey: D95E71B2F84A46DCA30E89B15C72D648" \
       https://whatsapi.fobs.dev/instance/connectionState/Jumeau
  ```
  
- [ ] **Vérifier que l'instance WhatsApp "Jumeau" est bien connectée**
  - Se connecter au dashboard Evolution API
  - Vérifier le QR code si nécessaire
  - Confirmer que le statut est "open"

- [ ] **Tester un envoi manuel via l'API Evolution**
  ```bash
  curl -X POST \
       -H "apikey: D95E71B2F84A46DCA30E89B15C72D648" \
       -H "Content-Type: application/json" \
       -d '{"number":"+237657528859","text":"Test JB Shop"}' \
       https://whatsapi.fobs.dev/message/sendText/Jumeau
  ```

### 2. Configuration du Serveur

- [ ] **Variables d'environnement sur le serveur de production**
  ```env
  EVOLUTION_API_URL=https://whatsapi.fobs.dev
  EVOLUTION_INSTANCE_NAME=Jumeau
  EVOLUTION_API_KEY=D95E71B2F84A46DCA30E89B15C72D648
  ```

- [ ] **Vérifier les permissions des fichiers**
  ```bash
  chmod -R 755 storage
  chmod -R 755 bootstrap/cache
  ```

- [ ] **Vider le cache Laravel**
  ```bash
  php artisan config:clear
  php artisan cache:clear
  php artisan view:clear
  php artisan route:clear
  ```

- [ ] **Reconstruire le cache**
  ```bash
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
  ```

### 3. Base de Données

- [ ] **Exécuter les migrations sur production**
  ```bash
  php artisan migrate --force
  ```
  
- [ ] **Vérifier les tables créées**
  ```sql
  SHOW TABLES LIKE 'otp_verifications';
  DESCRIBE users;  -- Vérifier la colonne phone_verified_at
  ```

- [ ] **Backup de la base de données AVANT migration**
  ```bash
  mysqldump -u root -p jb_db > backup_before_whatsapp_$(date +%Y%m%d).sql
  ```

### 4. Tests sur Production

- [ ] **Test 1 : Vérifier le statut API**
  ```bash
  php artisan whatsapp:test --check-status
  ```
  Résultat attendu : ✅ Instance connectée

- [ ] **Test 2 : Envoyer un message test à votre numéro**
  ```bash
  php artisan whatsapp:test +237657528859 --send-test
  ```
  Résultat attendu : ✅ Message reçu sur WhatsApp

- [ ] **Test 3 : Tester l'OTP**
  ```bash
  php artisan whatsapp:test +237657528859 --send-otp
  ```
  Résultat attendu : ✅ Code OTP reçu sur WhatsApp

- [ ] **Test 4 : Inscription complète via navigateur**
  - Aller sur https://votre-domaine.com/register
  - Utiliser un numéro de test
  - Vérifier la réception de l'OTP
  - Compléter la vérification
  - Vérifier le message de bienvenue

- [ ] **Test 5 : Notification de commande**
  - Créer une commande de test
  - Vérifier la notification WhatsApp
  - Vérifier le formatage et le contenu

### 5. Monitoring et Logs

- [ ] **Configurer la rotation des logs**
  ```bash
  # config/logging.php
  'daily' => [
      'driver' => 'daily',
      'path' => storage_path('logs/laravel.log'),
      'level' => 'debug',
      'days' => 14,
  ],
  ```

- [ ] **Vérifier les logs en temps réel**
  ```bash
  tail -f storage/logs/laravel.log | grep "WhatsApp"
  ```

- [ ] **Configurer les alertes pour les erreurs**
  - Slack, Discord, ou email pour les erreurs critiques
  - Monitoring du taux d'échec des envois

### 6. Sécurité

- [ ] **Vérifier que `.env` n'est PAS dans Git**
  ```bash
  git check-ignore .env  # Doit retourner .env
  ```

- [ ] **Permissions des fichiers sensibles**
  ```bash
  chmod 600 .env
  chmod 644 config/services.php
  ```

- [ ] **HTTPS activé sur le serveur**
  - Certificat SSL valide
  - Redirections HTTP → HTTPS

- [ ] **Rate limiting configuré**
  - Limiter les tentatives d'OTP par IP
  - Limiter les envois de messages

### 7. Performance

- [ ] **Activer le cache Opcache (PHP)**
  ```ini
  opcache.enable=1
  opcache.memory_consumption=256
  ```

- [ ] **Configurer les queues (optionnel mais recommandé)**
  ```bash
  # Changer dans .env
  QUEUE_CONNECTION=database
  
  # Lancer le worker
  php artisan queue:work --daemon
  
  # Ou avec supervisor
  sudo supervisorctl start laravel-worker:*
  ```

- [ ] **Indexer la table otp_verifications**
  - Vérifier que les index sont bien créés
  - Tester la performance des requêtes

### 8. Documentation

- [ ] **Partager avec l'équipe**
  - WHATSAPP_INTEGRATION_GUIDE.md
  - WHATSAPP_TEST_GUIDE.md
  - WHATSAPP_IMPLEMENTATION_SUMMARY.md

- [ ] **Former l'équipe support**
  - Comment vérifier les logs
  - Dépannage courant
  - Procédure de réinitialisation OTP

- [ ] **Documenter les credentials**
  - Où trouver la clé API Evolution
  - Comment régénérer la clé si nécessaire
  - Accès au dashboard Evolution

---

## 🚨 Procédures d'Urgence

### Si les messages ne s'envoient pas

1. **Vérifier le statut de l'instance**
   ```bash
   php artisan whatsapp:test --check-status
   ```

2. **Reconnecter l'instance WhatsApp**
   - Aller sur le dashboard Evolution API
   - Scanner le QR code si nécessaire

3. **Vérifier les logs**
   ```bash
   tail -100 storage/logs/laravel.log | grep "WhatsApp\|Error"
   ```

4. **Mode dégradé : Désactiver temporairement**
   ```php
   // Dans WhatsAppService.php, ajouter au début de sendTextMessage()
   if (config('app.env') === 'production' && !config('services.evolution.enabled', true)) {
       Log::info('WhatsApp disabled - would send: ' . $message);
       return ['success' => true, 'disabled' => true];
   }
   ```

### Si trop d'OTPs échouent

1. **Nettoyer les anciens OTPs**
   ```bash
   php artisan tinker
   \App\Models\OtpVerification::where('created_at', '<', now()->subDay())->delete();
   ```

2. **Vérifier les tentatives bloquées**
   ```sql
   SELECT phone, attempts, created_at 
   FROM otp_verifications 
   WHERE attempts >= 5 
   ORDER BY created_at DESC;
   ```

3. **Réinitialiser un utilisateur bloqué**
   ```bash
   php artisan tinker
   \App\Models\OtpVerification::where('phone', '+237657528859')->delete();
   ```

### Rollback en cas de problème

```bash
# 1. Rollback des migrations
php artisan migrate:rollback --step=2

# 2. Restaurer le backup
mysql -u root -p jb_db < backup_before_whatsapp_20251221.sql

# 3. Réinstaller l'ancienne version
git checkout <commit-avant-whatsapp>
composer install
php artisan config:cache
```

---

## 📊 Métriques à Surveiller

### Première semaine

- [ ] **Nombre d'inscriptions avec OTP**
  ```sql
  SELECT COUNT(*) FROM users WHERE phone_verified_at IS NOT NULL;
  ```

- [ ] **Taux de succès OTP**
  ```sql
  SELECT 
      COUNT(CASE WHEN verified = 1 THEN 1 END) * 100.0 / COUNT(*) as success_rate
  FROM otp_verifications;
  ```

- [ ] **Temps moyen de vérification**
  ```sql
  SELECT AVG(TIMESTAMPDIFF(SECOND, created_at, verified_at)) as avg_seconds
  FROM otp_verifications
  WHERE verified = 1;
  ```

- [ ] **Nombre de notifications commandes envoyées**
  ```bash
  grep "WhatsApp message sent successfully" storage/logs/laravel.log | wc -l
  ```

- [ ] **Taux d'échec des envois**
  ```bash
  grep "WhatsApp message failed" storage/logs/laravel.log | wc -l
  ```

### Alertes à configurer

- ❗ Taux d'échec > 10%
- ❗ Temps de réponse API > 5 secondes
- ❗ Plus de 100 OTPs non vérifiés
- ❗ Instance WhatsApp déconnectée

---

## ✅ Validation Finale

### Avant de marquer comme "Production Ready"

- [ ] Tous les tests passent ✅
- [ ] API Evolution opérationnelle ✅
- [ ] Messages reçus en moins de 5 secondes ✅
- [ ] Inscription complète testée ✅
- [ ] Notifications de commandes testées ✅
- [ ] Logs sans erreurs critiques ✅
- [ ] Documentation complète ✅
- [ ] Équipe formée ✅
- [ ] Backup effectué ✅
- [ ] Monitoring en place ✅

### Signature de validation

```
Testé par : _______________________
Date : _______________________
Signature : _______________________

Approuvé par : _______________________
Date : _______________________
Signature : _______________________
```

---

## 📞 Contacts d'Urgence

- **Développeur** : brayeljunior8@gmail.com / +237-657-528-859
- **Support Evolution API** : [Lien support]
- **Admin Serveur** : [Contact]

---

**Checklist créée le 21 décembre 2025**  
**Version 1.0**

Une fois cette checklist complétée, le système sera **100% opérationnel en production** ! 🚀
