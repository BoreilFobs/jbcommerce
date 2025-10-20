# Documentation des Filtres de la Boutique

## Vue d'ensemble
Le système de filtrage de la boutique permet aux utilisateurs de rechercher et filtrer les produits de manière intuitive avec plusieurs critères.

## Fonctionnalités Implémentées

### 1. **Recherche de Produits**
- Champ de recherche dans la barre latérale
- Recherche sur : nom, catégorie, description, marque
- Préservation de la requête de recherche lors de la pagination

### 2. **Filtres par Catégorie**
- Affichage de toutes les catégories avec compteurs
- Sélection par boutons radio
- Mise à jour automatique lors de la sélection
- Badge indiquant le nombre total de produits

### 3. **Fourchette de Prix**
- Champs pour prix minimum et maximum
- Valeurs pré-remplies basées sur les produits disponibles
- Auto-soumission avec délai (debounce) de 1 seconde
- Affichage de la plage de prix disponible

### 4. **Filtre par Marque**
- Liste de toutes les marques disponibles
- Sélection par boutons radio
- Scroll pour les longues listes (max-height: 200px)

### 5. **Filtres Rapides**
- **Produits vedettes** : Produits marqués comme featured
- **Nouveautés** : Produits ajoutés dans les 30 derniers jours
- **En promotion** : Produits avec réduction > 0%
- **En stock** : Produits avec quantité > 0
- Cases à cocher indépendantes

### 6. **Tri des Résultats**
Options de tri disponibles :
- Par défaut
- Prix : Croissant
- Prix : Décroissant
- Nom : A-Z
- Nom : Z-A
- Popularité (par nombre de vues)
- Nouveautés (par date de création)

### 7. **Affichage des Filtres Actifs**
- Badges colorés pour chaque filtre actif
- Bouton "×" pour supprimer un filtre individuel
- Bouton "Tout effacer" pour réinitialiser tous les filtres
- Animation d'entrée pour les badges

### 8. **Pagination**
- Pagination Laravel avec 9 produits par page
- Préservation de tous les filtres lors du changement de page
- Design personnalisé avec animations
- Responsive mobile

### 9. **Cartes Produits Améliorées**
Chaque carte produit affiche :
- Image principale avec effet hover (zoom)
- Badges multiples :
  - Vedette (jaune)
  - Réduction en % (rouge)
  - Nouveau (vert)
  - Rupture de stock / Stock limité (gris/orange)
- Catégorie cliquable
- Nom du produit (limité à 50 caractères)
- Marque (si disponible)
- Prix avec réduction si applicable
- Note par étoiles (4/5)
- Nombre de vues
- Bouton "Ajouter au panier" (désactivé si rupture de stock)
- Icône wishlist

## Architecture Technique

### Fichiers Modifiés/Créés

#### 1. **Controller** : `app/Http/Controllers/WelcomeController.php`
```php
public function shop()
{
    // Query builder pour les filtres
    $query = offers::where('status', 'active');
    
    // Filtres appliqués :
    - search (across name, category, description, brand)
    - category
    - brand
    - price range (min_price, max_price)
    - featured
    - new_arrivals (30 days)
    - on_sale (discount > 0)
    - in_stock (quantity > 0)
    
    // Tri dynamique
    // Pagination (9 par page)
    // Données supplémentaires : brands, minPrice, maxPrice, categoryCounts
}
```

#### 2. **View Sidebar** : `resources/views/layouts/store.blade.php`
- Formulaire de recherche
- Formulaire de filtres avec auto-soumission
- Filtres de catégorie avec compteurs
- Fourchette de prix
- Filtres de marque
- Filtres rapides (checkboxes)
- Produits populaires
- JavaScript pour auto-soumission

#### 3. **View Principale** : `resources/views/store.blade.php`
- En-tête avec résultats et tri
- Affichage des filtres actifs
- Grille de produits responsive
- Pagination Laravel
- Gestion de l'état vide

#### 4. **Styles** : `public/css/store-filters.css`
- Styles de pagination personnalisés
- Styles de badges de filtres
- Animations hover
- Responsive design
- Loading states

#### 5. **Layout** : `resources/views/layouts/web.blade.php`
- Inclusion du fichier CSS store-filters.css

## Flux de Données

### 1. Soumission de Filtre
```
User Input → Form Submission → WelcomeController@shop() → Query Builder → Database → Results
```

### 2. Préservation des Filtres
- Tous les paramètres GET sont préservés dans les liens de pagination
- Le formulaire de tri préserve tous les filtres existants
- Les badges de filtres actifs incluent les paramètres dans les liens de suppression

