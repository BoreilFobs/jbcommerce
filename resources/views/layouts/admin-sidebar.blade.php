<aside id="sidebar-wrapper" class="h-full flex flex-col">
    <div class="sidebar-brand p-6 border-b border-gray-200 text-center bg-gray-900">
        <a href="{{ url('/') }}" class="text-2xl font-bold text-white">ElectreoSphere</a>
        <p class="text-gray-400 text-xs mt-1">Administration</p>
    </div>
    
    <ul class="sidebar-menu flex-grow p-4 space-y-2">
        <li class="menu-header uppercase text-sm text-gray-400 px-3 pt-4">Tableau de Bord</li>
        <li class="{{ Request::is('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="nav-link {{ Request::is('dashboard') ? 'active' : '' }} flex items-center p-3 rounded-lg hover:bg-gray-800 transition-colors {{ Request::is('dashboard') ? 'bg-blue-600' : 'text-gray-300' }}">
                <i class="fas fa-chart-line mr-3"></i><span>Vue d'ensemble</span>
            </a>
        </li>

        <li class="menu-header uppercase text-sm text-gray-400 px-3 pt-4 mt-4">Gestion</li>

        <li class="{{ Request::is('offers*') ? 'active' : '' }}">
            <a href="{{route('offer.index')}}" class="nav-link flex items-center p-3 rounded-lg hover:bg-gray-800 transition-colors {{ Request::is('offers*') ? 'bg-blue-600 text-white' : 'text-gray-300' }}">
                <i class="fas fa-box mr-3"></i>
                <span>Produits</span>
            </a>
        </li>
        
        <li class="{{ Request::is('categories*') ? 'active' : '' }}">
            <a href="{{route('categories.index')}}" class="nav-link flex items-center p-3 rounded-lg hover:bg-gray-800 transition-colors {{ Request::is('categories*') ? 'bg-blue-600 text-white' : 'text-gray-300' }}">
                <i class="fas fa-tags mr-3"></i>
                <span>Catégories</span>
            </a>
        </li>

        <li class="{{ Request::is('admin/users*') ? 'active' : '' }}">
            <a href="{{route('admin.users.index')}}" class="nav-link flex items-center p-3 rounded-lg hover:bg-gray-800 transition-colors {{ Request::is('admin/users*') ? 'bg-blue-600 text-white' : 'text-gray-300' }}">
                <i class="fas fa-users mr-3"></i>
                <span>Utilisateurs</span>
            </a>
        </li>

        <li class="{{ Request::is('messages*') ? 'active' : '' }}">
            <a href="{{ url('/messages') }}" class="nav-link flex items-center p-3 rounded-lg hover:bg-gray-800 transition-colors {{ Request::is('messages*') ? 'bg-blue-600 text-white' : 'text-gray-300' }}">
                <i class="fas fa-envelope mr-3"></i>
                <span>Messages</span>
            </a>
        </li>
    </ul>
    
    <div class="mt-auto p-4 border-t border-gray-700">
        <div class="bg-gray-800 rounded-lg p-3 mb-3">
            <div class="flex items-center mb-2">
                <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold mr-3">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="flex-1">
                    <p class="text-white text-sm font-semibold">{{ Auth::user()->name }}</p>
                    <p class="text-gray-400 text-xs">Administrateur</p>
                </div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center p-3 rounded-lg bg-red-600 hover:bg-red-700 transition-colors text-white">
                <i class="fas fa-sign-out-alt mr-2"></i>
                Déconnexion
            </button>
        </form>
    </div>
</aside>
