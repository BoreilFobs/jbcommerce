/**
 * PWA Enhanced Features
 * Améliore l'expérience d'installation PWA sur mobile
 * Détecte WhatsApp, navigateurs in-app et guide l'utilisateur
 */

(function() {
    'use strict';
    
    // Détecter si on est dans WhatsApp in-app browser
    function isWhatsAppBrowser() {
        const ua = navigator.userAgent || navigator.vendor || window.opera;
        return /WhatsApp/i.test(ua);
    }
    
    // Détecter si on est dans Facebook/Instagram in-app browser
    function isSocialBrowser() {
        const ua = navigator.userAgent || navigator.vendor || window.opera;
        return /FBAN|FBAV|Instagram/i.test(ua);
    }
    
    // Détecter le navigateur mobile
    function getMobileBrowser() {
        const ua = navigator.userAgent || navigator.vendor || window.opera;
        
        if (/Chrome/i.test(ua) && !/Edge/i.test(ua)) return 'chrome';
        if (/Safari/i.test(ua) && !/Chrome/i.test(ua)) return 'safari';
        if (/Firefox/i.test(ua)) return 'firefox';
        if (/Edge/i.test(ua)) return 'edge';
        if (/Opera|OPR/i.test(ua)) return 'opera';
        
        return 'unknown';
    }
    
    // Afficher un guide pour ouvrir dans le navigateur principal
    function showOpenInBrowserGuide() {
        // Ne pas afficher si déjà vu récemment
        const lastShown = localStorage.getItem('browser_guide_shown');
        if (lastShown) {
            const hoursSince = (Date.now() - parseInt(lastShown)) / (1000 * 60 * 60);
            if (hoursSince < 24) return;
        }
        
        const isWhatsApp = isWhatsAppBrowser();
        const isSocial = isSocialBrowser();
        
        if (!isWhatsApp && !isSocial) return;
        
        const guide = document.createElement('div');
        guide.className = 'browser-guide-modal';
        guide.innerHTML = `
            <div class="browser-guide-content">
                <div class="browser-guide-header">
                    <i class="fas fa-info-circle"></i>
                    <h4>Meilleure Expérience</h4>
                    <button class="browser-guide-close" onclick="this.parentElement.parentElement.parentElement.remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="browser-guide-body">
                    <p>Pour installer JB Shop et profiter de toutes les fonctionnalités :</p>
                    <div class="browser-guide-steps">
                        <div class="guide-step">
                            <span class="step-number">1</span>
                            <span>Appuyez sur les <strong>3 points</strong> ${isWhatsApp ? 'en haut' : ''}</span>
                        </div>
                        <div class="guide-step">
                            <span class="step-number">2</span>
                            <span>Sélectionnez <strong>"Ouvrir dans Chrome"</strong> ou <strong>"Ouvrir dans le navigateur"</strong></span>
                        </div>
                        <div class="guide-step">
                            <span class="step-number">3</span>
                            <span>Installez l'application depuis Chrome</span>
                        </div>
                    </div>
                    <button class="btn-understand" onclick="this.parentElement.parentElement.parentElement.remove(); localStorage.setItem('browser_guide_shown', Date.now());">
                        J'ai Compris
                    </button>
                </div>
            </div>
        `;
        
        document.body.appendChild(guide);
        setTimeout(() => guide.classList.add('show'), 100);
    }
    
    // Vérifier si l'app peut être installée
    function checkPWACompatibility() {
        const isPWACompatible = 'serviceWorker' in navigator && 'PushManager' in window;
        const isStandalone = window.matchMedia('(display-mode: standalone)').matches;
        
        return {
            compatible: isPWACompatible,
            installed: isStandalone,
            browser: getMobileBrowser()
        };
    }
    
    // Améliorer les icônes du manifest selon le contexte
    function enhanceManifestIcons() {
        const manifestLink = document.querySelector('link[rel="manifest"]');
        if (!manifestLink) return;
        
        // Sur iOS, améliorer les icônes Apple
        if (/iPhone|iPad|iPod/.test(navigator.userAgent)) {
            const appleTouchIcon = document.querySelector('link[rel="apple-touch-icon"]');
            if (!appleTouchIcon) {
                const link = document.createElement('link');
                link.rel = 'apple-touch-icon';
                link.href = '/img/logo.png';
                link.sizes = '180x180';
                document.head.appendChild(link);
            }
            
            // Meta pour iOS
            const metas = [
                { name: 'apple-mobile-web-app-capable', content: 'yes' },
                { name: 'apple-mobile-web-app-status-bar-style', content: 'default' },
                { name: 'apple-mobile-web-app-title', content: 'JB Shop' }
            ];
            
            metas.forEach(meta => {
                if (!document.querySelector(`meta[name="${meta.name}"]`)) {
                    const metaTag = document.createElement('meta');
                    metaTag.name = meta.name;
                    metaTag.content = meta.content;
                    document.head.appendChild(metaTag);
                }
            });
        }
    }
    
    // Ajouter un bouton d'installation dans le menu (si non mobile)
    function addInstallButtonToMenu() {
        if (window.matchMedia('(display-mode: standalone)').matches) {
            console.log('[PWA] Already installed, no install button needed');
            return;
        }
        
        // Seulement sur desktop
        if (!/Mobi|Android/i.test(navigator.userAgent)) {
            const nav = document.querySelector('.navbar');
            if (nav && window.PWA && window.PWA.install) {
                const installBtn = document.createElement('button');
                installBtn.className = 'btn btn-outline-primary btn-sm ms-2';
                installBtn.innerHTML = '<i class="fas fa-download me-1"></i>Installer';
                installBtn.onclick = window.PWA.install;
                installBtn.style.display = 'none';
                installBtn.id = 'desktop-install-btn';
                
                // Montrer seulement si installable
                window.addEventListener('beforeinstallprompt', () => {
                    installBtn.style.display = 'inline-block';
                });
                
                const navItems = nav.querySelector('.navbar-nav');
                if (navItems) {
                    const li = document.createElement('li');
                    li.className = 'nav-item';
                    li.appendChild(installBtn);
                    navItems.appendChild(li);
                }
            }
        }
    }
    
    // Styles pour le guide navigateur
    const styles = document.createElement('style');
    styles.textContent = `
        .browser-guide-modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.8);
            z-index: 999999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            opacity: 0;
            transition: opacity 0.3s;
        }
        
        .browser-guide-modal.show {
            opacity: 1;
        }
        
        .browser-guide-content {
            background: white;
            border-radius: 15px;
            max-width: 400px;
            width: 100%;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        }
        
        .browser-guide-header {
            background: linear-gradient(135deg, #ff7e00 0%, #ff9933 100%);
            color: white;
            padding: 20px;
            border-radius: 15px 15px 0 0;
            display: flex;
            align-items: center;
            gap: 10px;
            position: relative;
        }
        
        .browser-guide-header i.fa-info-circle {
            font-size: 24px;
        }
        
        .browser-guide-header h4 {
            margin: 0;
            flex: 1;
            font-size: 18px;
        }
        
        .browser-guide-close {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .browser-guide-body {
            padding: 25px;
        }
        
        .browser-guide-body p {
            color: #666;
            margin-bottom: 20px;
        }
        
        .browser-guide-steps {
            margin-bottom: 20px;
        }
        
        .guide-step {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            margin-bottom: 10px;
        }
        
        .step-number {
            background: #ff7e00;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            flex-shrink: 0;
        }
        
        .btn-understand {
            width: 100%;
            background: linear-gradient(135deg, #ff7e00 0%, #ff9933 100%);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 25px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        .btn-understand:hover {
            transform: translateY(-2px);
        }
    `;
    document.head.appendChild(styles);
    
    // Initialisation au chargement
    window.addEventListener('load', () => {
        // Vérifier la compatibilité
        const pwaStatus = checkPWACompatibility();
        console.log('[PWA Enhanced] Status:', pwaStatus);
        
        // Améliorer les icônes
        enhanceManifestIcons();
        
        // Afficher le guide si dans un navigateur in-app
        setTimeout(() => {
            showOpenInBrowserGuide();
        }, 2000);
        
        // Ajouter bouton d'installation sur desktop
        addInstallButtonToMenu();
    });
    
    // Exposer les fonctions utiles
    window.PWAEnhanced = {
        isWhatsApp: isWhatsAppBrowser,
        isSocial: isSocialBrowser,
        getBrowser: getMobileBrowser,
        checkCompatibility: checkPWACompatibility,
        showGuide: showOpenInBrowserGuide
    };
    
    console.log('[PWA Enhanced] Module loaded');
    
})();
