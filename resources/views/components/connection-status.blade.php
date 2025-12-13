<!-- Connection Status Banner -->
<div id="connection-banner" class="connection-banner" style="display: none;">
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-center py-2">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <span id="connection-message">Pas de connexion Internet</span>
        </div>
    </div>
</div>

<!-- Offline Section Overlay for Dynamic Content -->
<div id="offline-overlay" class="offline-overlay" style="display: none;">
    <div class="offline-content">
        <i class="fas fa-wifi-slash mb-3"></i>
        <h5>Connexion Internet Requise</h5>
        <p>Cette section nécessite une connexion Internet active.</p>
        <button onclick="window.location.reload()" class="btn btn-primary btn-sm">
            <i class="fas fa-sync-alt me-2"></i>Réessayer
        </button>
    </div>
</div>

<style>
    .connection-banner {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 9999;
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
        font-weight: 500;
        font-size: 14px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        animation: slideDown 0.3s ease-out;
    }
    
    .connection-banner.online {
        background: linear-gradient(135deg, #28a745 0%, #218838 100%);
    }
    
    @keyframes slideDown {
        from {
            transform: translateY(-100%);
        }
        to {
            transform: translateY(0);
        }
    }
    
    @keyframes slideUp {
        from {
            transform: translateY(0);
        }
        to {
            transform: translateY(-100%);
        }
    }
    
    .offline-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.95);
        z-index: 9998;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .offline-content {
        text-align: center;
        padding: 40px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        max-width: 400px;
    }
    
    .offline-content i {
        font-size: 60px;
        color: #dc3545;
    }
    
    .offline-content h5 {
        margin: 20px 0 10px;
        color: #333;
    }
    
    .offline-content p {
        color: #666;
        margin-bottom: 20px;
    }
    
    /* Adjust body padding when banner is shown */
    body.connection-banner-visible {
        padding-top: 50px;
    }
    
    /* Loading indicator for dynamic sections */
    .loading-section {
        position: relative;
        min-height: 200px;
    }
    
    .loading-section::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 40px;
        height: 40px;
        border: 4px solid #f3f3f3;
        border-top: 4px solid #f28b00;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: translate(-50%, -50%) rotate(0deg); }
        100% { transform: translate(-50%, -50%) rotate(360deg); }
    }
</style>

<script>
    // Connection Status Manager
    class ConnectionManager {
        constructor() {
            this.isOnline = navigator.onLine;
            this.banner = document.getElementById('connection-banner');
            this.message = document.getElementById('connection-message');
            this.overlay = document.getElementById('offline-overlay');
            
            this.init();
        }
        
        init() {
            // Listen for online/offline events
            window.addEventListener('online', () => this.handleOnline());
            window.addEventListener('offline', () => this.handleOffline());
            
            // Check initial status
            if (!this.isOnline) {
                this.handleOffline();
            }
            
            // Monitor dynamic content sections
            this.monitorDynamicSections();
        }
        
        handleOnline() {
            this.isOnline = true;
            this.banner.classList.add('online');
            this.message.textContent = 'Connexion rétablie !';
            this.showBanner();
            
            // Hide overlay
            if (this.overlay) {
                this.overlay.style.display = 'none';
            }
            
            // Hide banner after 3 seconds
            setTimeout(() => {
                this.hideBanner();
            }, 3000);
            
            // Reload dynamic content
            this.reloadDynamicContent();
        }
        
        handleOffline() {
            this.isOnline = false;
            this.banner.classList.remove('online');
            this.message.textContent = 'Pas de connexion Internet - Mode Hors Ligne';
            this.showBanner();
            
            // Mark dynamic sections
            this.markDynamicSections();
        }
        
        showBanner() {
            if (this.banner) {
                this.banner.style.display = 'block';
                document.body.classList.add('connection-banner-visible');
            }
        }
        
        hideBanner() {
            if (this.banner) {
                this.banner.style.animation = 'slideUp 0.3s ease-out';
                setTimeout(() => {
                    this.banner.style.display = 'none';
                    this.banner.style.animation = '';
                    document.body.classList.remove('connection-banner-visible');
                }, 300);
            }
        }
        
        markDynamicSections() {
            // Mark sections that require internet
            const dynamicSections = document.querySelectorAll('[data-requires-connection="true"]');
            
            dynamicSections.forEach(section => {
                if (!this.isOnline) {
                    section.style.opacity = '0.5';
                    section.style.pointerEvents = 'none';
                    
                    // Add overlay message
                    const overlay = document.createElement('div');
                    overlay.className = 'dynamic-section-offline';
                    overlay.innerHTML = `
                        <div style="text-align: center; padding: 20px; background: rgba(255,255,255,0.95); border-radius: 10px;">
                            <i class="fas fa-wifi-slash" style="font-size: 40px; color: #dc3545; margin-bottom: 10px;"></i>
                            <p style="margin: 0; color: #666;">Connexion Internet requise</p>
                        </div>
                    `;
                    overlay.style.cssText = 'position: absolute; top: 0; left: 0; right: 0; bottom: 0; display: flex; align-items: center; justify-content: center; z-index: 100;';
                    
                    section.style.position = 'relative';
                    section.appendChild(overlay);
                }
            });
        }
        
        reloadDynamicContent() {
            // Remove offline overlays
            const overlays = document.querySelectorAll('.dynamic-section-offline');
            overlays.forEach(overlay => overlay.remove());
            
            // Restore dynamic sections
            const dynamicSections = document.querySelectorAll('[data-requires-connection="true"]');
            dynamicSections.forEach(section => {
                section.style.opacity = '1';
                section.style.pointerEvents = 'auto';
            });
        }
        
        monitorDynamicSections() {
            // Monitor AJAX calls and show loading states
            const originalFetch = window.fetch;
            
            window.fetch = async (...args) => {
                if (!navigator.onLine) {
                    throw new Error('No internet connection');
                }
                
                try {
                    return await originalFetch(...args);
                } catch (error) {
                    if (!navigator.onLine) {
                        this.handleOffline();
                    }
                    throw error;
                }
            };
        }
    }
    
    // Initialize connection manager when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            window.connectionManager = new ConnectionManager();
        });
    } else {
        window.connectionManager = new ConnectionManager();
    }
</script>
