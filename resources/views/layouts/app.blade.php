<!DOCTYPE html>
<html lang="en">

<!-- blank.html  Tue, 07 Jan 2020 03:35:42 GMT -->

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>@yield('title', 'Blank Page') &mdash; ElectreoSphere</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('assets/modules/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/summernote/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/owlcarousel2/dist/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/owlcarousel2/dist/assets/owl.theme.default.min.css') }}">

    <!-- Template CSS -->
    <link rel="stylesheet" href={{ asset('assets/css/style.css') }} />

    <link rel="stylesheet" href="{{ asset('assets/css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components.min.css') }}">
</head>

<body class="layout-4">
    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <span class="loader"><span class="loader-inner"></span></span>
    </div>

    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <div class="navbar-bg"></div>

            <!-- Start app top navbar -->
            <nav class="navbar navbar-expand-lg main-navbar">
                <form class="form-inline mr-auto">
                    <ul class="navbar-nav mr-3">
                        <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i
                                    class="fas fa-bars"></i></a></li>
                        <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i
                                    class="fas fa-search"></i></a></li>
                    </ul>
                    <div class="search-element">
                        <input class="form-control" type="search" placeholder="Search" aria-label="Search"
                            data-width="250">
                        <button class="btn" type="submit"><i class="fas fa-search"></i></button>
                        <div class="search-backdrop"></div>
                        <div class="search-result">
                            <div class="search-header">
                                Histories
                            </div>
                            <div class="search-item">
                                <a href="#">How to hack NASA using CSS</a>
                                <a href="#" class="search-close"><i class="fas fa-times"></i></a>
                            </div>
                            <div class="search-item">
                                <a href="#">Kodinger.com</a>
                                <a href="#" class="search-close"><i class="fas fa-times"></i></a>
                            </div>
                            <div class="search-item">
                                <a href="#">#ElectreoSphere</a>
                                <a href="#" class="search-close"><i class="fas fa-times"></i></a>
                            </div>
                            <div class="search-header">
                                Result
                            </div>
                            <div class="search-item">
                                <a href="#">
                                    <img class="mr-3 rounded" width="30"
                                        src="{{ asset('assets/img/products/product-3-50.png') }}" alt="product">
                                    oPhone S9 Limited Edition
                                </a>
                            </div>
                            <div class="search-item">
                                <a href="#">
                                    <img class="mr-3 rounded" width="30"
                                        src="{{ asset('assets/img/products/product-2-50.png') }}" alt="product">
                                    Drone X2 New Gen-7
                                </a>
                            </div>
                            <div class="search-item">
                                <a href="#">
                                    <img class="mr-3 rounded" width="30"
                                        src="{{ asset('assets/img/products/product-1-50.png') }}" alt="product">
                                    Headphone Blitz
                                </a>
                            </div>
                            <div class="search-header">
                                Projects
                            </div>
                            <div class="search-item">
                                <a href="#">
                                    <div class="search-icon bg-danger text-white mr-3">
                                        <i class="fas fa-code"></i>
                                    </div>
                                    ElectreoSphere Admin Template
                                </a>
                            </div>
                            <div class="search-item">
                                <a href="#">
                                    <div class="search-icon bg-primary text-white mr-3">
                                        <i class="fas fa-laptop"></i>
                                    </div>
                                    Create a new Homepage Design
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
                <ul class="navbar-nav navbar-right">
                    <li class="dropdown dropdown-list-toggle"><a href="#" data-toggle="dropdown"
                            class="nav-link notification-toggle nav-link-lg"><i class="far fa-bell"></i></a>
                        <div class="dropdown-menu dropdown-list dropdown-menu-right">
                            <div class="dropdown-header">Notifications
                                <div class="float-right">
                                    <a href="#">Mark All As Read</a>
                                </div>
                            </div>
                            <div class="dropdown-list-content dropdown-list-icons">
                                <a href="#" class="dropdown-item dropdown-item-unread">
                                    <div class="dropdown-item-icon bg-primary text-white">
                                        <i class="fas fa-code"></i>
                                    </div>
                                    <div class="dropdown-item-desc">
                                        New update is available
                                        <div class="time text-primary">2 min ago</div>
                                    </div>
                                </a>
                                <a href="#" class="dropdown-item">
                                    <div class="dropdown-item-icon bg-info text-white">
                                        <i class="fas fa-bell"></i>
                                    </div>
                                    <div class="dropdown-item-desc">
                                        Welcome to ElectreoSphere!
                                        <div class="time">Yesterday</div>
                                    </div>
                                </a>
                            </div>
                            <div class="dropdown-footer text-center">
                                <a href="#">View All <i class="fas fa-chevron-right"></i></a>
                            </div>
                        </div>
                    </li>
                    <li class="dropdown"><a href="#" data-toggle="dropdown"
                            class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                            <img alt="image" src="{{ asset('assets/img/avatar/images.png') }}"
                                class="rounded-circle mr-1">
                            <div class="d-sm-none d-lg-inline-block">Hi,
                                {{ Auth::check() ? Auth::user()->name : 'Guest' }}</div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            @if (Auth::check())
                                <div class="dropdown-title">Logged in 5 min ago</div>
                                <a href="{{ route('profile.edit') }}" class="dropdown-item has-icon">
                                    <i class="far fa-user"></i> Profile
                                </a>
                                <a href="features-activities.html" class="dropdown-item has-icon">
                                    <i class="fas fa-bolt"></i> Activities
                                </a>
                                <a href="features-settings.html" class="dropdown-item has-icon">
                                    <i class="fas fa-cog"></i> Settings
                                </a>
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a href="{{ route('logout') }}" class="dropdown-item has-icon text-danger"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                        <i class="fas fa-sign-out-alt"></i> Logout
                                    </a>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="dropdown-item has-icon">
                                    <i class="fas fa-sign-in-alt"></i> Login
                                </a>
                                <a href="{{ route('register') }}" class="dropdown-item has-icon">
                                    <i class="fas fa-user-plus"></i> Register
                                </a>
                            @endif
                        </div>
                    </li>
                </ul>
            </nav>

            <!-- Start main left sidebar menu -->
            <div class="main-sidebar sidebar-style-2">
                <aside id="sidebar-wrapper">
                    <div class="sidebar-brand">
                        <a href="{{ url('/') }}">ElectreoSphere</a>
                    </div>
                    <div class="sidebar-brand sidebar-brand-sm">
                        <a href="{{ url('/') }}">ES</a>
                    </div>
                    <ul class="sidebar-menu">
                        <li class="menu-header">Dashboard</li>
                        <li class="{{ Request::is('dashboard') ? 'active' : '' }}">
                            <a href="{{ route('dashboard') }}" class="nav-link"><i
                                    class="fas fa-fire"></i><span>Dashboard</span></a>
                        </li>


                        <li class="menu-header">Admin Management</li>
                        {{-- <li class="dropdown {{ Request::is('team*') ? 'active' : '' }}">
                            <a href="#" class="nav-link has-dropdown"><i class="fas fa-users"></i>
                                <span>Team</span></a>
                            <ul class="dropdown-menu">
                                <li class="{{ Request::is('team') ? 'active' : '' }}"><a class="nav-link"
                                        href="{{ url('/team') }}">All Team Members</a></li>
                                <li class="{{ Request::is('team/create') ? 'active' : '' }}"><a class="nav-link"
                                        href="{{ url('/team/create') }}">Add Team Member</a></li>
                            </ul>
                        </li> --}}

                        <li class="dropdown {{ Request::is('offers*') ? 'active' : '' }}">
                            <a href="#" class="nav-link has-dropdown"><i class="fas fa-tags"></i>
                                <span>Products</span></a>
                            <ul class="dropdown-menu">
                                <li class="{{ Request::is('/offers') ? 'active' : '' }}"><a class="nav-link"
                                        href="{{ url('/offers') }}">All Product</a></li>
                                <li class="{{ Request::is('offers/create-offer') ? 'active' : '' }}"><a
                                        class="nav-link" href="{{ url('/offers/create-offer') }}">Create Product</a>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown {{ Request::is('categories*') ? 'active' : '' }}">
                            <a href="#" class="nav-link has-dropdown"><i class="fas fa-tags"></i>
                                <span>Category</span></a>
                            <ul class="dropdown-menu">
                                <li class="{{ Request::is('/categories') ? 'active' : '' }}"><a class="nav-link"
                                        href="{{ url('/categories') }}">All categories</a></li>
                                <li class="{{ Request::is('categories/create-offer') ? 'active' : '' }}"><a
                                        class="nav-link" href="{{ url('/categories/create-offer') }}">Create
                                        category</a>
                                </li>
                            </ul>
                        </li>

                        <li class="dropdown {{ Request::is('users*') ? 'active' : '' }}">
                            <a href="#" class="nav-link has-dropdown"><i class="fas fa-user-cog"></i>
                                <span>Users</span></a>
                            <ul class="dropdown-menu">
                                <li class="{{ Request::is('users') ? 'active' : '' }}"><a class="nav-link"
                                        href="{{ url('/users') }}">All Users</a></li>
                            </ul>
                        </li>
                        <li class="dropdown {{ Request::is('messages*') ? 'active' : '' }}">
                            <a href="#" class="nav-link has-dropdown"><i class="fas fa-user-cog"></i>
                                <span>Messages</span></a>
                            <ul class="dropdown-menu">
                                <li class="{{ Request::is('messages') ? 'active' : '' }}"><a class="nav-link"
                                        href="{{ url('/messages') }}">All Messages</a></li>
                            </ul>
                        </li>
                    </ul>
                    @if (Auth::check() && Auth::user()->name === 'admin')
                        <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
                            <a href="{{ route('dashboard') }}"
                                class="btn btn-primary btn-lg btn-block btn-icon-split"><i class="fas fa-rocket"></i>
                                Admin Dashboard</a>
                        </div>
                    @endif
                </aside>
            </div>

            <!-- Start app main Content -->
            <div class="main-content">
                <section class="section">
                    <div class="section-header">
                        <h1>@yield('title', 'Blank Page')</h1>
                    </div>
                    @yield('content')
                    <div class="section-body">
                    </div>
                </section>
            </div>

            <!-- Start app Footer part -->
            <footer class="main-footer">
                <div class="footer-left">
                    <div class="bullet"></div> <a href="https://github.com/BoreilFobs">ElectreoSphere</a>
                </div>
                <div class="footer-right">

                </div>
            </footer>
        </div>
    </div>

    <!-- General JS Scripts -->
    <script src="{{ asset('assets/bundles/lib.vendor.bundle.js') }}"></script>
    <script src="{{ asset('js/CodiePie.js') }}"></script>

    <!-- JS Libraies -->
    <script src="{{ asset('assets/modules/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('assets/modules/chart.min.js') }}"></script>
    <script src="{{ asset('assets/modules/owlcarousel2/dist/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/modules/summernote/summernote-bs4.js') }}"></script>
    <script src="{{ asset('assets/modules/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/index.js') }}"></script>

    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>

</body>

<!-- blank.html  Tue, 07 Jan 2020 03:35:42 GMT -->

</html>
