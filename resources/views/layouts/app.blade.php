<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>@yield('title', 'Blank Page') &mdash; ElectreoSphere</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>

    <style type="text/tailwindcss">
        @layer base {
            body {
                @apply bg-gray-100 font-sans leading-normal tracking-normal;
            }
        }
        @layer components {
            .sidebar-menu a {
                @apply flex items-center p-3 text-gray-700 hover:bg-gray-200 transition-colors duration-200 rounded-md;
            }
            .sidebar-menu a.active {
                @apply bg-blue-600 text-white;
            }
            .sidebar-menu .dropdown-menu {
                @apply pl-6;
            }
            .main-content {
                @apply p-6;
            }
            .section-header {
                @apply flex justify-between items-center pb-4 mb-6 border-b border-gray-300;
            }
            .section-header h1 {
                @apply text-2xl font-semibold text-gray-800;
            }
            .card {
                @apply bg-white p-6 rounded-lg shadow-md;
            }
            .navbar {
                @apply bg-white shadow-md;
            }
            .navbar-nav .nav-link {
                @apply p-4 text-gray-600 hover:text-gray-900;
            }
            .dropdown-menu {
                @apply absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50;
            }
            .dropdown-item {
                @apply block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100;
            }
            .search-element input {
                @apply border border-gray-300 rounded-full px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500;
            }
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="page-loader-wrapper fixed inset-0 flex items-center justify-center bg-white z-50 hidden">
        <span class="loader block w-12 h-12 border-4 border-t-4 border-blue-500 rounded-full animate-spin"></span>
    </div>

    <div id="app" class="flex min-h-screen">
        <div class="main-sidebar w-64 bg-white shadow-lg flex-shrink-0">
            <aside id="sidebar-wrapper" class="h-full flex flex-col">
                <div class="sidebar-brand p-6 border-b border-gray-200 text-center">
                    <a href="{{ url('/') }}" class="text-2xl font-bold text-blue-600">ElectreoSphere</a>
                </div>
                <div class="sidebar-brand-sm p-4 text-center border-b border-gray-200 hidden">
                    <a href="{{ url('/') }}" class="text-xl font-bold text-blue-600">ES</a>
                </div>
                <ul class="sidebar-menu flex-grow p-4 space-y-2">
                    <li class="menu-header uppercase text-sm text-gray-500 px-3 pt-4">Dashboard</li>
                    <li class="{{ Request::is('dashboard') ? 'active' : '' }}">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}">
                            <i class="fas fa-fire mr-3"></i><span>Dashboard</span>
                        </a>
                    </li>

                    <li class="menu-header uppercase text-sm text-gray-500 px-3 pt-4">Admin Management</li>

                    <li class="dropdown {{ Request::is('offers*') ? 'active' : '' }}">
                        <a href="{{route("offer.index")}}" class="nav-link has-dropdown flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-tags mr-3"></i>
                                <span>Products</span>
                            </div>
                        </a>
                    </li>
                    <li class="dropdown {{ Request::is('categories*') ? 'active' : '' }}">
                        <a href="{{route('categories.index')}}" class="nav-link has-dropdown flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-tags mr-3"></i>
                                <span>Category</span>
                            </div>
                        </a>
                        <ul class="dropdown-menu mt-2 space-y-1 {{ Request::is('categories*') ? 'active' : '' }}">
                            {{-- <li>
                                <a class="nav-link {{ Request::is('categories') ? 'active' : '' }}" href="{{ url('/categories') }}">All Categories</a>
                            </li>
                            <li>
                                <a class="nav-link {{ Request::is('categories/create-offer') ? 'active' : '' }}" href="{{ url('/categories/create-offer') }}">Create Category</a>
                            </li> --}}
                        </ul>
                    </li>

                    <li class="dropdown {{ Request::is('users*') ? 'active' : '' }}">
                        <a href="{{route('users.index')}}" class="nav-link has-dropdown flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-user-cog mr-3"></i>
                                <span>Users</span>
                            </div>
                        </a>
                        {{-- <ul class="dropdown-menu mt-2 space-y-1 {{ Request::is('users*') ? 'block' : 'hidden' }}">
                            <li>
                                <a class="nav-link {{ Request::is('users') ? 'active' : '' }}" href="{{ url('/users') }}">All Users</a>
                            </li>
                        </ul> --}}
                    </li>
                    {{-- <li class="dropdown {{ Request::is('messages*') ? 'active' : '' }}">
                        <a href="#" class="nav-link has-dropdown flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-user-cog mr-3"></i>
                                <span>Messages</span>
                            </div>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </a>
                        <ul class="dropdown-menu mt-2 space-y-1 {{ Request::is('messages*') ? 'block' : 'hidden' }}">
                            <li>
                                <a class="nav-link {{ Request::is('messages') ? 'active' : '' }}" href="{{ url('/messages') }}">All Messages</a>
                            </li>
                        </ul>
                    </li> --}}
                </ul>
                @if (Auth::check() && Auth::user()->name === 'admin')
                <div class="mt-auto mb-4 p-3 hidden md:block">
                    <a href="{{ route('dashboard') }}" class="btn-primary w-full text-center py-3 px-4 rounded-md text-white font-semibold hover:bg-blue-700 transition-colors duration-200">
                        <i class="fas fa-rocket mr-2"></i> Admin Dashboard
                    </a>
                </div>
                @endif
            </aside>
        </div>

        <div class="flex flex-col flex-1">
            <nav class="navbar bg-white shadow-md sticky top-0 z-40">
                <div class="container mx-auto flex items-center justify-between p-4">
                    <div class="flex items-center">
                        <button class="nav-link-lg lg:hidden text-gray-600 mr-4 focus:outline-none" data-toggle="sidebar">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <form class="form-inline">
                            <div class="search-element relative">
                                <input class="form-control rounded-full pl-4 pr-10 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300 w-64" type="search" placeholder="Search" aria-label="Search">
                                <button class="btn absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700" type="submit">
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
                                            <div class="text-sm font-medium text-gray-800">Welcome to ElectreoSphere!</div>
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

            <div class="main-content flex-1 p-6">
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
                    <a href="https://github.com/BoreilFobs" class="text-blue-600 hover:underline">ElectreoSphere</a>
                </div>
                <div class="footer-right">
                    </div>
            </footer>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const dropdownToggles = document.querySelectorAll('[data-toggle="dropdown"]');
            dropdownToggles.forEach(toggle => {
                toggle.addEventListener('click', (e) => {
                    e.preventDefault();
                    const dropdownMenu = toggle.nextElementSibling;
                    if (dropdownMenu && dropdownMenu.classList.contains('dropdown-menu')) {
                        dropdownMenu.classList.toggle('hidden');
                    }
                });
            });

            const sidebarToggle = document.querySelector('[data-toggle="sidebar"]');
            const sidebar = document.querySelector('.main-sidebar');
            const app = document.getElementById('app');

            if (sidebarToggle && sidebar) {
                sidebarToggle.addEventListener('click', () => {
                    sidebar.classList.toggle('hidden');
                });
            }

            // Close dropdowns when clicking outside
            window.addEventListener('click', (e) => {
                dropdownToggles.forEach(toggle => {
                    const dropdownMenu = toggle.nextElementSibling;
                    if (dropdownMenu && !toggle.contains(e.target) && !dropdownMenu.contains(e.target)) {
                        dropdownMenu.classList.add('hidden');
                    }
                });
            });
        });

    </script>
</body>

</html>