### 3. Auto-soumission
```javascript
// Filtres checkbox/radio : soumission immédiate
document.querySelectorAll('.filter-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });
});

// Prix : soumission avec debounce (1 seconde)
let priceTimeout;
document.querySelectorAll('#min_price, #max_price').forEach(input => {
    input.addEventListener('input', function() {
        clearTimeout(priceTimeout);
        priceTimeout = setTimeout(() => {
            document.getElementById('filterForm').submit();
        }, 1000);
    });
});
```

## Responsive Design

### Desktop (>992px)
- Sidebar fixe (sticky) avec scroll indépendant
- 3 colonnes de produits
- Tous les filtres visibles

### Tablet (768px-992px)
- 2 colonnes de produits
- Sidebar en pleine largeur au-dessus

### Mobile (<768px)
- 1 colonne de produits
- Sidebar en pleine largeur
- Pagination compacte
- Badges de filtres empilés

## Performance

### Optimisations
1. **Query Builder** : Utilisation de scopes Eloquent pour des requêtes efficaces
2. **Eager Loading** : Chargement des relations nécessaires
3. **Pagination** : Seulement 9 produits chargés par page
4. **Debounce** : Évite les soumissions multiples pour la fourchette de prix
5. **CSS** : Fichier séparé pour le cache navigateur

### Indexation Recommandée
```sql
-- Pour améliorer les performances
CREATE INDEX idx_offers_status ON offers(status);
CREATE INDEX idx_offers_category ON offers(category);
CREATE INDEX idx_offers_brand ON offers(brand);
CREATE INDEX idx_offers_price ON offers(price);
CREATE INDEX idx_offers_featured ON offers(featured);
CREATE INDEX idx_offers_created_at ON offers(created_at);
CREATE INDEX idx_offers_views ON offers(views);
```

## Utilisation

### URL Examples

#### Recherche simple
```
/shop?search=samsung
```

#### Filtre par catégorie
```
/shop?category=Smartphones
```

#### Filtres combinés
```
/shop?category=Smartphones&brand=Samsung&min_price=50000&max_price=200000&on_sale=1&sort=price_asc
```

#### Avec pagination
```
/shop?category=Smartphones&page=2
```

## États des Produits

### Badges Affichés
- **Vedette** : `featured = true`
- **Réduction** : `discount_percentage > 0`
- **Nouveau** : `created_at >= 30 days ago`
- **Rupture de stock** : `quantity = 0`
- **Stock limité** : `quantity < 10`

### Bouton Panier
- **Actif** : `quantity > 0` + utilisateur authentifié
- **Redirige vers login** : `quantity > 0` + utilisateur guest
- **Désactivé** : `quantity = 0`

## Accessibilité

- Labels appropriés pour tous les champs de formulaire
- Attributs ARIA pour les éléments interactifs
- Focus visible sur les éléments cliquables
- Navigation au clavier supportée
- Contraste de couleurs conforme

## Traductions

Toutes les chaînes sont en français :
- Rechercher
- Catégories
- Fourchette de prix
- Marques
- Filtres rapides
- Produits vedettes
- Nouveautés
- En promotion
- En stock
- Trier par
- Aucun produit trouvé
- etc.

## Améliorations Futures

1. **AJAX Filtering** : Filtrage sans rechargement de page
2. **Infinite Scroll** : Alternative à la pagination
3. **Sauvegarde des Filtres** : Se souvenir des préférences utilisateur
4. **Filtres Avancés** :
   - Plage de dates
   - Note minimum
   - Spécifications techniques
5. **Vue Grille/Liste** : Toggle entre différentes vues
6. **Comparaison** : Comparer plusieurs produits
7. **Historique** : Produits récemment consultés

## Support Navigateurs

- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+
- Mobile browsers (iOS Safari, Chrome Mobile)

## Tests Recommandés

1. Tester chaque filtre individuellement
2. Tester les combinaisons de filtres
3. Vérifier la pagination avec filtres
4. Tester sur mobile/tablet
5. Vérifier les états vides
6. Tester avec utilisateur authentifié/guest
7. Vérifier les performances avec grand nombre de produits

## Dépannage

### Aucun produit affiché
- Vérifier que des produits ont `status = 'active'`
- Vérifier les filtres appliqués
- Consulter les logs Laravel

### Pagination ne fonctionne pas
- Vérifier que `$offers->appends(request()->except('page'))->links()` est utilisé
- Vérifier que le trait Paginate est importé

### Filtres ne s'appliquent pas
- Vérifier que le formulaire soumet vers `route('shop')`
- Vérifier le JavaScript d'auto-soumission
- Consulter les paramètres GET dans l'URL

## Notes de Développement

- Le code est organisé et commenté
- Utilise les best practices Laravel
- Compatible avec Laravel 11/12
- PSR-12 coding standard
- Blade components prêts pour réutilisation
