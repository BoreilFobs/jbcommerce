/**
 * Lazy Loading Implementation for JB Commerce
 * Optimizes page load performance by deferring image and background loading
 */

(function() {
    'use strict';

    // Configuration
    const CONFIG = {
        rootMargin: '50px 0px', // Start loading 50px before entering viewport
        threshold: 0.01,
        loadingClass: 'lazy-loading',
        loadedClass: 'lazy-loaded',
        errorClass: 'lazy-error'
    };

    /**
     * Lazy load images with data-src attribute
     */
    function lazyLoadImages() {
        const images = document.querySelectorAll('img[data-src]:not(.lazy-loaded):not(.lazy-loading)');
        
        if (!images.length) return;

        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        loadImage(img);
                        observer.unobserve(img);
                    }
                });
            }, CONFIG);

            images.forEach(img => imageObserver.observe(img));
        } else {
            // Fallback for browsers without IntersectionObserver
            images.forEach(loadImage);
        }
    }

    /**
     * Load a single image
     */
    function loadImage(img) {
        img.classList.add(CONFIG.loadingClass);
        
        const src = img.dataset.src;
        const srcset = img.dataset.srcset;
        
        if (!src) return;

        // Create a temporary image to preload
        const tempImg = new Image();
        
        tempImg.onload = () => {
            img.src = src;
            if (srcset) img.srcset = srcset;
            img.classList.remove(CONFIG.loadingClass);
            img.classList.add(CONFIG.loadedClass);
            
            // Trigger custom event
            img.dispatchEvent(new CustomEvent('lazyloaded', { bubbles: true }));
        };
        
        tempImg.onerror = () => {
            img.classList.remove(CONFIG.loadingClass);
            img.classList.add(CONFIG.errorClass);
            // Set fallback image
            img.src = img.dataset.fallback || '/img/default-product.jpg';
        };
        
        tempImg.src = src;
    }

    /**
     * Lazy load background images with data-bg attribute
     */
    function lazyLoadBackgrounds() {
        const backgrounds = document.querySelectorAll('[data-bg]:not(.lazy-loaded):not(.lazy-loading)');
        
        if (!backgrounds.length) return;

        if ('IntersectionObserver' in window) {
            const bgObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const element = entry.target;
                        loadBackground(element);
                        observer.unobserve(element);
                    }
                });
            }, CONFIG);

            backgrounds.forEach(bg => bgObserver.observe(bg));
        } else {
            backgrounds.forEach(loadBackground);
        }
    }

    /**
     * Load a single background image
     */
    function loadBackground(element) {
        element.classList.add(CONFIG.loadingClass);
        
        const bgUrl = element.dataset.bg;
        if (!bgUrl) return;

        // Preload the image
        const img = new Image();
        
        img.onload = () => {
            element.style.backgroundImage = `url('${bgUrl}')`;
            element.classList.remove(CONFIG.loadingClass);
            element.classList.add(CONFIG.loadedClass);
            
            // Remove data-bg attribute to prevent re-processing
            element.removeAttribute('data-bg');
            
            // Trigger custom event
            element.dispatchEvent(new CustomEvent('lazyloaded', { bubbles: true }));
        };
        
        img.onerror = () => {
            element.classList.remove(CONFIG.loadingClass);
            element.classList.add(CONFIG.errorClass);
            console.warn('Failed to load background:', bgUrl);
        };
        
        img.src = bgUrl;
    }

    /**
     * Lazy load iframes with data-src attribute
     */
    function lazyLoadIframes() {
        const iframes = document.querySelectorAll('iframe[data-src]:not(.lazy-loaded)');
        
        if (!iframes.length) return;

        if ('IntersectionObserver' in window) {
            const iframeObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const iframe = entry.target;
                        iframe.src = iframe.dataset.src;
                        iframe.classList.add(CONFIG.loadedClass);
                        observer.unobserve(iframe);
                    }
                });
            }, CONFIG);

            iframes.forEach(iframe => iframeObserver.observe(iframe));
        } else {
            iframes.forEach(iframe => {
                iframe.src = iframe.dataset.src;
                iframe.classList.add(CONFIG.loadedClass);
            });
        }
    }

    /**
     * Force load all lazy elements (useful for print or SEO)
     */
    function loadAll() {
        document.querySelectorAll('img[data-src]').forEach(loadImage);
        document.querySelectorAll('[data-bg]').forEach(loadBackground);
        document.querySelectorAll('iframe[data-src]').forEach(iframe => {
            iframe.src = iframe.dataset.src;
            iframe.classList.add(CONFIG.loadedClass);
        });
    }

    /**
     * Initialize lazy loading
     */
    function init() {
        // Initial load
        lazyLoadImages();
        lazyLoadBackgrounds();
        lazyLoadIframes();

        // Re-scan for new elements after DOM changes (e.g., AJAX content)
        if ('MutationObserver' in window) {
            const observer = new MutationObserver(() => {
                lazyLoadImages();
                lazyLoadBackgrounds();
                lazyLoadIframes();
            });

            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        }

        // Force load all on print
        window.addEventListener('beforeprint', loadAll);
        
        // Export to window for manual triggering
        window.lazyLoad = {
            init,
            loadAll,
            images: lazyLoadImages,
            backgrounds: lazyLoadBackgrounds,
            iframes: lazyLoadIframes
        };
    }

    // Auto-initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
