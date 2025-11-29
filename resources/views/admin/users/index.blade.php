@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h1 class="text-2xl lg:text-3xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-users mr-3 text-blue-600"></i>
                Gestion des Utilisateurs
            </h1>
            <p class="text-gray-600 mt-1 hidden sm:block">Gérer tous les utilisateurs de la plateforme</p>
        </div>
        <div class="mt-3 sm:mt-0">
            <span class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 text-white font-semibold text-sm">
                <i class="fas fa-user mr-2"></i>
                {{ $users->total() }} utilisateur(s)
            </span>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-4 p-4 rounded-lg bg-green-50 border border-green-200 flex items-center" role="alert">
            <i class="fas fa-check-circle text-green-600 mr-3 text-xl"></i>
            <span class="text-green-800">{{ session('success') }}</span>
            <button type="button" class="ml-auto text-green-600 hover:text-green-800" onclick="this.parentElement.style.display='none'">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 rounded-lg bg-red-50 border border-red-200 flex items-center" role="alert">
            <i class="fas fa-exclamation-circle text-red-600 mr-3 text-xl"></i>
            <span class="text-red-800">{{ session('error') }}</span>
            <button type="button" class="ml-auto text-red-600 hover:text-red-800" onclick="this.parentElement.style.display='none'">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <!-- Users List -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-list mr-2"></i>
                Liste des Utilisateurs
            </h2>
            <button class="hidden md:inline-flex items-center px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm" onclick="window.print()">
                <i class="fas fa-print mr-2"></i>
                Imprimer
            </button>
        </div>

        @if($users->count() > 0)
            <!-- Desktop Table View -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilisateur</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Panier</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Wishlist</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Inscription</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($users as $user)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            @if($user->name === 'admin')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                    <i class="fas fa-shield-alt mr-1"></i>Admin
                                                </span>
                                            @else
                                                <span class="text-xs text-gray-500">Utilisateur</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        <i class="fas fa-envelope text-gray-400 mr-1"></i>
                                        {{ $user->email }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-shopping-cart mr-1"></i>
                                        {{ $user->cartItems->count() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-heart mr-1"></i>
                                        {{ $user->wishlistItems->count() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><i class="far fa-calendar-alt mr-1 text-gray-400"></i>{{ $user->created_at->format('d/m/Y') }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.users.show', $user->id) }}" class="text-blue-600 hover:text-blue-800 p-2" title="Voir détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button onclick="openResetPasswordModal({{ $user->id }}, '{{ $user->name }}')" class="text-green-600 hover:text-green-800 p-2" title="Réinitialiser mot de passe">
                                            <i class="fas fa-key"></i>
                                        </button>
                                        @if($user->name !== 'admin')
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer {{ $user->name }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 p-2" title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <button type="button" class="text-gray-400 p-2 cursor-not-allowed" disabled title="Impossible de supprimer l'admin">
                                                <i class="fas fa-lock"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile/Tablet Card View -->
            <div class="lg:hidden p-4 space-y-4">
                @foreach($users as $user)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <!-- User Header -->
                        <div class="flex items-center mb-3 pb-3 border-b border-gray-200">
                            <div class="flex-shrink-0 h-12 w-12 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="ml-3 flex-1">
                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                @if($user->name === 'admin')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 mt-1">
                                        <i class="fas fa-shield-alt mr-1"></i>Admin
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Stats -->
                        <div class="grid grid-cols-2 gap-3 mb-3">
                            <div>
                                <div class="text-xs text-gray-500 mb-1">Panier</div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-shopping-cart mr-1"></i>{{ $user->cartItems->count() }}
                                </span>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 mb-1">Wishlist</div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-heart mr-1"></i>{{ $user->wishlistItems->count() }}
                                </span>
                            </div>
                        </div>

                        <!-- Date -->
                        <div class="text-xs text-gray-500 mb-3">
                            <i class="far fa-calendar-alt mr-1"></i>Inscrit le {{ $user->created_at->format('d/m/Y') }}
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-2">
                            <a href="{{ route('admin.users.show', $user->id) }}" class="flex-1 px-3 py-2 bg-blue-600 text-white text-center rounded-lg hover:bg-blue-700 transition-colors text-sm">
                                <i class="fas fa-eye mr-1"></i>Voir
                            </a>
                            <button onclick="openResetPasswordModal({{ $user->id }}, '{{ $user->name }}')" class="px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors" title="Réinitialiser">
                                <i class="fas fa-key"></i>
                            </button>
                            @if($user->name !== 'admin')
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer {{ $user->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <i class="fas fa-users text-gray-300 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun utilisateur trouvé</h3>
                <p class="text-gray-500">Les utilisateurs apparaîtront ici une fois inscrits.</p>
            </div>
        @endif

        <!-- Pagination -->
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="flex justify-center">
                    {{ $users->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Reset Password Modal -->
<div id="resetPasswordModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-lg bg-white">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="fas fa-key text-green-600 mr-2"></i>
                Réinitialiser le mot de passe
            </h3>
            <button onclick="closeResetPasswordModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="resetPasswordForm" method="POST">
            @csrf
            <p class="text-sm text-gray-600 mb-4">
                Définir un nouveau mot de passe pour: <strong id="modalUserName"></strong>
            </p>
            
            <div class="mb-4">
                <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-lock mr-1"></i>Nouveau mot de passe
                </label>
                <input type="password" name="new_password" id="new_password" required minlength="8"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                       placeholder="Min. 8 caractères">
            </div>
            
            <div class="mb-4">
                <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-lock mr-1"></i>Confirmer le mot de passe
                </label>
                <input type="password" name="new_password_confirmation" id="new_password_confirmation" required minlength="8"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                       placeholder="Retapez le mot de passe">
            </div>
            
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-4">
                <p class="text-xs text-yellow-800">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    L'utilisateur devra utiliser ce nouveau mot de passe pour se connecter.
                </p>
            </div>
            
            <div class="flex gap-3">
                <button type="button" onclick="closeResetPasswordModal()" class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    <i class="fas fa-times mr-1"></i>Annuler
                </button>
                <button type="submit" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-check mr-1"></i>Réinitialiser
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openResetPasswordModal(userId, userName) {
        document.getElementById('modalUserName').textContent = userName;
        document.getElementById('resetPasswordForm').action = `/admin/users/${userId}/reset-password`;
        document.getElementById('resetPasswordModal').classList.remove('hidden');
        document.getElementById('new_password').focus();
    }

    function closeResetPasswordModal() {
        document.getElementById('resetPasswordModal').classList.add('hidden');
        document.getElementById('resetPasswordForm').reset();
    }

    // Close modal on outside click
    document.getElementById('resetPasswordModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeResetPasswordModal();
        }
    });

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeResetPasswordModal();
        }
    });
</script>

<style>
    @media print {
        .hidden, button, nav {
            display: none !important;
        }
    }
</style>
@endsection
