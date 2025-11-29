@extends('layouts.app')

@section('title', 'Détails Utilisateur - ' . $user->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h1 class="text-2xl lg:text-3xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-user mr-3 text-blue-600"></i>
                Détails de l'utilisateur
            </h1>
            <nav class="text-sm mt-2" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-gray-500">
                    <li><a href="{{ route('dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
                    <li><i class="fas fa-chevron-right text-xs"></i></li>
                    <li><a href="{{ route('admin.users.index') }}" class="hover:text-blue-600">Utilisateurs</a></li>
                    <li><i class="fas fa-chevron-right text-xs"></i></li>
                    <li class="text-gray-900 font-medium">{{ $user->name }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.users.index') }}" class="mt-3 sm:mt-0 inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Retour
        </a>
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - User Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 px-6 py-8 text-center">
                    <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center text-blue-600 font-bold text-3xl mx-auto mb-3 shadow-lg">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <h3 class="text-xl font-bold text-white mb-1">{{ $user->name }}</h3>
                    <p class="text-blue-100 text-sm">
                        <i class="fas fa-envelope mr-1"></i>{{ $user->email }}
                    </p>
                    @if($user->name === 'admin')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-500 text-white mt-3">
                            <i class="fas fa-shield-alt mr-1"></i>Administrateur
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-500 text-white mt-3">
                            <i class="fas fa-user mr-1"></i>Utilisateur
                        </span>
                    @endif
                </div>
                
                <div class="px-6 py-4">
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-calendar-plus text-blue-600"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-gray-500 mb-1">Date d'inscription</p>
                                <p class="text-sm font-medium text-gray-900">{{ $user->created_at->format('d/m/Y à H:i') }}</p>
                                <p class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-sync text-purple-600"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-gray-500 mb-1">Dernière mise à jour</p>
                                <p class="text-sm font-medium text-gray-900">{{ $user->updated_at->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <!-- Actions -->
                    <div class="space-y-2">
                        <button onclick="openResetPasswordModal({{ $user->id }}, '{{ $user->name }}')" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center justify-center">
                            <i class="fas fa-key mr-2"></i>
                            Réinitialiser le mot de passe
                        </button>
                        
                        @if($user->name !== 'admin')
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center justify-center">
                                    <i class="fas fa-trash mr-2"></i>
                                    Supprimer l'utilisateur
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Activity & Items -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-sm p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm mb-1">Articles dans le panier</p>
                            <p class="text-3xl font-bold">{{ $user->cartItems->count() }}</p>
                        </div>
                        <div class="w-14 h-14 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <i class="fas fa-shopping-cart text-2xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg shadow-sm p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-yellow-100 text-sm mb-1">Liste de souhaits</p>
                            <p class="text-3xl font-bold">{{ $user->wishlistItems->count() }}</p>
                        </div>
                        <div class="w-14 h-14 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <i class="fas fa-heart text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cart Items -->
            @if($user->cartItems->count() > 0)
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-blue-50 border-b border-blue-100">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-shopping-cart text-blue-600 mr-2"></i>
                        Panier ({{ $user->cartItems->count() }})
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produit</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Prix</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Ajouté le</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($user->cartItems as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        @if($item->offer)
                                            <a href="{{ url('/product/details/' . $item->offer->id) }}" target="_blank" class="text-blue-600 hover:text-blue-800 hover:underline">
                                                {{ $item->offer->name }}
                                            </a>
                                        @else
                                            <span class="text-gray-400 italic">Produit supprimé</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($item->offer)
                                            <span class="font-semibold text-gray-900">{{ number_format($item->offer->price, 0, '.', ',') }} F</span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm text-gray-500">
                                        {{ $item->created_at->format('d/m/Y') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Wishlist Items -->
            @if($user->wishlistItems->count() > 0)
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-yellow-50 border-b border-yellow-100">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-heart text-yellow-600 mr-2"></i>
                        Liste de souhaits ({{ $user->wishlistItems->count() }})
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produit</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Prix</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Ajouté le</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($user->wishlistItems as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        @if($item->offer)
                                            <a href="{{ url('/product/details/' . $item->offer->id) }}" target="_blank" class="text-blue-600 hover:text-blue-800 hover:underline">
                                                {{ $item->offer->name }}
                                            </a>
                                        @else
                                            <span class="text-gray-400 italic">Produit supprimé</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($item->offer)
                                            <span class="font-semibold text-gray-900">{{ number_format($item->offer->price, 0, '.', ',') }} F</span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm text-gray-500">
                                        {{ $item->created_at->format('d/m/Y') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Empty State -->
            @if($user->cartItems->count() == 0 && $user->wishlistItems->count() == 0)
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <i class="fas fa-inbox text-gray-300 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune activité</h3>
                <p class="text-gray-500">Cet utilisateur n'a pas encore ajouté de produits au panier ou à la liste de souhaits.</p>
            </div>
            @endif
        </div>
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
        
        <form id="resetPasswordForm" method="POST" action="{{ route('admin.users.resetPassword', $user->id) }}">
            @csrf
            <p class="text-sm text-gray-600 mb-4">
                Définir un nouveau mot de passe pour: <strong>{{ $user->name }}</strong>
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
@endsection
