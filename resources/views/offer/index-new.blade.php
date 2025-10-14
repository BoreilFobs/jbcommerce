@extends('layouts.app')
@section('title', 'Produits')

@section('content')

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-4 sm:mb-6" role="alert">
            <strong class="font-bold">Succès!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    
    <div class="main-content p-4 sm:p-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row items-center justify-between p-4 sm:p-6 bg-white rounded-lg shadow-md mb-6 gap-4">
            <div>
                <h4 class="text-xl font-semibold text-gray-800">Gestion des Produits</h4>
                <p class="text-sm text-gray-600 mt-1">{{ $offers->total() }} produits au total</p>
            </div>
            <a href="{{ route('offer.create') }}" class="w-full sm:w-auto btn bg-blue-600 text-white font-bold py-2 px-6 rounded-full hover:bg-blue-700 transition-colors duration-200 text-center">
                <i class="fas fa-plus mr-2"></i> Nouveau Produit
            </a>
        </div>

        <!-- Filters and Search -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <form method="GET" action="{{ route('offer.index') }}" id="filterForm">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div class="relative">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Rechercher un produit..." 
                               class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <select name="category" class="w-full py-2 px-4 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <option value="">Toutes les catégories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->name }}" {{ request('category') == $category->name ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <select name="status" class="w-full py-2 px-4 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <option value="">Tous les statuts</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actif</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactif</option>
                            <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Rupture de stock</option>
                        </select>
                    </div>

                    <!-- Sort By -->
                    <div>
                        <select name="sort_by" class="w-full py-2 px-4 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date de création</option>
                            <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Nom</option>
                            <option value="price" {{ request('sort_by') == 'price' ? 'selected' : '' }}>Prix</option>
                            <option value="quantity" {{ request('sort_by') == 'quantity' ? 'selected' : '' }}>Quantité</option>
                            <option value="views" {{ request('sort_by') == 'views' ? 'selected' : '' }}>Vues</option>
                        </select>
                    </div>
                </div>

                <div class="flex gap-3 mt-4">
                    <button type="submit" class="btn bg-blue-600 text-white font-semibold py-2 px-6 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-filter mr-2"></i> Filtrer
                    </button>
                    <a href="{{ route('offer.index') }}" class="btn bg-gray-500 text-white font-semibold py-2 px-6 rounded-lg hover:bg-gray-600 transition-colors">
                        <i class="fas fa-redo mr-2"></i> Réinitialiser
                    </a>
                </div>
            </form>
        </div>

        <!-- Products Grid -->
        @if($offers->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-4 sm:gap-6">
                @foreach ($offers as $offer)
                    <x-product-card :offer="$offer" :is-admin="true" />
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $offers->links() }}
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <i class="fas fa-box-open text-gray-300 text-6xl mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Aucun produit trouvé</h3>
                <p class="text-gray-500 mb-6">Essayez d'ajuster vos filtres ou ajoutez un nouveau produit.</p>
                <a href="{{ route('offer.create') }}" class="inline-block btn bg-blue-600 text-white font-bold py-2 px-6 rounded-full hover:bg-blue-700 transition-colors duration-200">
                    <i class="fas fa-plus mr-2"></i> Créer un produit
                </a>
            </div>
        @endif

        <!-- Confirmation Modal -->
        <div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Confirmer la suppression</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500">
                            Êtes-vous sûr de vouloir supprimer ce produit ? Cette action ne peut pas être annulée.
                        </p>
                    </div>
                    <div class="flex justify-center gap-4 mt-4">
                        <button id="cancelDelete" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-full shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                            Annuler
                        </button>
                        <button id="confirmDelete" class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-full shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                            Supprimer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(offerId) {
            const modal = document.getElementById('deleteModal');
            const confirmBtn = document.getElementById('confirmDelete');
            const cancelBtn = document.getElementById('cancelDelete');
            
            modal.classList.remove('hidden');
            
            confirmBtn.onclick = function() {
                window.location.href = "{{ url('/offers/delete') }}/" + offerId;
            }
            
            cancelBtn.onclick = function() {
                modal.classList.add('hidden');
            }
            
            // Close modal when clicking outside
            modal.onclick = function(e) {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                }
            }
        }

        // Auto-submit form on select change
        document.querySelectorAll('#filterForm select').forEach(select => {
            select.addEventListener('change', function() {
                document.getElementById('filterForm').submit();
            });
        });
    </script>
@endsection
