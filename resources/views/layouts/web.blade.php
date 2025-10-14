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

    // Handle cart for authenticated users only
    $carts = Auth::check() ? App\Models\Cart::where('user_id', Auth::id())->get() : collect();
    $totalCartPrice = $carts->sum(function($cart) {
        return optional($cart->offer)->price * $cart->quantity;
    });
@endphp
<!DOCTYPE html>
<html lang="fr"> <head>
        <meta charset="utf-8">
        <title>JB-Commerce -  Produit Vente en ligne</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="" name="keywords">
        <meta content="" name="description">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet"> 

        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

        <link href="{{ asset('lib/animate/animate.min.css') }}" rel="stylesheet">
        <link href="{{ asset('lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">

        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
        </head>


    <body>

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
                                <a href="#" class="dropdown-toggle text-muted ms-2" data-bs-toggle="dropdown"><small><i class="fa fa-user me-2"></i> Mon Compte</small></a> <div class="dropdown-menu rounded">
                                    <form action="{{ route('logout') }}" method="POST" class="dropdown-item">
                                        @csrf
                                        <button type="submit" style="background:none; border:none; padding:0; color:inherit;">Déconnexion</button> </form>
                                </div>
                            @else
                                <a href={{ route("login")}} data-bs-toggle="modal" class="text-muted ms-2"><small><i class="fa fa-user me-2"></i> Connexion / Inscription</small></a> @endauth
                        </div>
                    </div>
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
                        <div class="wishlist">
                            @if(Auth::check())
                                <a href="{{ url('/wish-list/' . Auth::id()) }}" class="text-muted d-flex align-items-center justify-content-center me-3" title="Liste de souhaits"> 
                                    <span class="rounded-circle btn-md-square border"><i class="fas fa-heart"></i></span>
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="text-muted d-flex align-items-center justify-content-center me-3" title="Liste de souhaits"> 
                                    <span class="rounded-circle btn-md-square border"><i class="fas fa-heart"></i></span>
                                </a>
                            @endif
                        </div>
                        {{-- Cart (Panier) --}}
                        <div class="cart">
                            @if(Auth::check())
                                <a href="{{ url('/cart/' . Auth::id()) }}" class="text-muted d-flex align-items-center justify-content-center" title="Panier"> 
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
                    <a href="{{ url('/cart/' . (Auth::id() ?? '')) }}" class="text-center flex-fill nav-tab {{ request()->is('cart*') ? 'active' : '' }}">
                        <span class="d-block"><i class="fas fa-shopping-cart fa-lg"></i></span>
                        <small>Panier</small>
                    </a>
                    <a href="{{ url('/wish-list/' . (Auth::id() ?? '')) }}" class="text-center flex-fill nav-tab {{ request()->is('wish-list*') ? 'active' : '' }}">
                        <span class="d-block"><i class="fas fa-heart fa-lg"></i></span>
                        <small>Souhaits</small>
                    </a>
                </div>
            </nav>
        @endif

