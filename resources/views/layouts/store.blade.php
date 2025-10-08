<div class="store-sidebar m">
<div class="store-search mb-4 mx-3">
    <form action="{{ route('search') }}" method="GET">
        <div class="input-group w-100 mx-auto d-flex">
            <input 
                type="search" 
                class="form-control p-3" 
                name="search" 
                placeholder="Rechercher un produit ..." 
                value="{{ request('search') }}" 
                aria-describedby="search-icon-1"
            >
            <button type="submit" class="input-group-text p-3" id="search-icon-1" style="border: 0; background: rgb(167, 165, 165);">
                <i class="fa fa-search"></i>
            </button>
        </div>
    </form>
</div>
    <div class="product-categories mb-4">
        <h4>Catégories de produits</h4>
        <ul class="list-unstyled">
            @foreach ($categories as $category)
                <li>
                    <div class="categories-item">
                        <a href="#" class="text-dark"><i class="fas fa-apple-alt text-secondary me-2"></i> {{$category->name}}</a>
                        <span>({{ $offers->where('category', $category->name)->count() }})</span>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
    <div class="featured-product mb-4">
        <h4 class="mb-3">Produits en vedette</h4>
        @php
            $randomOffers = $offers->shuffle()->take(3);
        @endphp
        @foreach ($randomOffers as $offer)
            <div class="featured-product-item">
                <div class="rounded me-4" style="width: 100px; height: 100px;">
                    <img src={{'/storage/offer_img/product' . $offer->id . "/" . json_decode($offer->images, true)[0]}} class="img-fluid rounded" alt="Image">
                </div>
                <div>
                    <h6 class="mb-2">{{$offer->name}}</h6>
                    <div class="d-flex mb-2">
                        <i class="fa fa-star text-secondary"></i>
                        <i class="fa fa-star text-secondary"></i>
                        <i class="fa fa-star text-secondary"></i>
                        <i class="fa fa-star text-secondary"></i>
                        <i class="fa fa-star"></i>
                    </div>
                    <div class="d-flex mb-2">
                        <h5 class="fw-bold me-2">{{ number_format($offer->price, 0, '.', ',')}}F</h5>
                        <h5 class="text-danger text-decoration-line-through">{{ number_format(($offer->price) + ($offer->price * 0.15), 0, '.', ',') }}F</h5>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="product-tags py-4">
        <h4 class="mb-3">ÉTIQUETTES DE PRODUIT</h4>
        <div class="product-tags-items bg-light rounded p-3">
            <a href="#" class="border rounded py-1 px-2 mb-2">Nouveau</a>
            <a href="#" class="border rounded py-1 px-2 mb-2">Marque</a>
            <a href="#" class="border rounded py-1 px-2 mb-2">Noir</a>
            <a href="#" class="border rounded py-1 px-2 mb-2">Blanc</a>
            <a href="#" class="border rounded py-1 px-2 mb-2">Tablettes</a>
            <a href="#" class="border rounded py-1 px-2 mb-2">Téléphone</a>
            <a href="#" class="border rounded py-1 px-2 mb-2">Caméra</a>
            <a href="#" class="border rounded py-1 px-2 mb-2">Drone</a>
            <a href="#" class="border rounded py-1 px-2 mb-2">Télévision</a>
            <a href="#" class="border rounded py-1 px-2 mb-2">Soldes</a>
        </div>
    </div>
    {{-- Slot for dynamic elements --}}
    @yield('sidebar-dynamic')
</div>
