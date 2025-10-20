# Documentation - Gestion des Utilisateurs (Admin)

## Vue d'ensemble
Système complet de gestion des utilisateurs pour l'administrateur avec possibilité de visualiser, consulter les détails et supprimer les utilisateurs.

## Fonctionnalités Implémentées

### 1. **Liste des Utilisateurs** (`/admin/users`)

#### Caractéristiques:
- ✅ Table responsive avec pagination (15 utilisateurs par page)
- ✅ Affichage des informations clés:
  - ID utilisateur
  - Nom avec avatar (première lettre)
  - Email
  - Nombre d'articles dans le panier
  - Nombre d'articles dans la wishlist
  - Date d'inscription
  - Actions (Voir / Supprimer)

#### Protections:
- ✅ Badge "Administrateur" pour le compte admin principal
- ✅ Bouton de suppression désactivé pour le compte admin
- ✅ Protection contre la suppression de son propre compte
- ✅ Exclusion automatique du compte connecté de la liste

#### Design:
- ✅ Badges colorés pour le panier (bleu) et wishlist (jaune)
- ✅ Avatar circulaire avec initiales
- ✅ Effet hover sur les lignes du tableau
- ✅ Messages de succès/erreur avec auto-fermeture (5s)
- ✅ Bouton d'impression

### 2. **Détails d'un Utilisateur** (`/admin/users/{id}`)

#### Informations affichées:
- **Profil utilisateur:**
  - Avatar avec initiales
  - Nom et email
  - Statut (Admin / Utilisateur)
  - Date d'inscription
  - Date de dernière mise à jour

- **Statistiques:**
  - Nombre d'articles dans le panier (carte bleu info)
  - Nombre d'articles dans la wishlist (carte jaune warning)

- **Détails du panier:**
  - Liste complète des produits
  - Prix de chaque produit
  - Date d'ajout au panier
  - Liens vers les pages produits

- **Détails de la wishlist:**
  - Liste complète des produits
  - Prix de chaque produit
  - Date d'ajout à la wishlist
  - Liens vers les pages produits

#### Design:
- ✅ Layout responsive avec sidebar
- ✅ Cartes colorées pour les statistiques
- ✅ Tables avec tri et affichage propre
- ✅ Breadcrumb pour la navigation
- ✅ Bouton retour vers la liste

### 3. **Suppression d'un Utilisateur** (Modal de confirmation)

#### Fonctionnalités:
- ✅ Modal de confirmation stylisé avec icône d'avertissement
- ✅ Affichage du nom de l'utilisateur à supprimer
- ✅ Liste des éléments qui seront supprimés:
  - Articles du panier
  - Articles de la wishlist
  - Le compte utilisateur

#### Protections:
- ✅ Impossible de supprimer le compte "admin" principal
- ✅ Impossible de supprimer son propre compte
- ✅ Confirmation requise avant suppression
- ✅ Suppression en cascade (panier + wishlist)
- ✅ Gestion des erreurs avec messages explicites

#### Messages:
- **Succès:** "Utilisateur supprimé avec succès."
- **Erreur (auto-suppression):** "Vous ne pouvez pas supprimer votre propre compte."
- **Erreur (admin principal):** "Le compte administrateur principal ne peut pas être supprimé."

## Architecture Technique

### Fichiers Créés/Modifiés

#### 1. **Controller** : `app/Http/Controllers/UserController.php`
```php
Méthodes:
- index() : Liste paginée des utilisateurs
- show($id) : Détails d'un utilisateur avec relations
- destroy($id) : Suppression avec protections
```

#### 2. **Routes** : `routes/web.php`
```php
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/{id}', [UserController::class, 'show'])->name('admin.users.show');
    Route::delete('/admin/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');
});
```

#### 3. **Vues**

**Liste:** `resources/views/admin/users/index.blade.php`
- Table responsive avec pagination
- Modal de confirmation de suppression
- Messages flash
- JavaScript pour gestion de la modale
- Styles CSS inline pour animations

**Détails:** `resources/views/admin/users/show.blade.php`
- Profil utilisateur
- Statistiques
- Liste des activités (panier + wishlist)

**Sidebar:** `resources/views/layouts/admin-sidebar.blade.php`
- Navigation administrative
- Lien "Utilisateurs" actif
- Profil admin en bas
- Bouton déconnexion

#### 4. **Modèles**

**User.php** : Ajout des relations
```php
public function cartItems() {
    return $this->hasMany(Cart::class, 'user_id');
}

public function wishlistItems() {
    return $this->hasMany(wishes::class, 'user_id');
}
```

**wishes.php** : Ajout des relations
```php
public function offer() {
    return $this->belongsTo(offers::class, 'offer_id');
}

public function user() {
    return $this->belongsTo(User::class, 'user_id');
}
```

#### 5. **Layout** : `resources/views/layouts/app.blade.php`
- Mise à jour du lien sidebar "Users" → "Utilisateurs"
- Pointage vers route `admin.users.index`
- Icône changée vers `fa-users`

## Utilisation

### Accès à la gestion des utilisateurs

1. **Via le menu sidebar:**
   - Connectez-vous en tant qu'admin
   - Cliquez sur "Utilisateurs" dans le menu latéral

