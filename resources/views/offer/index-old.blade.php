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
        <div class="flex flex-col sm:flex-row items-center justify-between p-4 sm:p-6 bg-white rounded-lg shadow-md mb-6 gap-4">
            <h4 class="text-xl font-semibold text-gray-800">Produits</h4>
            <div class="w-full sm:w-auto flex flex-col sm:flex-row gap-4">
                <div class="relative w-full sm:w-64">
                    <input type="text" 
                           placeholder="Rechercher un produit..." 
                           class="w-full pl-10 pr-4 py-2 rounded-full border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
                <a href="{{ route('offer.create') }}" class="w-full sm:w-auto btn bg-blue-600 text-white font-bold py-2 px-6 rounded-full hover:bg-blue-700 transition-colors duration-200 text-center">
                    <i class="fas fa-plus mr-2"></i> Nouveau Produit
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-4 sm:gap-6">
            @foreach ($offers as $offer)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-200">
                    <figure class="relative aspect-square sm:aspect-[4/3] overflow-hidden">
                        <img src="{{'storage/offer_img/product' . $offer->id . "/" . json_decode($offer->images, true)[0]}}" 
                             alt="Image du produit" 
                             class="w-full h-full object-cover transition-transform duration-300 hover:scale-110">
                        
                        <!-- Mobile-friendly action buttons -->
                        <div class="absolute top-2 right-2 flex flex-col space-y-2 sm:hidden">
                            <button onclick="window.location.href='{{ url('/offers/update/' . $offer->id) }}'"
                                    class="w-10 h-10 rounded-full bg-yellow-500 text-white flex items-center justify-center hover:bg-yellow-600 transition-colors duration-200"
                                    title="Modifier">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="confirmDelete('{{ $offer->id }}')"
                                    class="w-10 h-10 rounded-full bg-red-500 text-white flex items-center justify-center hover:bg-red-600 transition-colors duration-200"
                                    title="Supprimer">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        
                        <!-- Desktop action overlay -->
                        <div class="absolute inset-0 bg-black bg-opacity-40 items-center justify-center space-x-4 opacity-0 hover:opacity-100 transition-opacity duration-300 hidden sm:flex">
                            <button onclick="confirmDelete('{{ $offer->id }}')"
                                    class="btn bg-red-500 text-white font-bold py-2 px-4 rounded-full hover:bg-red-600 transition-colors duration-200" 
                                    title="Supprimer le produit">
                                Supprimer
                            </button>
                            <a href="{{ url('/offers/update/' . $offer->id) }}" 
                               class="btn bg-yellow-500 text-white font-bold py-2 px-4 rounded-full hover:bg-yellow-600 transition-colors duration-200" 
                               title="Modifier le produit">
                                Modifier
                            </a>
                        </div>
                    </figure>

                    <div class="p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs text-gray-500 font-semibold uppercase tracking-wide">
                                {{ $offer->category }}
                            </span>
                            <span class="text-sm font-semibold {{ $offer->quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $offer->quantity > 0 ? $offer->quantity . ' unités' : 'Rupture de stock' }}
                            </span>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2 line-clamp-2">
                            <a href="#" class="hover:text-blue-600 transition-colors duration-200">{{ $offer->name }}</a>
                        </h3>
                        <div class="flex items-center justify-between">
                            <span class="text-xl font-bold text-gray-800">{{ number_format($offer->price, 0, ',', ' ') }} FCFA</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

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

        // Handle search input
        const searchInput = document.querySelector('input[type="text"]');
        let timeoutId;

        searchInput.addEventListener('input', function(e) {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => {
                const searchTerm = e.target.value.toLowerCase();
                const products = document.querySelectorAll('.grid > div');
                
                products.forEach(product => {
                    const name = product.querySelector('h3').textContent.toLowerCase();
                    const category = product.querySelector('.uppercase').textContent.toLowerCase();
                    
                    if (name.includes(searchTerm) || category.includes(searchTerm)) {
                        product.style.display = '';
                    } else {
                        product.style.display = 'none';
                    }
                });
            }, 300);
        });
    </script>
@endsection