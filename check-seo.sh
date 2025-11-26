#!/bin/bash

# Script de vérification des fichiers SEO pour JB Shop
# Date: 26 Novembre 2025

echo "=========================================="
echo "  🔍 Vérification des Fichiers SEO"
echo "  JB Shop - jbshop237.com"
echo "=========================================="
echo ""

# Couleurs
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Fonction de vérification
check_file() {
    if [ -f "$1" ]; then
        echo -e "${GREEN}✓${NC} $2 trouvé"
        return 0
    else
        echo -e "${RED}✗${NC} $2 MANQUANT"
        return 1
    fi
}

# Fonction de vérification de route
check_route() {
    if php artisan route:list | grep -q "$1"; then
        echo -e "${GREEN}✓${NC} Route $1 enregistrée"
        return 0
    else
        echo -e "${RED}✗${NC} Route $1 MANQUANTE"
        return 1
    fi
}

echo "📁 Vérification des fichiers statiques..."
echo "----------------------------------------"
check_file "public/robots.txt" "robots.txt"
check_file "public/.htaccess" ".htaccess"
check_file "public/humans.txt" "humans.txt"
check_file "public/manifest.json" "manifest.json"
check_file "public/.well-known/security.txt" "security.txt"
check_file "public/favicon.ico" "favicon.ico"
echo ""

echo "🎯 Vérification des contrôleurs..."
echo "----------------------------------------"
check_file "app/Http/Controllers/SitemapController.php" "SitemapController"
echo ""

echo "🛣️  Vérification des routes..."
echo "----------------------------------------"
check_route "sitemap"
echo ""

echo "📄 Vérification du contenu robots.txt..."
echo "----------------------------------------"
if grep -q "Sitemap: https://jbshop237.com/sitemap.xml" public/robots.txt; then
    echo -e "${GREEN}✓${NC} Référence sitemap présente"
else
    echo -e "${RED}✗${NC} Référence sitemap MANQUANTE"
fi

if grep -q "Disallow: /admin" public/robots.txt; then
    echo -e "${GREEN}✓${NC} Protection /admin présente"
else
    echo -e "${RED}✗${NC} Protection /admin MANQUANTE"
fi
echo ""

echo "🔒 Vérification .htaccess..."
echo "----------------------------------------"
if grep -q "Force HTTPS" public/.htaccess; then
    echo -e "${GREEN}✓${NC} Force HTTPS configuré"
else
    echo -e "${YELLOW}⚠${NC} Force HTTPS non configuré"
fi

if grep -q "mod_deflate" public/.htaccess; then
    echo -e "${GREEN}✓${NC} Compression Gzip activée"
else
    echo -e "${YELLOW}⚠${NC} Compression Gzip non activée"
fi

if grep -q "mod_expires" public/.htaccess; then
    echo -e "${GREEN}✓${NC} Cache navigateur configuré"
else
    echo -e "${YELLOW}⚠${NC} Cache navigateur non configuré"
fi
echo ""

echo "📱 Vérification manifest.json..."
echo "----------------------------------------"
if grep -q '"name": "JB Shop' public/manifest.json; then
    echo -e "${GREEN}✓${NC} PWA Manifest configuré"
else
    echo -e "${RED}✗${NC} PWA Manifest invalide"
fi
echo ""

echo "🌐 Test des URLs (si serveur actif)..."
echo "----------------------------------------"
if curl -s http://localhost:8000 > /dev/null 2>&1; then
    echo -e "${GREEN}✓${NC} Serveur Laravel actif"
    
    # Test sitemap
    if curl -s http://localhost:8000/sitemap.xml | grep -q "<?xml"; then
        echo -e "${GREEN}✓${NC} Sitemap.xml accessible et valide"
    else
        echo -e "${RED}✗${NC} Sitemap.xml non accessible"
    fi
    
    # Test robots.txt
    if curl -s http://localhost:8000/robots.txt | grep -q "User-agent"; then
        echo -e "${GREEN}✓${NC} Robots.txt accessible"
    else
        echo -e "${RED}✗${NC} Robots.txt non accessible"
    fi
else
    echo -e "${YELLOW}⚠${NC} Serveur Laravel non actif (lancez: php artisan serve)"
fi
echo ""

echo "=========================================="
echo "  📊 Résumé"
echo "=========================================="
echo ""
echo "Fichiers SEO créés :"
echo "  • robots.txt"
echo "  • sitemap.xml (dynamique)"
echo "  • humans.txt"
echo "  • manifest.json"
echo "  • .well-known/security.txt"
echo "  • .htaccess (optimisé)"
echo ""
echo "Prochaines étapes :"
echo "  1. Soumettre sitemap à Google Search Console"
echo "  2. Tester avec: https://search.google.com/test/rich-results"
echo "  3. Vérifier vitesse: https://pagespeed.web.dev/"
echo ""
echo "Documentation complète: SEO_CONFIGURATION_COMPLETE.md"
echo "=========================================="
