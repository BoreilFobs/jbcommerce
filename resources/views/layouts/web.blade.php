<!DOCTYPE html>
<html lang="en">
<!-- ElectroSphere/index-3.html  22 Nov 2019 09:55:42 GMT -->
@php
    $phone = '+0123 456 789';
@endphp

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>ElectroSphere</title>
    <meta name="keywords" content="HTML5 Template" />
    <meta name="description" content="ElectroSphere" />
    <meta name="author" content="p-themes" />
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/icons/apple-touch-icon.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/icons/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/icons/favicon-16x16.png" />
    <link rel="manifest" href="assets/images/icons/site.html" />
    <link rel="mask-icon" href="assets/images/icons/safari-pinned-tab.svg" color="#666666" />
    <link rel="shortcut icon" href="assets/images/icons/favicon.ico" />
    <meta name="apple-mobile-web-app-title" content="ElectroSphere" />
    <meta name="application-name" content="ElectroSphere" />
    <meta name="msapplication-TileColor" content="#cc9966" />
    <meta name="msapplication-config" content="assets/images/icons/browserconfig.xml" />
    <meta name="theme-color" content="#ffffff" />
    <link rel="stylesheet" href="assets/vendor/line-awesome/line-awesome/line-awesome/css/line-awesome.min.css" />
    <!-- Plugins CSS File -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <link rel="stylesheet" href={{ asset('assets/css/bootstrap.min.css') }} />
    <link rel="stylesheet" href={{ asset('assets/css/plugins/owl-carousel/owl.carousel.css') }} />
    <link rel="stylesheet" href={{ asset('assets/css/plugins/magnific-popup/magnific-popup.css') }} />
    <link rel="stylesheet" href={{ asset('assets/css/plugins/jquery.countdown.css') }} />
    <!-- Main CSS File -->
    <link rel="stylesheet" href={{ asset('assets/css/style.css') }} />
    <link rel="stylesheet" href={{ asset('assets/css/skins/skin-demo-3.css') }} />
    <link rel="stylesheet" href={{ asset('assets/css/demos/demo-3.css') }} />
</head>

