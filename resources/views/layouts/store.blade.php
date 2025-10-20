<!-- Search Box -->
<div class="filter-section store-search mb-4">
    <h5 class="mb-3"><i class="fas fa-search me-2"></i>Rechercher</h5>
    <div class="filter-content">
        <form action="{{ route('shop') }}" method="GET" id="searchForm">
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
</div>

<!-- Filter Form -->
<form action="{{ route('shop') }}" method="GET" id="filterForm">
        <!-- Preserve search query -->
        @if(request('search'))
            <input type="hidden" name="search" value="{{ request('search') }}">
        @endif

        <!-- Categories Filter -->
        <div class="filter-section product-categories mb-4">
            <h5 class="mb-3"><i class="fas fa-list me-2"></i>Catégories</h5>
            <div class="filter-content">
                <ul class="list-unstyled">
                <li class="mb-2">
                    <div class="form-check categories-item">
                        <input class="form-check-input filter-checkbox" type="radio" name="category" value="" id="cat-all" {{ !request('category') ? 'checked' : '' }}>
                        <label class="form-check-label d-flex justify-content-between w-100" for="cat-all">
                            <span><i class="fas fa-th text-secondary me-2"></i>Toutes les catégories</span>
                            <span class="badge bg-primary">{{ $offers->total() }}</span>
                        </label>
                    </div>
                </li>
                @foreach ($categories as $category)
                    <li class="mb-2">
                        <div class="form-check categories-item">
                            <input class="form-check-input filter-checkbox" type="radio" name="category" value="{{ $category->name }}" id="cat-{{ $category->id }}" {{ request('category') == $category->name ? 'checked' : '' }}>
                            <label class="form-check-label d-flex justify-content-between w-100" for="cat-{{ $category->id }}">
                                <span><i class="fas fa-tag text-secondary me-2"></i>{{ $category->name }}</span>
                                <span class="badge bg-secondary">{{ $categoryCounts[$category->name] ?? 0 }}</span>
                            </label>
                        </div>
                    </li>
                @endforeach
            </ul>
            </div>
        </div>

        <!-- Price Range Filter -->
        <div class="filter-section price-filter mb-4">
            <h5 class="mb-3"><i class="fas fa-dollar-sign me-2"></i>Fourchette de prix</h5>
            <div class="filter-content">
                <div class="px-3">
                <div class="mb-3">
                    <label for="min_price" class="form-label small">Prix minimum</label>
                    <input type="number" class="form-control form-control-sm" id="min_price" name="min_price" value="{{ request('min_price', $minPrice) }}" min="{{ $minPrice }}" max="{{ $maxPrice }}" placeholder="{{ number_format($minPrice, 0) }}">
                </div>
                <div class="mb-3">
                    <label for="max_price" class="form-label small">Prix maximum</label>
                    <input type="number" class="form-control form-control-sm" id="max_price" name="max_price" value="{{ request('max_price', $maxPrice) }}" min="{{ $minPrice }}" max="{{ $maxPrice }}" placeholder="{{ number_format($maxPrice, 0) }}">
                </div>
                <div class="text-center small text-muted">
                    {{ number_format($minPrice, 0) }} FCFA - {{ number_format($maxPrice, 0) }} FCFA
                </div>
                </div>
            </div>
        </div>

        <!-- Brand Filter -->
        @if($brands && $brands->count() > 0)
        <div class="filter-section brand-filter mb-4">
            <h5 class="mb-3"><i class="fas fa-copyright me-2"></i>Marques</h5>
            <div class="filter-content">
                <div class="px-3" style="max-height: 200px; overflow-y: auto;">
                <div class="form-check mb-2">
                    <input class="form-check-input filter-checkbox" type="radio" name="brand" value="" id="brand-all" {{ !request('brand') ? 'checked' : '' }}>
                    <label class="form-check-label" for="brand-all">
                        Toutes les marques
                    </label>
                </div>
                @foreach($brands as $brand)
                    <div class="form-check mb-2">
                        <input class="form-check-input filter-checkbox" type="radio" name="brand" value="{{ $brand }}" id="brand-{{ Str::slug($brand) }}" {{ request('brand') == $brand ? 'checked' : '' }}>
                        <label class="form-check-label" for="brand-{{ Str::slug($brand) }}">
                            {{ $brand }}
                        </label>
                    </div>
                @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Quick Filters -->
        <div class="filter-section quick-filters mb-4">
            <h5 class="mb-3"><i class="fas fa-filter me-2"></i>Filtres rapides</h5>
            <div class="filter-content">
                <div class="px-3">
                <div class="form-check mb-2">
                    <input class="form-check-input filter-checkbox" type="checkbox" name="featured" value="1" id="filter-featured" {{ request('featured') == '1' ? 'checked' : '' }}>
                    <label class="form-check-label" for="filter-featured">
                        <i class="fas fa-star text-warning me-2"></i>Produits vedettes
                    </label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input filter-checkbox" type="checkbox" name="new_arrivals" value="1" id="filter-new" {{ request('new_arrivals') == '1' ? 'checked' : '' }}>
                    <label class="form-check-label" for="filter-new">
                        <i class="fas fa-sparkles text-success me-2"></i>Nouveautés
                    </label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input filter-checkbox" type="checkbox" name="on_sale" value="1" id="filter-sale" {{ request('on_sale') == '1' ? 'checked' : '' }}>
                    <label class="form-check-label" for="filter-sale">
                        <i class="fas fa-tag text-danger me-2"></i>En promotion
                    </label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input filter-checkbox" type="checkbox" name="in_stock" value="1" id="filter-stock" {{ request('in_stock') == '1' ? 'checked' : '' }}>
                    <label class="form-check-label" for="filter-stock">
                        <i class="fas fa-check-circle text-info me-2"></i>En stock
                    </label>
                </div>
                </div>
            </div>
        </div>

        <!-- Filter Buttons -->
        <div class="filter-section filter-buttons px-3 mb-4">
            <div class="filter-content">
            <button type="submit" class="btn btn-primary w-100 mb-2">
                <i class="fas fa-check me-2"></i>Appliquer les filtres
            </button>
            <a href="{{ route('shop') }}" class="btn btn-outline-secondary w-100">
                <i class="fas fa-redo me-2"></i>Réinitialiser
            </a>
            </div>
        </div>
    </form>

    <!-- Featured Products -->
    <div class="filter-section featured-product mb-4">
        <h5 class="mb-3"><i class="fas fa-fire text-danger me-2"></i>Produits populaires</h5>
        <div class="filter-content">
        @php
            $featuredProducts = \App\Models\offers::where('status', 'active')
                ->where('featured', true)
                ->orWhere('discount_percentage', '>', 0)
                ->orderBy('views', 'desc')
                ->take(3)
                ->get();
        @endphp
        @if($featuredProducts->count() > 0)
            @foreach ($featuredProducts as $offer)
                <div class="featured-product-item mb-3">
                    <a href="{{ url('/shop/' . $offer->id) }}" class="d-flex text-decoration-none">
                        <div class="rounded me-3 position-relative" style="width: 80px; height: 80px; flex-shrink: 0;">
                            @php
                                $images = is_string($offer->images) ? json_decode($offer->images, true) : $offer->images;
                                $firstImage = $images && is_array($images) && count($images) > 0 
                                    ? '/storage/offer_img/product' . $offer->id . "/" . $images[0]
                                    : '/img/default-product.jpg';
                            @endphp
                            <img src="{{ asset($firstImage) }}" class="img-fluid rounded" style="width: 100%; height: 100%; object-fit: cover;" alt="{{ $offer->name }}">
                            @if($offer->discount_percentage > 0)
                                <span class="badge bg-danger position-absolute top-0 start-0 m-1">-{{ $offer->discount_percentage }}%</span>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 text-dark">{{ Str::limit($offer->name, 40) }}</h6>
                            <div class="d-flex align-items-center mb-1">
                                @for($i = 0; $i < 5; $i++)
                                    <i class="fa fa-star {{ $i < 4 ? 'text-secondary' : 'text-muted' }} fa-xs"></i>
                                @endfor
                            </div>
                            <div class="d-flex align-items-center">
                                @if($offer->discount_percentage > 0)
                                    <span class="text-decoration-line-through text-muted me-2 small">{{ number_format($offer->price, 0) }}F</span>
                                    <span class="text-danger fw-bold">{{ number_format($offer->discounted_price, 0) }}F</span>
                                @else
                                    <span class="text-primary fw-bold">{{ number_format($offer->price, 0) }}F</span>
                                @endif
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        @endif
        </div>
    </div>

