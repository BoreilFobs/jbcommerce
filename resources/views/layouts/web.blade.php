@php
    $categories = App\Models\Categorie::all();
    $offers = App\Models\offers::all()->shuffle();
    
    // Définition des numéros de téléphone
    $phone1 = '+237-657-528-859';
    $phone2 = '+237-693-662-715';
    
    // Nettoyage des numéros pour le lien 'tel:'
    $phone = str_replace('-', '', $phone1);
    $tel2 = str_replace('-', '', $phone2);
    $email = 'brayeljunior8@gmail.com';

    // Handle cart for authenticated users
    $carts = Auth::check() ? App\Models\Cart::where('user_id', Auth::id())->get() : collect();
    $totalCartPrice = $carts->sum(function($cart) {
        return optional($cart->offer)->price * $cart->quantity;
    });
@endphp
<!DOCTYPE html>
<html lang="fr"> <head>
        <meta charset="utf-8">
        <title>JB Shop - Boutique en Ligne à Bafoussam | Électronique, Smartphones & Accessoires</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        
        <!-- SEO Meta Tags -->
        <meta name="description" content="JB Shop - Votre boutique en ligne de confiance à Bafoussam, Cameroun. Découvrez une large gamme d'électronique, smartphones, ordinateurs, accessoires et plus encore. Livraison rapide et paiement sécurisé.">
        <meta name="keywords" content="JB Shop, boutique en ligne Cameroun, électronique Bafoussam, smartphones Cameroun, ordinateurs portables, accessoires électroniques, vente en ligne Bafoussam, e-commerce Cameroun, JB Commerce, produits électroniques, livraison Bafoussam">
        <meta name="author" content="JB Shop">
        <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
        <meta name="googlebot" content="index, follow">
        <meta name="bingbot" content="index, follow">
        
        <!-- Canonical URL -->
        <link rel="canonical" href="https://jbshop237.com">
        
        <!-- Open Graph / Facebook Meta Tags -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="https://jbshop237.com">
        <meta property="og:title" content="JB Shop - Boutique en Ligne à Bafoussam | Électronique & Accessoires">
        <meta property="og:description" content="Découvrez JB Shop, votre destination e-commerce de confiance à Bafoussam. Large sélection d'électronique, smartphones, ordinateurs et accessoires. Livraison rapide au Cameroun.">
        <meta property="og:image" content="{{ asset('icons/og-image.png') }}">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
        <meta property="og:site_name" content="JB Shop">
        <meta property="og:locale" content="fr_FR">
        
        <!-- Twitter Card Meta Tags -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:url" content="https://jbshop237.com">
        <meta name="twitter:title" content="JB Shop - Boutique en Ligne à Bafoussam">
        <meta name="twitter:description" content="Votre boutique en ligne de confiance pour électronique, smartphones et accessoires au Cameroun.">
        <meta name="twitter:image" content="{{ asset('icons/og-image.png') }}">
        
        <!-- Geographic Meta Tags -->
        <meta name="geo.region" content="CM-OU">
        <meta name="geo.placename" content="Bafoussam">
        <meta name="geo.position" content="5.4781;10.4178">
        <meta name="ICBM" content="5.4781, 10.4178">
        
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <!-- Authentication Status (for FCM) -->
        <meta name="user-authenticated" content="{{ Auth::check() ? 'true' : 'false' }}">
        
        <!-- Business Meta Tags -->
        <meta name="contact" content="brayeljunior8@gmail.com">
        <meta name="telephone" content="+237657528859">
        <meta name="locality" content="Bafoussam, Cameroun">
        
        <!-- Language & Region -->
        <meta http-equiv="content-language" content="fr">
        <meta name="language" content="French">
        <meta name="country" content="Cameroon">

        <!-- Resource Hints for Performance -->
        <link rel="dns-prefetch" href="https://fonts.googleapis.com">
        <link rel="dns-prefetch" href="https://fonts.gstatic.com">
        <link rel="dns-prefetch" href="https://use.fontawesome.com">
        <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
        <link rel="dns-prefetch" href="https://www.gstatic.com">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        
        <!-- Optimized Font Loading with font-display: swap -->
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
        <noscript><link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet"></noscript> 

        <!-- Critical CSS (inline or load immediately) -->
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
        <link href="{{ asset('css/performance.css') }}" rel="stylesheet">
        <link href="{{ asset('css/pwa-install.css') }}" rel="stylesheet">
        
        <!-- Deferred Non-Critical CSS -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" media="print" onload="this.media='all'"/>
        <noscript><link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/></noscript>
        
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet" media="print" onload="this.media='all'">
        <noscript><link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet"></noscript>

        <link href="{{ asset('lib/animate/animate.min.css') }}" rel="stylesheet" media="print" onload="this.media='all'">
        <noscript><link href="{{ asset('lib/animate/animate.min.css') }}" rel="stylesheet"></noscript>
        
        <link href="{{ asset('lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet" media="print" onload="this.media='all'">
        <noscript><link href="{{ asset('lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet"></noscript>

        <link href="{{ asset('css/store-filters.css') }}" rel="stylesheet" media="print" onload="this.media='all'">
        <noscript><link href="{{ asset('css/store-filters.css') }}" rel="stylesheet"></noscript>
        
        <link href="{{ asset('css/mobile-responsive.css') }}" rel="stylesheet" media="print" onload="this.media='all'">
        <noscript><link href="{{ asset('css/mobile-responsive.css') }}" rel="stylesheet"></noscript>
        
        <link href="{{ asset('css/related-products.css') }}" rel="stylesheet" media="print" onload="this.media='all'">
        <noscript><link href="{{ asset('css/related-products.css') }}" rel="stylesheet"></noscript>
        
        <!-- Mobile Optimization -->
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="theme-color" content="#ff7e00">
        
        <!-- PWA Manifest -->
        <link rel="manifest" href="{{ asset('manifest.json') }}">
        
        <!-- Favicon & App Icons -->
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('icons/favicon-16x16.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('icons/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="48x48" href="{{ asset('icons/favicon-48x48.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('icons/apple-touch-icon.png') }}">
        <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('icons/apple-touch-icon-120x120.png') }}">
        <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('icons/apple-touch-icon-152x152.png') }}">
        <link rel="apple-touch-icon" sizes="167x167" href="{{ asset('icons/apple-touch-icon-167x167.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('icons/apple-touch-icon-180x180.png') }}">
        
        <!-- iOS Meta Tags -->
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="JB Shop">
        
        <!-- Android/Chrome Meta Tags -->
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="application-name" content="JB Shop">
        
        <!-- Microsoft Tiles -->
        <meta name="msapplication-TileColor" content="#ff7e00">
        <meta name="msapplication-TileImage" content="{{ asset('icons/icon-144x144.png') }}">
        <meta name="msapplication-config" content="{{ asset('browserconfig.xml') }}">
        
        <!-- Additional SEO Links -->
        <link rel="author" href="{{ asset('humans.txt') }}">
        
        <!-- Structured Data / JSON-LD for SEO -->
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Store",
            "name": "JB Shop",
            "image": "{{ asset('icons/icon-512x512.png') }}",
            "description": "Boutique en ligne spécialisée dans la vente d'électronique, smartphones, ordinateurs et accessoires à Bafoussam, Cameroun.",
            "address": {
                "@type": "PostalAddress",
                "streetAddress": "Bafoussam",
                "addressLocality": "Bafoussam",
                "addressRegion": "Ouest",
                "postalCode": "",
                "addressCountry": "CM"
            },
            "geo": {
                "@type": "GeoCoordinates",
                "latitude": "5.4781",
                "longitude": "10.4178"
            },
            "telephone": "+237657528859",
            "email": "brayeljunior8@gmail.com",
            "url": "https://jbshop237.com",
            "priceRange": "FCFA",
            "openingHoursSpecification": {
                "@type": "OpeningHoursSpecification",
                "dayOfWeek": [
                    "Monday",
                    "Tuesday",
                    "Wednesday",
                    "Thursday",
                    "Friday",
                    "Saturday"
                ],
                "opens": "08:00",
                "closes": "18:00"
            },
            "sameAs": [
                "https://www.facebook.com/jbshop",
                "https://www.instagram.com/jbshop"
            ]
        }
        </script>
        </head>


    <body>
        <!-- Page Refresh After Login/Logout -->
        @if(session('refresh_page'))
        <script>
            // Force page refresh after login/logout
            if (!sessionStorage.getItem('page_refreshed')) {
                sessionStorage.setItem('page_refreshed', 'true');
                window.location.reload(true);
            } else {
                sessionStorage.removeItem('page_refreshed');
            }
        </script>
        @endif
        
        <!-- Connection Status Component -->
        @include('components.connection-status')

        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Chargement...</span> </div>
        </div>
        <div class="container-fluid px-5 d-none border-bottom d-lg-block">
            <div class="row gx-0 align-items-center">
                <div class="col-lg-4 text-center text-lg-start mb-lg-0">
                    <div class="d-inline-flex align-items-center" style="height: 45px;">
                        <a href="tel:{{$phone}}" class="text-muted"><i class="fa fa-phone me-1"></i>Appelez: {{ $phone1 }} / {{$phone2}}</a> </div>
                </div>
                <div class="col-lg-4 text-center d-flex align-items-center justify-content-center">
                    <small class="text-dark">Appelez-nous:</small> <a href="tel:{{$phone}}" class="text-muted">{{ $phone1 }} / {{$phone2}}</a>
                </div>

                <div class="col-lg-4 text-center text-lg-end">
                    <div class="d-inline-flex align-items-center" style="height: 45px;">
                        {{-- Currency Dropdown: Use FCFA (Franc CFA) --}}
                        <div class="dropdown">
                            <a href="#" class="dropdown-toggle text-muted me-2" data-bs-toggle="dropdown"><small> FCFA</small></a>
                            {{-- <div class="dropdown-menu rounded">
                                <a href="#" class="dropdown-item"> FCFA</a>
                                <a href="#" class="dropdown-item"> FCFA</a>
                            </div> --}}
                        </div>
                        {{-- Language Dropdown: Default to French --}}
                        <div class="dropdown">
                            <a href="#" class="dropdown-toggle text-muted mx-2" data-bs-toggle="dropdown"><small> Français</small></a> <div class="dropdown-menu rounded">
                                <a href="#" class="dropdown-item"> Français</a>
                                <a href="#" class="dropdown-item"> Anglais</a> </div>
                        </div>
                        {{-- Auth/Logout Dropdown --}}
                        <div class="dropdown">
                            @auth
                                <a href="#" class="dropdown-toggle text-muted ms-2" data-bs-toggle="dropdown"><small><i class="fa fa-user me-2"></i> Mon Compte</small></a> 
                                <div class="dropdown-menu rounded">
                                    <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                        <i class="fa fa-user-circle me-2"></i>Profil
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <form action="{{ route('logout') }}" method="POST" class="dropdown-item">
                                        @csrf
                                        <button type="submit" style="background:none; border:none; padding:0; color:inherit; width:100%; text-align:left;">
                                            <i class="fa fa-sign-out-alt me-2"></i>Déconnexion
                                        </button>
                                    </form>
                                </div>
                            @else
                                <a href="{{ route('login') }}" data-bs-toggle="modal" class="text-muted ms-2"><small><i class="fa fa-user me-2"></i> Connexion / Inscription</small></a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Mobile Header -->
        <div class="container-fluid px-3 py-3 d-lg-none bg-white border-bottom">
            <div class="row align-items-center">
                <div class="col-2">
                    <button class="btn btn-link p-0 text-dark" id="mobileNavToggle" aria-label="Menu">
                        <i class="fas fa-bars fa-2x"></i>
                    </button>
                </div>
                <div class="col-8 text-center">
                    <a href="{{ url('/') }}" class="navbar-brand p-0" style="font-family: cursive; font-weight:500;">
                        <h1 class="text-primary m-0" style="font-size: 24px;">
                            <i class="fas fa-shopping-bag text-secondary me-1"></i>JB Shop
                        </h1>
                    </a>
                </div>
                <div class="col-2 text-end">
                    <button class="btn btn-link p-0 text-dark" id="mobileSearchToggle" aria-label="Rechercher">
                        <i class="fas fa-search fa-lg"></i>
                    </button>
                </div>
            </div>
            
            <!-- Mobile Search Bar (Hidden by default) -->
            <div class="row mt-3 d-none" id="mobileSearchBar">
                <div class="col-12">
                    <form action="{{ route('search') }}" method="GET">
                        <div class="input-group">
                            <input class="form-control" type="text" name="search" placeholder="Rechercher un produit..." value="{{ request('search') }}">
                            <select class="form-select" name="category" style="max-width: 120px;">
                                <option value="">Toutes</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->name }}" {{ request('category') == $category->name ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Mobile Side Menu (Hidden by default) -->
        <div class="mobile-side-menu" id="mobileSideMenu">
            <div class="mobile-side-menu-overlay" id="mobileSideMenuOverlay"></div>
            <div class="mobile-side-menu-content">
                <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                    <h5 class="m-0">Menu</h5>
                    <button class="btn btn-link text-dark p-0" id="closeMobileMenu" aria-label="Fermer">
                        <i class="fas fa-times fa-2x"></i>
                    </button>
                </div>
                
                <!-- User Account Section -->
                <div class="p-3 border-bottom">
                    @auth
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-user-circle fa-2x text-primary me-2"></i>
                            <div>
                                <strong>{{ Auth::user()->name }}</strong>
                                <br><small class="text-muted">{{ Auth::user()->phone }}</small>
                            </div>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-primary w-100 mb-2">
                            <i class="fas fa-user-edit me-2"></i>Mon Compte
                        </a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary w-100">
                            <i class="fas fa-sign-in-alt me-2"></i>Connexion / Inscription
                        </a>
                    @endauth
                </div>
                
                <!-- Navigation Links -->
                <div class="mobile-menu-links">
                    <a href="{{ url('/') }}" class="mobile-menu-item {{ request()->is('/') ? 'active' : '' }}">
                        <i class="fas fa-home me-3"></i>Accueil
                    </a>
                    <a href="{{ url('/shop') }}" class="mobile-menu-item {{ request()->is('shop') ? 'active' : '' }}">
                        <i class="fas fa-store me-3"></i>Boutique
                    </a>
                    @if(Auth::check())
                        <a href="{{ route('cart.index') }}" class="mobile-menu-item {{ request()->is('cart*') ? 'active' : '' }}">
                            <i class="fas fa-shopping-cart me-3"></i>Mon Panier
                            <span class="badge bg-primary float-end">{{ $carts->sum('quantity') }}</span>
                        </a>
                        {{-- <a href="{{ url('/wish-list/' . Auth::id()) }}" class="mobile-menu-item {{ request()->is('wish-list*') ? 'active' : '' }}">
                            <i class="fas fa-heart me-3"></i>Liste de Souhaits
                        </a> --}}
                        <a href="{{ route('orders.index') }}" class="mobile-menu-item {{ request()->is('orders*') ? 'active' : '' }}">
                            <i class="fas fa-box me-3"></i>Mes Commandes
                        </a>
                    @endif
                    <a href="{{ url('/contact') }}" class="mobile-menu-item {{ request()->is('contact') ? 'active' : '' }}">
                        <i class="fas fa-envelope me-3"></i>Contact
                    </a>
                     <a href="{{ url('/track-order') }}" class="mobile-menu-item {{ request()->is('contact') ? 'active' : '' }}">
                        <i class="fas fa-envelope me-3"></i>Suivre Ma Comande
                    </a>
                    <a href="{{ url('/about') }}" class="mobile-menu-item {{ request()->is('about') ? 'active' : '' }}">
                        <i class="fas fa-info-circle me-3"></i>À Propos
                    </a>
                </div>
                
                <!-- Contact Info -->
                <div class="p-3 border-top mt-auto">
                    <small class="text-muted">
                        <i class="fas fa-phone me-2"></i>{{ $phone1 }}<br>
                        <i class="fas fa-envelope me-2"></i>{{ $email }}
                    </small>
                </div>
            </div>
        </div>

        <div class="container-fluid px-5 py-4 d-none d-lg-block">
            <div class="row gx-0 align-items-center text-center">
                <div class="col-md-4 col-lg-3 text-center text-lg-start">
                    <div class="d-inline-flex align-items-center">
                        <a href="{{ url('/') }}" class="navbar-brand p-0" style="font-family: cursive; font-weight:500; font-size: 35px;">
                            <h1 class="display-5 text-primary m-0" style="font-size: 35px;">
                                <i class="fas fa-shopping-bag text-secondary me-2"></i>JB Shop
                            </h1>
                        </a>
                        <nav style="position: relative; left:150px" class="ms-3 ps-3 d-flex align-items-center">
                            <a href="{{ url('/') }}"
                               class="text-muted d-flex align-items-center justify-content-center me-3 {{ request()->is('/') ? 'active' : '' }}"
                               title="Accueil"
                               style="text-decoration:none;">
                                <span class="rounded-circle btn-md-square border {{ request()->is('/') ? 'orange-active' : '' }}">
                                    <i class="fas fa-home fa-lg"></i>
                                </span>
                            </a>
                            <a href="{{ url('/shop') }}"
                               class="text-muted d-flex align-items-center justify-content-center me-3 {{ request()->is('shop') ? 'active' : '' }}"
                               title="Boutique"
                               style="text-decoration:none;">
                                <span class="rounded-circle btn-md-square border {{ request()->is('shop') ? 'orange-active' : '' }}">
                                    <i class="fas fa-store fa-lg"></i>
                                </span>
                            </a>
                            @auth
                            <a href="{{ route('orders.index') }}"
                               class="text-muted d-flex align-items-center justify-content-center me-3 {{ request()->is('orders*') ? 'active' : '' }}"
                               title="Mes Commandes"
                               style="text-decoration:none;">
                                <span class="rounded-circle btn-md-square border {{ request()->is('orders*') ? 'orange-active' : '' }}">
                                    <i class="fas fa-box fa-lg"></i>
                                </span>
                            </a>
                            @endauth
                            <a href="{{ url('/contact') }}"
                               class="text-muted d-flex align-items-center justify-content-center {{ request()->is('contact') ? 'active' : '' }}"
                               title="Contact"
                               style="text-decoration:none;">
                                <span class="rounded-circle btn-md-square border {{ request()->is('contact') ? 'orange-active' : '' }}">
                                    <i class="fas fa-envelope fa-lg"></i>
                                </span>
                            </a>
                        </nav>
                    </div>
                    <style>
                        .btn-md-square {
                            width: 40px;
                            height: 40px;
                            display: inline-flex;
                            align-items: center;
                            justify-content: center;
                            transition: border-color 0.2s, color 0.2s, background 0.2s;
                            background: #fff;
                        }
                        .btn-md-square:hover,
                        .btn-md-square:focus {
                            border-color: #ff7e00 !important;
                            color: #ff7e00 !important;
                            background: #fff;
                        }
                        .orange-active {
                            border-color: #ff7e00 !important;
                            color: #ff7e00 !important;
                        }
                    </style>
                </div>
                <div class="col-md-4 col-lg-6 text-center">
                    <div class="position-relative ps-4">
                        <form action="{{ route('search') }}" method="GET" class="d-flex border rounded-pill">
                            <input class="form-control border-0 rounded-pill w-100 py-3" type="text" name="search" placeholder="Rechercher un produit ..." value="{{ request('search') }}">
                            <select class="form-select text-dark border-0 border-start rounded-0 p-3" name="category" style="width: 200px;">
                                <option value="">Toutes Catégories</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->name }}" {{ request('category') == $category->name ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary rounded-pill py-3 px-5" style="border: 0;"><i class="fas fa-search"></i></button>
                        </form>
                    </div>
                </div>
                <div class="col-md-4 col-lg-3 text-center text-lg-end">
                    <div class="d-inline-flex align-items-center">
                        {{-- Wishlist (Liste de souhaits) --}}
                        {{-- <div class="wishlist">
                            @if(Auth::check())
                                <a href="{{ url('/wish-list/' . Auth::id()) }}" class="text-muted d-flex align-items-center justify-content-center me-3" title="Liste de souhaits"> 
                                    <span class="rounded-circle btn-md-square border"><i class="fas fa-heart"></i></span>
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="text-muted d-flex align-items-center justify-content-center me-3" title="Liste de souhaits"> 
                                    <span class="rounded-circle btn-md-square border"><i class="fas fa-heart"></i></span>
                                </a>
                            @endif
                        </div> --}}
                        {{-- Cart (Panier) --}}
                        <div class="cart">
                            @if(Auth::check())
                                <a href="{{ route('cart.index') }}" class="text-muted d-flex align-items-center justify-content-center" title="Panier"> 
                                    <span class="rounded-circle btn-md-square border"><i class="fas fa-shopping-cart"></i></span> 
                                    <span class="text-dark ms-2">{{number_format($totalCartPrice, 0, '.', ',') }} FCFA</span> 
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="text-muted d-flex align-items-center justify-content-center" title="Panier"> 
                                    <span class="rounded-circle btn-md-square border"><i class="fas fa-shopping-cart"></i></span> 
                                    <span class="text-dark ms-2">0 FCFA</span> 
                                </a>
                            @endif
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        
        


        <div class="px-1">
            @yield('content')
        </div>

        <!-- Mobile Bottom Navigation -->
        @if (!request()->is('login') && !request()->is('register'))
            <nav class="mobile-bottom-nav d-lg-none d-md-block d-sm-block d-xs-block fixed-bottom bg-white border-top shadow-lg" style="z-index:9999;">
                <div class="d-flex justify-content-around align-items-center py-2">
                    <a href="{{ url('/') }}" class="text-center flex-fill nav-tab {{ request()->is('/') ? 'active' : '' }}">
                        <span class="d-block"><i class="fas fa-home fa-lg"></i></span>
                        <small>Accueil</small>
                    </a>
                    <a href="{{ url('/shop') }}" class="text-center flex-fill nav-tab {{ request()->is('shop') ? 'active' : '' }}">
                        <span class="d-block"><i class="fas fa-store fa-lg"></i></span>
                        <small>Boutique</small>
                    </a>
                    @auth
                    <a href="{{ route('orders.index') }}" class="text-center flex-fill nav-tab {{ request()->is('orders*') ? 'active' : '' }}">
                        <span class="d-block"><i class="fas fa-box fa-lg"></i></span>
                        <small>Commandes</small>
                    </a>
                    @endauth
                    <a href="{{ route('cart.index') }}" class="text-center flex-fill nav-tab {{ request()->is('cart*') ? 'active' : '' }}">
                        <span class="d-block position-relative">
                            <i class="fas fa-shopping-cart fa-lg"></i>
                            @auth
                                @if($carts->sum('quantity') > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                                        {{ $carts->sum('quantity') }}
                                    </span>
                                @endif
                            @endauth
                        </span>
                        <small>Panier</small>
                    </a>
                    @auth
                        <a href="{{ route('profile.edit') }}" class="text-center flex-fill nav-tab {{ request()->is('profile*') ? 'active' : '' }}">
                            <span class="d-block"><i class="fas fa-user fa-lg"></i></span>
                            <small>Compte</small>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-center flex-fill nav-tab">
                            <span class="d-block"><i class="fas fa-user fa-lg"></i></span>
                            <small>Compte</small>
                        </a>
                    @endauth
                </div>
            </nav>
            
            <!-- PWA Install Button (Floating) -->
            <button id="pwa-install-button" class="pwa-install-fab" style="display: none;">
                <i class="fas fa-download"></i>
                <span class="pwa-install-text">Installer</span>
            </button>
        @endif

<!-- Minimalistic Footer Start -->
<div class="container-fluid footer py-5 wow fadeIn" data-wow-delay="0.2s">
    <div class="container">
        <!-- Main Footer Content -->
        <div class="row g-4 mb-4">
            <!-- Brand & Description Column -->
            <div class="col-lg-4 col-md-6">
                <div class="footer-item">
                    <h3 class="text-primary mb-3 fw-bold">JB Shop</h3>
                    <p class="text-white-50 mb-4">Votre destination de confiance pour les meilleurs produits électroniques et accessoires à Bafoussam, Cameroun.</p>
                    <!-- Social Media Icons -->
                    <div class="d-flex gap-3">
                        <a href="#" class="btn btn-primary btn-sm-square rounded-circle">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="btn btn-primary btn-sm-square rounded-circle">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="btn btn-primary btn-sm-square rounded-circle">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="btn btn-primary btn-sm-square rounded-circle">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Links Column -->
            <div class="col-lg-2 col-md-6">
                <div class="footer-item">
                    <h5 class="text-primary mb-3">Liens Rapides</h5>
                    <div class="d-flex flex-column">
                        <a href="{{ url('/') }}" class="text-white-50 mb-2 footer-link"><i class="fas fa-angle-right me-2"></i>Accueil</a>
                        <a href="{{ route('shop') }}" class="text-white-50 mb-2 footer-link"><i class="fas fa-angle-right me-2"></i>Boutique</a>
                        <a href="{{ url('/about') }}" class="text-white-50 mb-2 footer-link"><i class="fas fa-angle-right me-2"></i>À Propos</a>
                        <a href="{{ url('/contact') }}" class="text-white-50 mb-2 footer-link"><i class="fas fa-angle-right me-2"></i>Contact</a>
                    </div>
                </div>
            </div>

            <!-- Customer Service Column -->
            <div class="col-lg-3 col-md-6">
                <div class="footer-item">
                    <h5 class="text-primary mb-3">Service Client</h5>
                    <div class="d-flex flex-column">
                        @if (Auth::check())
                            <a href="{{ route('profile.edit') }}" class="text-white-50 mb-2 footer-link"><i class="fas fa-angle-right me-2"></i>Mon Compte</a>
                            <a href="{{ route('cart.index') }}" class="text-white-50 mb-2 footer-link"><i class="fas fa-angle-right me-2"></i>Mon Panier</a>
                            <a href="{{ url('/wish-list/' . Auth::id()) }}" class="text-white-50 mb-2 footer-link"><i class="fas fa-angle-right me-2"></i>Mes Favoris</a>
                            <a href="{{ route('orders.index') }}" class="text-white-50 mb-2 footer-link"><i class="fas fa-angle-right me-2"></i>Mes Commandes</a>
                        @else
                            <a href="{{ route('login') }}" class="text-white-50 mb-2 footer-link"><i class="fas fa-angle-right me-2"></i>Connexion</a>
                        @endif
                        <a href="{{ route('orders.track') }}" class="text-white-50 mb-2 footer-link"><i class="fas fa-angle-right me-2"></i>Suivre Commande</a>
                    </div>
                </div>
            </div>

            <!-- Contact Info Column -->
            <div class="col-lg-3 col-md-6">
                <div class="footer-item">
                    <h5 class="text-primary mb-3">Contact</h5>
                    <div class="d-flex flex-column text-white-50">
                        <p class="mb-2"><i class="fas fa-map-marker-alt text-primary me-2"></i>Bafoussam, Cameroun</p>
                        <p class="mb-2"><i class="fas fa-phone text-primary me-2"></i>{{ $phone1 }}</p>
                        <p class="mb-2"><i class="fas fa-envelope text-primary me-2"></i>{{ $email }}</p>
                        <p class="mb-0"><i class="fas fa-clock text-primary me-2"></i>Lun - Sam: 8h - 18h</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Divider -->
        <hr class="text-white-50 my-4">

        <!-- Bottom Footer Row -->
        <!-- Divider -->
        <hr class="text-white-50 my-4">

        <!-- Bottom Footer Row -->
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <span class="text-white-50">
                    <i class="fas fa-copyright text-primary me-2"></i>
                    <strong class="text-white">JB Shop {{ date('Y') }}</strong> - Tous droits réservés
                </span>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <div class="d-flex justify-content-center justify-content-md-end gap-3">
                    <a href="#" class="text-white-50 footer-link">Politique de Confidentialité</a>
                    <span class="text-white-50">|</span>
                    <a href="#" class="text-white-50 footer-link">Termes & Conditions</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Minimalistic Footer End -->

        <a href="#" class="btn btn-primary btn-lg-square back-to-top"><i class="fa fa-arrow-up"></i></a>
        
    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <style>
            /* Glassy effect for JB Shop logo */
            .jbshop-glass {
                background: rgba(255,255,255,0.25);
                border-radius: 12px;
                box-shadow: 0 4px 24px rgba(0,0,0,0.08);
                backdrop-filter: blur(4px);
                padding: 0.25em 0.75em;
                color: #0d6efd !important;
                font-family: cursive;
                font-weight: 500;
                letter-spacing: 1px;
            }
        
        /* Minimalistic Footer Styles */
        .footer-link {
            transition: all 0.3s ease;
        }
        .footer-link:hover {
            color: #ff7e00 !important;
            padding-left: 5px;
        }
        .btn-sm-square {
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Mobile bottom nav styles */
        .mobile-bottom-nav {
            box-shadow: 0 -2px 10px rgba(0,0,0,0.08);
        }
        .mobile-bottom-nav .nav-tab {
            color: #333;
            text-decoration: none;
            font-size: 13px;
        }
        .mobile-bottom-nav .nav-tab.active,
        .mobile-bottom-nav .nav-tab:active {
            color: #ff7e00;
        }
        /* Reduce text size for home page */
        body.home-page, .home-page * {
            font-size: 0.95rem !important;
        }
        @media (max-width: 576px) {
            body.home-page, .home-page * {
                font-size: 0.85rem !important;
            }
        }
        
        /* Mobile Side Menu Styles */
        .mobile-side-menu {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 99999;
            pointer-events: none;
        }
        
        .mobile-side-menu-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .mobile-side-menu-content {
            position: absolute;
            top: 0;
            left: -100%;
            width: 85%;
            max-width: 320px;
            height: 100%;
            background: white;
            transition: left 0.3s ease;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }
        
        .mobile-side-menu.active {
            pointer-events: auto;
        }
        
        .mobile-side-menu.active .mobile-side-menu-overlay {
            opacity: 1;
        }
        
        .mobile-side-menu.active .mobile-side-menu-content {
            left: 0;
        }
        
        .mobile-menu-item {
            display: block;
            padding: 1rem 1.5rem;
            color: #333;
            text-decoration: none;
            border-bottom: 1px solid #eee;
            transition: all 0.2s;
        }
        
        .mobile-menu-item:hover,
        .mobile-menu-item.active {
            background: #f8f9fa;
            color: #f28b00;
            padding-left: 2rem;
        }
        
        .mobile-menu-item i {
            width: 20px;
            text-align: center;
        }
        
        /* Add padding for mobile bottom nav */
        @media (max-width: 991px) {
            .px-1 {
                padding-bottom: 70px !important;
            }
        }
    </style>
    <script>
        // Add home-page class to body if on home
        if (window.location.pathname != '/hey' || window.location.pathname === '/home') {
            document.body.classList.add('home-page');
        }
        
        // Mobile Navigation
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile nav toggle
            const mobileNavToggle = document.getElementById('mobileNavToggle');
            const mobileSideMenu = document.getElementById('mobileSideMenu');
            const closeMobileMenu = document.getElementById('closeMobileMenu');
            const mobileSideMenuOverlay = document.getElementById('mobileSideMenuOverlay');
            
            if (mobileNavToggle) {
                mobileNavToggle.addEventListener('click', function() {
                    mobileSideMenu.classList.add('active');
                    document.body.style.overflow = 'hidden';
                });
            }
            
            if (closeMobileMenu) {
                closeMobileMenu.addEventListener('click', function() {
                    mobileSideMenu.classList.remove('active');
                    document.body.style.overflow = '';
                });
            }
            
            if (mobileSideMenuOverlay) {
                mobileSideMenuOverlay.addEventListener('click', function() {
                    mobileSideMenu.classList.remove('active');
                    document.body.style.overflow = '';
                });
            }
            
            // Mobile search toggle
            const mobileSearchToggle = document.getElementById('mobileSearchToggle');
            const mobileSearchBar = document.getElementById('mobileSearchBar');
            
            if (mobileSearchToggle && mobileSearchBar) {
                mobileSearchToggle.addEventListener('click', function() {
                    mobileSearchBar.classList.toggle('d-none');
                    if (!mobileSearchBar.classList.contains('d-none')) {
                        mobileSearchBar.querySelector('input').focus();
                    }
                });
            }
            
            // Close mobile menu on window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 991) {
                    mobileSideMenu.classList.remove('active');
                    document.body.style.overflow = '';
                }
            });
            
            // Smooth scroll for all anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    const href = this.getAttribute('href');
                    if (href !== '#' && document.querySelector(href)) {
                        e.preventDefault();
                        document.querySelector(href).scrollIntoView({
                            behavior: 'smooth'
                        });
                    }
                });
            });
            
            // Add active class to current nav items
            const currentPath = window.location.pathname;
            document.querySelectorAll('.mobile-menu-item').forEach(item => {
                if (item.getAttribute('href') === currentPath) {
                    item.classList.add('active');
                }
            });
            
            // Prevent iOS zoom on input focus
            if (/iPhone|iPad|iPod/.test(navigator.userAgent)) {
                const viewportmeta = document.querySelector('meta[name="viewport"]');
                if (viewportmeta) {
                    viewportmeta.content = 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0';
                    document.body.addEventListener('blur', function() {
                        viewportmeta.content = 'width=device-width, initial-scale=1.0';
                    }, true);
                }
            }
            
            // Add touch feedback
            document.querySelectorAll('.btn, .mobile-menu-item, .nav-tab').forEach(element => {
                element.addEventListener('touchstart', function() {
                    this.style.opacity = '0.7';
                }, { passive: true });
                
                element.addEventListener('touchend', function() {
                    this.style.opacity = '1';
                }, { passive: true });
            });
        });
    </script>
    <!-- Tailwind CSS via CDN -->
    <script>
        tailwind.config = {
            theme: {
            extend: {
                colors: {
                brand: '#ff7e00', // JB Shop orange
                }
            }
            }
        }
    </script>

    <!-- Lazy Loading (Load Early for Image Optimization) -->
    <script src="{{ asset('js/lazy-load.js') }}"></script>
    
    <!-- PWA Initialization -->
        <script src="{{ asset('js/pwa-init.js') }}?v=2.3.0" defer></script>    <!-- PWA Enhanced Features (WhatsApp, In-app browsers support) -->
    <script src="{{ asset('js/pwa-enhanced.js') }}" defer></script>

    <!-- Firebase Cloud Messaging -->
    @auth
    <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-app-compat.js" defer></script>
    <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-messaging-compat.js" defer></script>
    <script src="{{ asset('js/fcm-init.js') }}" defer></script>
    @endauth

</body>

</html>