<body>
    <div class="page-wrapper">
        <header class="header header-intro-clearance header-3">
            <div class="header-top">
                <div class="container">
                    <div class="header-left">
                        <a href="tel:#"><i class="icon-phone"></i>Call: {{ $phone }}</a>
                    </div>
                    <!-- End .header-left -->

                    <div class="header-right">
                        <ul class="top-menu">
                            <li>
                                <a href="#">Links</a>
                                <ul>
                                    <li>
                                        <div class="header-dropdown">
                                            <a href="#">FCFA</a>
                                            <div class="header-menu">
                                                <ul>
                                                    <li>
                                                        <a href="#">FCFA</a>
                                                    </li>
                                                    <li>
                                                        <a href="#">FCFA</a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <!-- End .header-menu -->
                                        </div>
                                    </li>
                                    <li>
                                        <div class="header-dropdown">
                                            <a href="#">English</a>
                                            <div class="header-menu">
                                                <ul>
                                                    <li>
                                                        <a href="#">English</a>
                                                    </li>
                                                    <li>
                                                        <a href="#">French</a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <!-- End .header-menu -->
                                        </div>
                                    </li>
                                    <li>
                                        @auth
                                            <form action="{{ route('logout') }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                    style="background-color: transparent; border: none; color: orange; font-size: 16px; cursor: pointer;"
                                                    class="logout-btn">Logout</button>
                                            </form>
                                        @else
                                            <a href="#signin-modal" data-toggle="modal">Sign in / Sign up</a>
                                        @endauth
                                    </li>

                                </ul>
                            </li>
                        </ul>
                        <!-- End .top-menu -->
                    </div>
                    <!-- End .header-right -->
                </div>
                <!-- End .container -->
            </div>
            <!-- End .header-top -->

            <div class="header-middle">
                <div class="container">
                    <div class="header-left">
                        <button class="mobile-menu-toggler">
                            <span class="sr-only">Toggle mobile menu</span>
                            <i class="icon-bars"></i>
                        </button>

                        <a href={{ url('/') }} style="font-family: cursive; font-weight:500; font-size: 35px;"
                            class="logo">
                            {{-- <img src={{ asset('assets/images/demos/demo-3/logo.png') }} alt="ElectroSphere Logo"
                                width="105" height="25" /> --}}
                            ElectroSphere
                        </a>
                    </div>
                    <!-- End .header-left -->

                    <div class="header-center">
                        <div class="header-search header-search-extended header-search-visible d-none d-lg-block">
                            <a href="#" class="search-toggle" role="button"><i class="icon-search"></i></a>
                            <form action="#" method="get">
                                <div class="header-search-wrapper search-wrapper-wide">
                                    <label for="q" class="sr-only">Search</label>
                                    <button class="btn btn-primary" type="submit">
                                        <i class="icon-search"></i>
                                    </button>
                                    <input type="search" class="form-control" name="q" id="q"
                                        placeholder="Search product ..." required />
                                </div>
                                <!-- End .header-search-wrapper -->
                            </form>
                        </div>
                        <!-- End .header-search -->
                    </div>

                    <div class="header-right">
                        <div class="dropdown compare-dropdown">


                            <!-- End .dropdown-menu -->
                        </div>
                        <!-- End .compare-dropdown -->

                        <div class="wishlist">
                            @if (Auth::check())
                                <a href={{ url('/wish-list/' . Auth::id()) }} title="Wishlist">
                                    <div class="icon">
                                        <i class="icon-heart-o"></i>
                                        <span class="wishlist-count badge">3</span>
                                    </div>
                                    <p>Wishlist</p>
                                </a>
                            @else
                                <a href='#' title="Wishlist">
                                    <div onclick="()=>{alert('login to access this page')}" class="icon">
                                        <i class="icon-heart-o"></i>
                                        <span class="wishlist-count badge">3</span>
                                    </div>
                                    <p>Wishlist</p>
                                </a>
                            @endif
                        </div>
                        <!-- End .compare-dropdown -->

                        <div class="dropdown cart-dropdown">
                            @if (Auth::check())
                                <a href={{ url('/cart/' . Auth::id()) }} class="dropdown-toggle" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                    data-display="static">
                                    <div onclick="()=>{alert('login to access this page')}" class="icon">
                                        <i class="icon-shopping-cart"></i>
                                        <span class="cart-count">2</span>
                                    </div>
                                    <p>Cart</p>
                                </a>
                            @else
                                <a href='#' class="dropdown-toggle" role="button" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false" data-display="static">
                                    <div class="icon">
                                        <i class="icon-shopping-cart"></i>
                                        <span class="cart-count">2</span>
                                    </div>
                                    <p>Cart</p>
                                </a>
                            @endif

                            {{-- <div class="dropdown-menu dropdown-menu-right">
                                <div class="dropdown-cart-products">
                                    <div class="product">
                                        <div class="product-cart-details">
                                            <h4 class="product-title">
                                                <a href="product.html">Beige knitted elastic
                                                    runner shoes</a>
                                            </h4>

                                            <span class="cart-product-info">
                                                <span class="cart-product-qty">1</span>
                                                x $84.00
                                            </span>
                                        </div>
                                        <!-- End .product-cart-details -->

                                        <figure class="product-image-container">
                                            <a href="product.html" class="product-image">
                                                <img src="assets/images/products/cart/product-1.jpg" alt="product" />
                                            </a>
                                        </figure>
                                        <a href="#" class="btn-remove" title="Remove Product"><i
                                                class="icon-close"></i></a>
                                    </div>
                                    <!-- End .product -->

                                    <div class="product">
                                        <div class="product-cart-details">
                                            <h4 class="product-title">
                                                <a href="product.html">Blue utility pinafore
                                                    denim dress</a>
                                            </h4>

                                            <span class="cart-product-info">
                                                <span class="cart-product-qty">1</span>
                                                x $76.00
                                            </span>
                                        </div>
                                        <!-- End .product-cart-details -->

                                        <figure class="product-image-container">
                                            <a href="product.html" class="product-image">
                                                <img src="assets/images/products/cart/product-2.jpg" alt="product" />
                                            </a>
                                        </figure>
                                        <a href="#" class="btn-remove" title="Remove Product"><i
                                                class="icon-close"></i></a>
                                    </div>
                                    <!-- End .product -->
                                </div>
                                <!-- End .cart-product -->

                                <div class="dropdown-cart-total">
                                    <span>Total</span>

                                    <span class="cart-total-price">$160.00</span>
                                </div>
                                <!-- End .dropdown-cart-total -->

                                <div class="dropdown-cart-action">
                                    <a href="cart.html" class="btn btn-primary">View Cart</a>
                                    <a href="checkout.html" class="btn btn-outline-primary-2"><span>Checkout</span><i
                                            class="icon-long-arrow-right"></i></a>
                                </div>
                                <!-- End .dropdown-cart-total -->
                            </div> --}}
                            <!-- End .dropdown-menu -->
                        </div>
                        <!-- End .cart-dropdown -->
                    </div>
                    <!-- End .header-right -->
                </div>
                <!-- End .container -->
            </div>
            <!-- End .header-middle -->

            <div class="header-bottom sticky-header">
                <div class="container">
                    <!-- End .header-left -->

                    <div class="header-center">
                        <nav class="main-nav">
                            <ul class="menu sf-arrows">
                                <li class=" {{ request()->is('/') ? 'active' : '' }}">
                                    <a href={{ url('/') }}>Home</a>

                                    <!-- End .megamenu -->
                                </li>
                                <li class="{{ request()->is('/about') ? 'active' : '' }}">
                                    <a href={{ url('/about') }}>About us</a>
                                </li>
                                <li class=" {{ request()->is('/contact') ? 'active' : '' }}">
                                    <a href={{ url('/contact') }}>Contact us</a>
                                </li>
                            </ul>
                            <!-- End .menu -->
                        </nav>
                        <!-- End .main-nav -->
                    </div>
                    <!-- End .header-center -->

                    <div class="header-right">
                        <i class="la la-lightbulb-o"></i>
                        <p>
                            {{ Auth::check() ? Auth::user()->name : '' }}<span class="highlight">&nbsp;Up to 30%
                                Off</span>
                        </p>
                    </div>
                </div>
                <!-- End .container -->
            </div>
            <!-- End .header-bottom -->
        </header>
        <!-- End .header -->


        @yield('content')
        <footer class="footer">
            <div class="footer-middle">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-6 col-lg-3">
                            <div class="widget widget-about">
                                <img src="assets/images/demos/demo-3/logo-footer.png" class="footer-logo"
                                    alt="Footer Logo" width="105" height="25" />
                                <p>
                                    We offer high-quality products with exceptional
                                    customer service. Shop with confidence and enjoy
                                    our 30-day money-back guarantee.
                                </p>

                                <div class="widget-call">
                                    <i class="icon-phone"></i>
                                    Got Question? Call us 24/7
                                    <a href="tel:#">{{ $phone }}</a>
                                </div>
                                <!-- End .widget-call -->
                            </div>
                            <!-- End .widget about-widget -->
                        </div>
                        <!-- End .col-sm-6 col-lg-3 -->

                        <div class="col-sm-6 col-lg-3">
                            <div class="widget">
                                <h4 class="widget-title">Useful Links</h4>
                                <!-- End .widget-title -->

                                <ul class="widget-list">
                                    <li>
                                        <a href={{ url('/about') }}>About ElectroSphere</a>
                                    </li>
                                    <li><a href={{ url('/services') }}>Our Services</a></li>
                                    <li><a href="#">FAQ</a></li>
                                    <li>
                                        <a href={{ url('/contact') }}>Contact us</a>
                                    </li>
                                </ul>
                                <!-- End .widget-list -->
                            </div>
                            <!-- End .widget -->
                        </div>
                        <!-- End .col-sm-6 col-lg-3 -->

                        <div class="col-sm-6 col-lg-3">
                            <div class="widget">
                                <h4 class="widget-title">
                                    Customer Service
                                </h4>
                                <!-- End .widget-title -->

                                <ul class="widget-list">
                                    <li>
                                        <a href="#">Terms and conditions</a>
                                    </li>
                                    <li><a href="#">Privacy Policy</a></li>
                                </ul>
                                <!-- End .widget-list -->
                            </div>
                            <!-- End .widget -->
                        </div>
                        <!-- End .col-sm-6 col-lg-3 -->

                        <div class="col-sm-6 col-lg-3">
                            <div class="widget">
                                <h4 class="widget-title">My Account</h4>
                                <!-- End .widget-title -->

                                <ul class="widget-list">
                                    <li>
                                        @if (Auth::check())
                                            <a href={{ url('/cart/' . Auth::id()) }}>View Cart</a>
                                        @else
                                            <a href="#">View Cart</a>
                                        @endif
                                    </li>
                                    <li>
                                        @if (Auth::check())
                                            <a href={{ url('/wish-list/' . Auth::id()) }}>My Wishlist</a>
                                        @else
                                            <a href='#'>My Wishlist</a>
                                        @endif
                                    </li>
                                    <li><a href="#">Help</a></li>
                                </ul>
                                <!-- End .widget-list -->
                            </div>
                            <!-- End .widget -->
                        </div>
                        <!-- End .col-sm-6 col-lg-3 -->
                    </div>
                    <!-- End .row -->
                </div>
                <!-- End .container -->
            </div>
            <!-- End .footer-middle -->

            <div class="footer-bottom">
                <div class="container">
                    <p class="footer-copyright">
                        Copyright © 2019 ElectroSphere Store. All Rights Reserved.
                    </p>
                    <!-- End .footer-copyright -->
                    <figure class="footer-payments">
                        {{-- <img src="assets/img/payments.jpeg" alt="Payment methods" width="272" height="20" /> --}}
                    </figure>
                    <!-- End .footer-payments -->
                </div>
                <!-- End .container -->
            </div>
            <!-- End .footer-bottom -->
        </footer>
        <!-- End .footer -->
    </div>
    <!-- End .page-wrapper -->
    <button id="scroll-top" title="Back to Top">
        <i class="icon-arrow-up"></i>
    </button>

    <!-- Mobile Menu -->
    <div class="mobile-menu-overlay"></div>
    <!-- End .mobil-menu-overlay -->

    <div class="mobile-menu-container">
        <div class="mobile-menu-wrapper">
            <span class="mobile-menu-close"><i class="icon-close"></i></span>

            <form action="#" method="get" class="mobile-search">
                <label for="mobile-search" class="sr-only">Search</label>
                <input type="search" class="form-control" name="mobile-search" id="mobile-search"
                    placeholder="Search in..." required />
                <button class="btn btn-primary" type="submit">
                    <i class="icon-search"></i>
                </button>
            </form>

            <ul class="nav nav-pills-mobile nav-border-anim" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="mobile-menu-link" data-toggle="tab" href="#mobile-menu-tab"
                        role="tab" aria-controls="mobile-menu-tab" aria-selected="true">Menu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="mobile-cats-link" data-toggle="tab" href="#mobile-cats-tab"
                        role="tab" aria-controls="mobile-cats-tab" aria-selected="false">Categories</a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="mobile-menu-tab" role="tabpanel"
                    aria-labelledby="mobile-menu-link">
                    <nav class="mobile-nav">
                        <ul class="mobile-menu">
                            <li class={{ request()->is('/') ? 'active' : '' }}>
                                <a href={{ url('/') }}>Home</a>

                            </li>
                            <li class={{ request()->is('/products') ? 'active' : '' }}>
                                <a href={{ url('/products') }} class="sf-with-ul">Product</a>
                            </li>
                            <li class={{ request()->is('/about') ? 'active' : '' }}>
                                <a href={{ url('/about') }} class="sf-with-ul">About us</a>
                            </li>
                            <li class={{ request()->is('/contact') ? 'active' : '' }}>
                                <a href={{ url('/contact') }} class="sf-with-ul">Contact us</a>
                            </li>
                        </ul>
                    </nav>
                    <!-- End .mobile-nav -->
                </div>
                <!-- .End .tab-pane -->
            </div>
            <!-- End .tab-content -->

            <div class="social-icons">
                <a href="#" class="social-icon" target="_blank" title="Facebook"><i
                        class="icon-facebook-f"></i></a>
                <a href="#" class="social-icon" target="_blank" title="Twitter"><i
                        class="icon-twitter"></i></a>
                <a href="#" class="social-icon" target="_blank" title="Instagram"><i
                        class="icon-instagram"></i></a>
                <a href="#" class="social-icon" target="_blank" title="Youtube"><i
                        class="icon-youtube"></i></a>
            </div>
            <!-- End .social-icons -->
        </div>
        <!-- End .mobile-menu-wrapper -->
    </div>
    <!-- End .mobile-menu-container -->

    <!-- Sign in / Register Modal -->
    <div class="modal fade" id="signin-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="icon-close"></i></span>
                    </button>

                    <div class="form-box">
                        <div class="form-tab">
                            <ul class="nav nav-pills nav-fill nav-border-anim" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="signin-tab" data-toggle="tab" href="#signin"
                                        role="tab" aria-controls="signin" aria-selected="true">Sign In</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="register-tab" data-toggle="tab" href="#register"
                                        role="tab" aria-controls="register" aria-selected="false">Register</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="tab-content-5">
                                <div class="tab-pane fade show active" id="signin" role="tabpanel"
                                    aria-labelledby="signin-tab">
                                    <form method="POST" action="{{ route('login') }}">
                                        @csrf
                                        <div class="form-group">
                                            <label for="email">Email address *</label>
                                            <input type="email" class="form-control" id="email" name="email"
                                                value="{{ old('email') }}" required autofocus />
                                        </div>
                                        <!-- End .form-group -->

                                        <div class="form-group">
                                            <label for="password">Password *</label>
                                            <input type="password" class="form-control" id="password"
                                                name="password" required autocomplete="current-password" />
                                        </div>
                                        <!-- End .form-group -->

                                        <div class="form-footer">
                                            <button type="submit" class="btn btn-outline-primary-2">
                                                <span>LOG IN</span>
                                                <i class="icon-long-arrow-right"></i>
                                            </button>

                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="remember"
                                                    name="remember" />
                                                <label class="custom-control-label" for="remember">Remember Me</label>
                                            </div>
                                            <!-- End .custom-checkbox -->

                                            @if (Route::has('password.request'))
                                                <a href="{{ route('password.request') }}" class="forgot-link">Forgot
                                                    Your Password?</a>
                                            @endif
                                        </div>
                                        <!-- End .form-footer -->
                                    </form>
                                </div>
                                <!-- .End .tab-pane -->
                                <div class="tab-pane fade" id="register" role="tabpanel"
                                    aria-labelledby="register-tab">
                                    <form method="POST" action="{{ route('register') }}">
                                        @csrf
                                        <div class="form-group">
                                            <label for="name">Name *</label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                value="{{ old('name') }}" required autofocus />
                                        </div>
                                        <!-- End .form-group -->

                                        <div class="form-group">
                                            <label for="register-email">Your email address *</label>
                                            <input type="email" class="form-control" id="register-email"
                                                name="email" value="{{ old('email') }}" required />
                                        </div>
                                        <!-- End .form-group -->

                                        <div class="form-group">
                                            <label for="register-password">Password *</label>
                                            <input type="password" class="form-control" id="register-password"
                                                name="password" required autocomplete="new-password" />
                                        </div>
                                        <!-- End .form-group -->

                                        <div class="form-group">
                                            <label for="password_confirmation">Confirm Password *</label>
                                            <input type="password" class="form-control" id="password_confirmation"
                                                name="password_confirmation" required />
                                        </div>
                                        <!-- End .form-group -->

                                        <div class="form-footer">
                                            <button type="submit" class="btn btn-outline-primary-2">
                                                <span>SIGN UP</span>
                                                <i class="icon-long-arrow-right"></i>
                                            </button>

                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input"
                                                    id="register-policy" required />
                                                <label class="custom-control-label" for="register-policy">I agree to
                                                    the
                                                    <a href="#">privacy policy</a>
                                                    *</label>
                                            </div>
                                            <!-- End .custom-checkbox -->
                                        </div>
                                        <!-- End .form-footer -->
                                    </form>
                                </div>
                                <!-- .End .tab-pane -->
                            </div>
                            <!-- End .tab-content -->
                        </div>
                        <!-- End .form-tab -->
                    </div>
                    <!-- End .form-box -->
                </div>
                <!-- End .modal-body -->
            </div>
            <!-- End .modal-content -->
        </div>
        <!-- End .modal-dialog -->
    </div>
    <!-- End .modal -->

    {{-- <div class="container newsletter-popup-container mfp-hide" id="newsletter-popup-form">
        <div class="row justify-content-center">
            <div class="col-10">
                <div class="row no-gutters bg-white newsletter-popup-content">
                    <div class="col-xl-3-5col col-lg-7 banner-content-wrap">
                        <div class="banner-content text-center">
                            <img src={{ asset('assets/images/popup/newsletter/logo.png') }} class="logo"
                                alt="logo" width="60" height="15" />
                            <h2 class="banner-title">
                                get <span>25<light>%</light></span> off
                            </h2>
                            <p>
                                Subscribe to the ElectroSphere eCommerce newsletter
                                to receive timely updates from your favorite
                                products.
                            </p>
                            <form action="#">
                                <div class="input-group input-group-round">
                                    <input type="email" class="form-control form-control-white"
                                        placeholder="Your Email Address" aria-label="Email Adress" required />
                                    <div class="input-group-append">
                                        <button class="btn" type="submit">
                                            <span>go</span>
                                        </button>
                                    </div>
                                    <!-- .End .input-group-append -->
                                </div>
                                <!-- .End .input-group -->
                            </form>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="register-policy-2"
                                    required />
                                <label class="custom-control-label" for="register-policy-2">Do not show this popup
                                    again</label>
                            </div>
                            <!-- End .custom-checkbox -->
                        </div>
                    </div>
                    <div class="col-xl-2-5col col-lg-5">
                        <img src={{ asset('assets/images/popup/newsletter/img-1.jpg') }} class="newsletter-img"
                            alt="newsletter" />
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <!-- Plugins JS File -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/jquery.hoverIntent.min.js"></script>
    <script src="assets/js/jquery.waypoints.min.js"></script>
    <script src="assets/js/superfish.min.js"></script>
    <script src="assets/js/owl.carousel.min.js"></script>
    <script src="assets/js/bootstrap-input-spinner.js"></script>
    <script src="assets/js/jquery.plugin.min.js"></script>
    <script src="assets/js/jquery.magnific-popup.min.js"></script>
    <script src="assets/js/jquery.countdown.min.js"></script>
    <!-- Main JS File -->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/demos/demo-3.js"></script>
</body>

<!-- ElectroSphere/index-3.html  22 Nov 2019 09:55:58 GMT -->

</html>