<script>
    // Auto-submit form when filter changes
    document.querySelectorAll('.filter-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    });

    // Auto-submit on price range change with debounce
    let priceTimeout;
    document.querySelectorAll('#min_price, #max_price').forEach(input => {
        input.addEventListener('input', function() {
            clearTimeout(priceTimeout);
            priceTimeout = setTimeout(() => {
                document.getElementById('filterForm').submit();
            }, 1000);
        });
    });
</script>

<style>
    .categories-item {
        padding: 8px 12px;
        border-radius: 6px;
        transition: all 0.3s;
    }
    .categories-item:hover {
        background-color: #f8f9fa;
    }
    .form-check-input:checked + .form-check-label {
        color: #0d6efd;
        font-weight: 600;
    }
    
    /* Filter Section Styles */
    .filter-section {
        background: white;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .filter-section h5 {
        font-size: 1.1rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #f8f9fa;
    }
    
    .filter-content {
        animation: fadeIn 0.3s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    /* Mobile collapsible enhancement */
    @media (max-width: 991px) {
        .filter-section {
            margin-bottom: 0.75rem;
        }
        
        .filter-section h5 {
            margin-bottom: 0;
            cursor: pointer;
            user-select: none;
        }
        
        .filter-section.collapsed .filter-content {
            display: none;
        }
        
        .filter-section:not(.collapsed) h5::after {
            content: '\f077'; /* fa-chevron-up */
        }
    }
</style>