<div class="container-fluid footer py-5 wow fadeIn" data-wow-delay="0.2s">
            <div class="container py-5">
                <div class="row g-4 rounded mb-5" style="background: rgba(255, 255, 255, .03);">
                    {{-- Contact Info Section --}}
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="rounded p-4">
                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center mb-4" style="width: 70px; height: 70px;">
                                <i class="fas fa-map-marker-alt fa-2x text-primary"></i>
                            </div>
                            <div>
                                <h4 class="text-white">Adresse</h4> <p class="mb-2">Bafoussam, Cameroun</p> </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="rounded p-4">
                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center mb-4" style="width: 70px; height: 70px;">
                                <i class="fas fa-envelope fa-2x text-primary"></i>
                            </div>
                            <div>
                                <h4 class="text-white">Envoyez-nous</h4> <p class="mb-2">{{$email}}</p> </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="rounded p-4">
                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center mb-4" style="width: 70px; height: 70px;">
                                <i class="fa fa-phone-alt fa-2x text-primary"></i>
                            </div>
                            <div>
                                <h4 class="text-white">Téléphone</h4> <p class="mb-2">{{ $phone1 }} / {{$phone2}}</p> </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="rounded p-4">
                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center mb-4" style="width: 70px; height: 70px;">
                                <i class="fab fa-firefox-browser fa-2x text-primary"></i>
                            </div>
                            <div>
                                <h4 class="text-white">Site Web</h4> <p class="mb-2">www.example.com</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row g-5">
                    {{-- Newsletter Section --}}
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="footer-item d-flex flex-column">
                            <div class="footer-item">
                                <h4 class="text-primary mb-4">Newsletter</h4>
                                <p class="mb-3">Abonnez-vous pour recevoir les dernières offres, produits et actualités d'ElectroSphere.</p> <div class="position-relative mx-auto rounded-pill">
                                    <input class="form-control rounded-pill w-100 py-3 ps-4 pe-5" type="text" placeholder="Entrez votre e-mail"> <button type="button" class="btn btn-primary rounded-pill position-absolute top-0 end-0 py-2 mt-2 me-2">S'abonner</button> </div>
                            </div>
                        </div>
                    </div>

                    {{-- Customer Service Section --}}
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="footer-item d-flex flex-column">
                            <h4 class="text-primary mb-4">Service Client</h4> <a href="{{ url('/contact') }}" class=""><i class="fas fa-angle-right me-2"></i> Contactez-nous</a> <a href="#" class=""><i class="fas fa-angle-right me-2"></i> Retours</a> <a href="#" class=""><i class="fas fa-angle-right me-2"></i> Historique des commandes</a> <a href="#" class=""><i class="fas fa-angle-right me-2"></i> Plan du site</a> <a href="#" class=""><i class="fas fa-angle-right me-2"></i> Témoignages</a> <a href="#" class=""><i class="fas fa-angle-right me-2"></i> Mon Compte</a> <a href="#" class=""><i class="fas fa-angle-right me-2"></i> Notifications</a> </div>
                    </div>

                    {{-- Information Section --}}
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="footer-item d-flex flex-column">
                            <h4 class="text-primary mb-4">Informations</h4> <a href="{{ url('/about') }}" class=""><i class="fas fa-angle-right me-2"></i> À Propos de Nous</a> <a href="#" class=""><i class="fas fa-angle-right me-2"></i> Informations de livraison</a> <a href="#" class=""><i class="fas fa-angle-right me-2"></i> Politique de Confidentialité</a> <a href="#" class=""><i class="fas fa-angle-right me-2"></i> Termes & Conditions</a> <a href="#" class=""><i class="fas fa-angle-right me-2"></i> Garantie</a> <a href="#" class=""><i class="fas fa-angle-right me-2"></i> FAQ</a>
                            <a href="#signin-modal" data-bs-toggle="modal" class=""><i class="fas fa-angle-right me-2"></i> Connexion Vendeur</a> </div>
                    </div>

                    {{-- Extras/My Account Section (Using logic from the first layout) --}}
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="footer-item d-flex flex-column">
                            <h4 class="text-primary mb-4">Mon Compte</h4> {{-- View Cart --}}
                            @if (Auth::check())
                                <a href="{{ url('/cart/' . Auth::id()) }}" class=""><i class="fas fa-angle-right me-2"></i> Voir Panier</a> @else
                                <a href='#' onclick="alert('Veuillez vous connecter pour accéder à cette page')" class=""><i class="fas fa-angle-right me-2"></i> Voir Panier</a> @endif

                            {{-- My Wishlist --}}
                            @if (Auth::check())
                                <a href="{{ url('/wish-list/' . Auth::id()) }}" class=""><i class="fas fa-angle-right me-2"></i> Ma Liste de Souhaits</a> @else
                                <a href='#' onclick="alert('Veuillez vous connecter pour accéder à cette page')" class=""><i class="fas fa-angle-right me-2"></i> Ma Liste de Souhaits</a> @endif

                            <a href="#" class=""><i class="fas fa-angle-right me-2"></i> Marques</a> <a href="#" class=""><i class="fas fa-angle-right me-2"></i> Bons Cadeaux</a> <a href="#" class=""><i class="fas fa-angle-right me-2"></i> Affiliés</a> <a href="#" class=""><i class="fas fa-angle-right me-2"></i> Suivre Votre Commande</a> </div>
                    </div>
                </div>
            </div>
        </div>
        ---
        
<div class="container-fluid copyright py-4">
            <div class="container">
                <div class="row g-4 align-items-center">
                    <div class="col-md-6 text-center text-md-start mb-md-0">
                        <span class="text-white">
                            <a href="{{ url('/') }}" class="border-bottom text-white">
                                <i class="fas fa-copyright text-light me-2"></i>ElectroSphere
                            </a>. Tous droits réservés.
                        </span> </div>
                    <div class="col-md-6 text-center text-md-end text-white">
                        Conçu par <a class="border-bottom text-white" href="https://htmlcodex.com">HTML Codex</a> </div>
                </div>
            </div>
        </div>

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
    </style>
    <script>
        // Add home-page class to body if on home
        if (window.location.pathname != '/hey' || window.location.pathname === '/home') {
            document.body.classList.add('home-page');
        }
    </script>
    <!-- Tailwind CSS via CDN -->
    <script>
        tailwind.config = {
            theme: {
            extend: {
                colors: {
                brand: '#ff7e00', // ElectroSphere orange
                }
            }
            }
        }
    </script>


</body>

</html>