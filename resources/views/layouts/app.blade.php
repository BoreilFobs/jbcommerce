<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
        <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Tableau de bord') &mdash; JB Shop</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>

    <style type="text/tailwindcss">
        @layer base {
            body {
                @apply bg-gray-100 font-sans leading-normal tracking-normal text-gray-800;
            }
            
            @media (max-width: 640px) {
                body {
                    @apply text-sm;
                }
            }
        }
        @layer components {
            /* Mobile-first sidebar with dark theme */
            .main-sidebar {
                @apply fixed inset-y-0 left-0 z-50 transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:w-64 bg-gray-900;
            }
            .main-sidebar.show {
                @apply translate-x-0;
            }
            
            /* Dark sidebar menu items */
            .sidebar-menu a {
                @apply flex items-center p-3 text-gray-300 hover:bg-gray-800 transition-colors duration-200 rounded-lg mb-1;
            }
            .sidebar-menu a.active,
            .sidebar-menu a.nav-link.active {
                @apply bg-blue-600 text-white hover:bg-blue-700;
            }
            .sidebar-menu .menu-header {
                @apply uppercase text-sm text-gray-400 px-3 pt-4 mt-4 first:mt-0;
            }
            .sidebar-menu .dropdown-menu {
                @apply pl-4 space-y-1 mt-1;
            }
            
            /* Responsive main content */
            .main-content {
                @apply p-4 lg:p-6;
            }
            
            /* Improved section headers */
            .section-header {
                @apply flex flex-col sm:flex-row justify-between items-start sm:items-center pb-4 mb-6 border-b border-gray-200;
            }
            .section-header h1 {
                @apply text-xl lg:text-2xl font-bold text-gray-800 mb-2 sm:mb-0;
            }
            
            /* Enhanced cards */
            .card {
                @apply bg-white p-4 lg:p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300;
            }
            
            /* Mobile-friendly navbar */
            .navbar {
                @apply bg-white shadow-sm sticky top-0;
            }
            .navbar-nav .nav-link {
                @apply p-3 lg:p-4 text-gray-600 hover:text-gray-900;
            }
            
            /* Improved dropdowns */
            .dropdown-menu {
                @apply absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50;
            }
            .dropdown-item {
                @apply block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200;
            }
            
            /* Enhanced search input */
            .search-element input {
                @apply w-full sm:w-64 border border-gray-300 rounded-full px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300;
            }
            
            /* Touch-friendly buttons */
            .btn {
                @apply px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2;
            }
            .btn-primary {
                @apply bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500;
            }
            
            /* Table improvements */
            .table-responsive {
                @apply overflow-x-auto -mx-4 sm:mx-0;
            }
            .table {
                @apply min-w-full divide-y divide-gray-200;
            }
            .table th {
                @apply px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider;
            }
            .table td {
                @apply px-4 py-3 text-sm;
            }
            
            /* Form elements */
            .form-input {
                @apply w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition-colors duration-200;
            }
            
            /* Status badges */
            .badge {
                @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium;
            }
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="page-loader-wrapper fixed inset-0 flex items-center justify-center bg-white z-50 hidden">
        <span class="loader block w-12 h-12 border-4 border-t-4 border-blue-500 rounded-full animate-spin"></span>
    </div>

    <div id="app" class="flex min-h-screen">
        <!-- Admin Sidebar with Dark Theme -->
        <div class="main-sidebar w-64 bg-gray-900 shadow-lg flex-shrink-0">
            @include('layouts.admin-sidebar')
        </div>

        <div class="flex flex-col flex-1">
            <nav class="navbar bg-white shadow-sm sticky top-0 z-40">
                <div class="container mx-auto flex items-center justify-between p-4">
                    <div class="flex items-center">
                        <button class="nav-link-lg lg:hidden text-gray-600 mr-4 focus:outline-none hover:text-blue-600 transition-colors" id="sidebarToggle" data-toggle="sidebar">
                            <i class="fas fa-bars text-2xl"></i>
                        </button>
                        <form class="form-inline hidden sm:block">
                            <div class="search-element relative">
                                <input class="form-control rounded-full pl-4 pr-10 py-2.5 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300 w-full sm:w-64" type="search" placeholder="Rechercher..." aria-label="Rechercher">
                                <button class="btn absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-blue-600 transition-colors" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    <ul class="navbar-nav flex items-center space-x-4">
                        {{-- <li class="dropdown dropdown-list-toggle relative">
                            <a href="#" class="nav-link notification-toggle relative text-gray-600 hover:text-gray-900" data-toggle="dropdown">
                                <i class="far fa-bell text-xl"></i>
                                <div class="badge absolute top-0 right-0 -mt-1 -mr-1 bg-red-500 text-white text-xs font-bold rounded-full h-4 w-4 flex items-center justify-center">2</div>
                            </a>
                            <div class="dropdown-menu absolute right-0 mt-2 w-72 bg-white rounded-md shadow-lg py-1 z-50">
                                <div class="dropdown-header flex justify-between items-center p-4 border-b border-gray-200">
                                    <span class="font-semibold text-gray-800">Notifications</span>
                                    <a href="#" class="text-xs text-blue-600 hover:underline">Mark All As Read</a>
                                </div>
                                <div class="dropdown-list-content max-h-64 overflow-y-auto">
                                    <a href="#" class="dropdown-item flex items-center p-4 hover:bg-gray-100 transition-colors duration-200">
                                        <div class="dropdown-item-icon bg-blue-500 text-white rounded-full h-10 w-10 flex items-center justify-center mr-3">
                                            <i class="fas fa-code"></i>
                                        </div>
                                        <div class="dropdown-item-desc">
                                            <div class="text-sm font-medium text-gray-800">New update is available</div>
                                            <div class="time text-xs text-blue-600">2 min ago</div>
                                        </div>
                                    </a>
                                    <a href="#" class="dropdown-item flex items-center p-4 hover:bg-gray-100 transition-colors duration-200">
                                        <div class="dropdown-item-icon bg-green-500 text-white rounded-full h-10 w-10 flex items-center justify-center mr-3">
                                            <i class="fas fa-bell"></i>
                                        </div>
                                        <div class="dropdown-item-desc">
                                                                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                            <div class="text-sm font-medium text-gray-800">Welcome to JB Shop!</div>
                                            <div class="time text-xs text-gray-500">Yesterday</div>
                                        </div>
                                    </a>
                                </div>
                                <div class="dropdown-footer text-center p-2 border-t border-gray-200">
                                    <a href="#" class="text-sm text-blue-600 hover:underline">View All <i class="fas fa-chevron-right ml-1"></i></a>
                                </div>
                            </div>
                        </li> --}}
                        <li class="dropdown relative">
                            <a href="#" class="nav-link dropdown-toggle flex items-center" data-toggle="dropdown">
                                <img alt="image" src="{{ asset('assets/img/avatar/images.png') }}" class="rounded-full w-8 h-8 mr-2">
                                <div class="hidden md:block text-gray-700">Hi, {{ Auth::check() ? Auth::user()->name : 'Guest' }}</div>
                            </a>
                            <div class="dropdown-menu absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                @if (Auth::check())
                                <div class="dropdown-title px-4 py-2 text-sm text-gray-500">Logged in 5 min ago</div>
                                <a href="{{ route('profile.edit') }}" class="dropdown-item has-icon flex items-center px-4 py-2 hover:bg-gray-100">
                                    <i class="far fa-user mr-2"></i> Profile
                                </a>
                                {{-- <a href="features-activities.html" class="dropdown-item has-icon flex items-center px-4 py-2 hover:bg-gray-100">
                                    <i class="fas fa-bolt mr-2"></i> Activities
                                </a>
                                <a href="features-settings.html" class="dropdown-item has-icon flex items-center px-4 py-2 hover:bg-gray-100">
                                    <i class="fas fa-cog mr-2"></i> Settings
                                </a> --}}
                                <div class="dropdown-divider border-t border-gray-200 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a href="{{ route('logout') }}" class="dropdown-item has-icon text-red-500 flex items-center px-4 py-2 hover:bg-gray-100" onclick="event.preventDefault(); this.closest('form').submit();">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                    </a>
                                </form>
                                @else
                                <a href="{{ route('login') }}" class="dropdown-item has-icon flex items-center px-4 py-2 hover:bg-gray-100">
                                    <i class="fas fa-sign-in-alt mr-2"></i> Login
                                </a>
                                <a href="{{ route('register') }}" class="dropdown-item has-icon flex items-center px-4 py-2 hover:bg-gray-100">
                                    <i class="fas fa-user-plus mr-2"></i> Register
                                </a>
                                @endif
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>

            <div class="main-content flex-1 p-1">
                <section class="section">
                    <div class="section-header">
                        <h1>@yield('title', 'Blank Page')</h1>
                    </div>
                    @yield('content')
                    <div class="section-body">
                        </div>
                </section>
            </div>

            <footer class="main-footer bg-white p-4 text-center text-gray-600 border-t border-gray-200 mt-auto">
                <div class="footer-left">
                    <div class="bullet inline-block w-1 h-1 bg-gray-500 rounded-full mx-2"></div>
                    <a href="https://github.com/BoreilFobs" class="text-blue-600 hover:underline">JB shop</a>
                </div>
                <div class="footer-right">
                    </div>
            </footer>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Improved dropdown handling
            const dropdownToggles = document.querySelectorAll('[data-toggle="dropdown"]');
            const closeAllDropdowns = (except = null) => {
                dropdownToggles.forEach(toggle => {
                    if (toggle !== except) {
                        const dropdownMenu = toggle.nextElementSibling;
                        if (dropdownMenu?.classList.contains('dropdown-menu')) {
                            dropdownMenu.classList.add('hidden');
                        }
                    }
                });
            };

            dropdownToggles.forEach(toggle => {
                toggle.addEventListener('click', (e) => {
                    e.preventDefault();
                    const dropdownMenu = toggle.nextElementSibling;
                    if (dropdownMenu?.classList.contains('dropdown-menu')) {
                        closeAllDropdowns(toggle);
                        dropdownMenu.classList.toggle('hidden');
                    }
                });
            });

            // Enhanced mobile sidebar handling
            const sidebarToggle = document.querySelector('[data-toggle="sidebar"]');
            const sidebar = document.querySelector('.main-sidebar');
            const overlay = document.createElement('div');
            overlay.className = 'fixed inset-0 bg-gray-900 bg-opacity-50 z-40 lg:hidden hidden';
            document.body.appendChild(overlay);

            const toggleSidebar = () => {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('hidden');
                document.body.classList.toggle('overflow-hidden');
            };

            if (sidebarToggle && sidebar) {
                sidebarToggle.addEventListener('click', toggleSidebar);
                overlay.addEventListener('click', toggleSidebar);
            }

            // Handle clicks outside dropdowns
            document.addEventListener('click', (e) => {
                if (!e.target.closest('[data-toggle="dropdown"]')) {
                    closeAllDropdowns();
                }
            });

            // Swipe to close sidebar on mobile
            let touchStartX = 0;
            let touchEndX = 0;

            sidebar.addEventListener('touchstart', e => {
                touchStartX = e.changedTouches[0].screenX;
            }, false);

            sidebar.addEventListener('touchend', e => {
                touchEndX = e.changedTouches[0].screenX;
                if (touchStartX > touchEndX + 50) { // Swipe left
                    toggleSidebar();
                }
            }, false);

            // Close sidebar on navigation
            const navLinks = sidebar.querySelectorAll('a[href]:not([href="#"])');
            navLinks.forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth < 1024) { // lg breakpoint
                        toggleSidebar();
                    }
                });
            });

            // Active link highlighting
            const currentPath = window.location.pathname;
            navLinks.forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                    const dropdownParent = link.closest('.dropdown');
                    if (dropdownParent) {
                        dropdownParent.classList.add('active');
                    }
                }
            });
        });
    </script>
</body>

</html>