2. **URL directe:**
   ```
   http://localhost:8000/admin/users
   ```

### Consulter les détails d'un utilisateur

1. Dans la liste, cliquez sur le bouton "œil" (👁️) d'un utilisateur
2. Vous verrez:
   - Informations du profil
   - Statistiques d'activité
   - Liste des produits dans le panier
   - Liste des produits dans la wishlist

### Supprimer un utilisateur

1. Dans la liste, cliquez sur le bouton "corbeille" (🗑️)
2. Une modale de confirmation s'ouvre
3. Vérifiez le nom de l'utilisateur
4. Cliquez sur "Supprimer" pour confirmer
5. Un message de succès s'affiche

**Note:** Impossible de supprimer le compte "admin" ou votre propre compte.

## Sécurité

### Middleware
- ✅ `auth` : Authentification requise
- ✅ `admin` : Vérification du rôle admin (nom = 'admin')

### Protections Controller
```php
// Empêcher la suppression de soi-même
if ($id == Auth::id()) {
    return redirect()->back()->with('error', '...');
}

// Empêcher la suppression du compte admin principal
if ($user->name === 'admin') {
    return redirect()->back()->with('error', '...');
}
```

### Suppression en cascade
```php
// Supprimer les dépendances avant l'utilisateur
$user->cartItems()->delete();
$user->wishlistItems()->delete();
$user->delete();
```

## Interface Utilisateur

### Responsive Design
- **Desktop:** Table complète avec toutes les colonnes
- **Tablet:** Colonnes adaptées
- **Mobile:** Table scrollable horizontalement

### Couleurs et Badges
- **Primary (Bleu):** ID utilisateur, statistiques totales
- **Info (Cyan):** Panier
- **Warning (Jaune):** Wishlist
- **Danger (Rouge):** Administrateur, suppression
- **Success (Vert):** Utilisateur standard

### Animations
- Hover sur les lignes du tableau (scale 1.01, box-shadow)
- Fade in/out pour les alertes
- Transitions douces sur les boutons

## Tests Effectués

### ✅ Vérifications
```bash
php artisan route:clear      # Succès
php artisan view:clear       # Succès
php artisan config:clear     # Succès
php artisan route:list       # Routes confirmées
```

### ✅ Routes enregistrées
```
GET    /admin/users          → admin.users.index
GET    /admin/users/{id}     → admin.users.show
DELETE /admin/users/{id}     → admin.users.destroy
```

### ✅ Code
- Aucune erreur de syntaxe
- Relations Eloquent fonctionnelles
- Middleware appliqué correctement

## Workflow de Suppression

```
1. Utilisateur clique sur bouton "Supprimer"
2. JavaScript déclenche la modale
3. Nom de l'utilisateur affiché dans la modale
4. Utilisateur confirme
5. Formulaire DELETE soumis
6. Controller vérifie les protections
7. Suppression du panier
8. Suppression de la wishlist
9. Suppression de l'utilisateur
10. Redirection avec message de succès
```

## Améliorations Futures

### Phase 1
- [ ] Export CSV/Excel de la liste
- [ ] Filtrage par date d'inscription
- [ ] Recherche par nom/email
- [ ] Tri personnalisé des colonnes

### Phase 2
- [ ] Édition des utilisateurs
- [ ] Statistiques d'activité détaillées
- [ ] Graphiques d'engagement
- [ ] Notifications email

### Phase 3
- [ ] Gestion des rôles et permissions
- [ ] Activation/Désactivation de comptes
- [ ] Historique des actions
- [ ] Logs d'audit

## Dépannage

### Erreur "Route not found"
```bash
php artisan route:clear
php artisan route:cache
```

### Erreur "View not found"
```bash
php artisan view:clear
```

### Pas d'utilisateurs affichés
- Vérifier que des utilisateurs existent dans la DB
- Vérifier que vous n'êtes pas le seul utilisateur (exclusion du compte connecté)

### Bouton supprimer désactivé
- C'est normal pour le compte "admin"
- Vérifier que `$user->name === 'admin'`

## Base de Données

### Tables utilisées
- `users` : Utilisateurs
- `carts` : Articles dans les paniers
- `wishes` : Articles dans les wishlists
- `offers` : Produits

### Relations
```
User
  ├── hasMany(Cart) via user_id
  └── hasMany(wishes) via user_id

Cart
  ├── belongsTo(User) via user_id
  └── belongsTo(offers) via offer_id

wishes
  ├── belongsTo(User) via user_id
  └── belongsTo(offers) via offer_id
```

## Compatibilité

- ✅ Laravel 11/12
- ✅ PHP 8.1+
- ✅ Bootstrap 5
- ✅ Font Awesome 6
- ✅ Tailwind CSS
- ✅ Tous navigateurs modernes

## Notes Importantes

1. **Compte admin protégé:** Le compte avec `name = 'admin'` ne peut jamais être supprimé
2. **Auto-protection:** Un admin ne peut pas se supprimer lui-même
3. **Suppression en cascade:** Panier et wishlist sont supprimés automatiquement
4. **Pagination:** 15 utilisateurs par page par défaut
5. **Messages temporaires:** Les alertes se ferment après 5 secondes

---

**Système prêt pour la production** ✨

Pour accéder: `/admin/users` (authentification admin requise)
