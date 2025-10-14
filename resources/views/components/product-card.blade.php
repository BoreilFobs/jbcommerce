@php
    $images = is_string($offer->images) ? json_decode($offer->images, true) : $offer->images;
    $firstImage = $images && is_array($images) && count($images) > 0 
        ? 'storage/offer_img/product' . $offer->id . '/' . $images[0]
        : 'img/default-product.jpg';
@endphp

<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-200 h-full flex flex-col">
    <figure class="relative aspect-square sm:aspect-[4/3] overflow-hidden">
        <a href="{{ url('/shop/' . $offer->id) }}">
            <img src="{{ asset($firstImage) }}" 
                 alt="{{ $offer->name }}" 
                 class="w-full h-full object-cover transition-transform duration-300 hover:scale-110"
                 loading="lazy">
        </a>
        
        @if($offer->discount_percentage > 0)
            <div class="absolute top-2 left-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                -{{ $offer->discount_percentage }}%
            </div>
        @endif

        @if($offer->featured)
            <div class="absolute top-2 {{ $offer->discount_percentage > 0 ? 'left-16' : 'left-2' }} bg-yellow-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                <i class="fas fa-star"></i> Vedette
            </div>
        @endif

        @if(!$offer->isInStock())
            <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                <span class="text-white font-bold text-lg">Rupture de stock</span>
            </div>
        @endif
        
        @if($isAdmin)
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
        @endif
    </figure>

    <div class="p-4 flex-grow flex-col">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs text-gray-500 font-semibold uppercase tracking-wide">
                {{ $offer->category }}
            </span>
            @if($isAdmin)
                <span class="text-sm font-semibold {{ $offer->quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $offer->quantity > 0 ? $offer->quantity . ' unités' : 'Rupture de stock' }}
                </span>
            @else
                <span class="text-sm font-semibold {{ $offer->isInStock() ? 'text-green-600' : 'text-red-600' }}">
                    {{ $offer->isInStock() ? 'En stock' : 'Rupture de stock' }}
                </span>
            @endif
        </div>
        
        @if($offer->brand)
            <div class="text-xs text-gray-400 mb-1">{{ $offer->brand }}</div>
        @endif

        <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2 line-clamp-2">
            <a href="{{ url('/shop/' . $offer->id) }}" class="hover:text-blue-600 transition-colors duration-200">
                {{ $offer->name }}
            </a>
        </h3>

        <div class="flex items-center justify-between mt-auto">
            <div class="flex flex-col">
                @if($offer->discount_percentage > 0)
                    <span class="text-sm text-gray-400 line-through">
                        {{ number_format($offer->price, 0, ',', ' ') }} FCFA
                    </span>
                    <span class="text-xl font-bold text-red-600">
                        {{ number_format($offer->discounted_price, 0, ',', ' ') }} FCFA
                    </span>
                @else
                    <span class="text-xl font-bold text-gray-800">
                        {{ number_format($offer->price, 0, ',', ' ') }} FCFA
                    </span>
                @endif
            </div>

            @if($showActions && !$isAdmin)
                <div class="flex gap-2">
                    @if(Auth::check())
                        <form action="{{ url('/wish-list') }}" method="POST">
                            @csrf
                            <input type="hidden" name="offer_id" value="{{ $offer->id }}">
                            <button type="submit" class="text-red-500 hover:text-red-600 transition-colors" title="Ajouter aux favoris">
                                <i class="far fa-heart fa-lg"></i>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-red-500 hover:text-red-600 transition-colors" title="Ajouter aux favoris">
                            <i class="far fa-heart fa-lg"></i>
                        </a>
                    @endif
                </div>
            @endif
        </div>

        @if($isAdmin)
            <div class="mt-3 pt-3 border-t border-gray-200 flex items-center justify-between text-xs text-gray-500">
                <span><i class="fas fa-eye"></i> {{ $offer->views }} vues</span>
                <span class="px-2 py-1 rounded {{ $offer->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                    {{ ucfirst($offer->status) }}
                </span>
            </div>
        @endif
    </div>
</div>