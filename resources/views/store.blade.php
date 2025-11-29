@extends('layouts.web')
@section('content')

<!-- Mobile Menu Toggle Button -->
{{-- <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle Filter Menu">
    <i class="fas fa-filter"></i>
</button> --}}

<!-- Sidebar Overlay for Mobile -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Single Page Header start -->
        <div class="container-fluid page-header py-5">
            @if(request('search'))
                <h1 class="text-center text-white display-6 wow fadeInUp" data-wow-delay="0.1s">
                    Résultats de recherche: {{ request('search') }}
                </h1>
            @else
                <h1 class="text-center text-white display-6 wow fadeInUp" data-wow-delay="0.1s">Boutique</h1>
            @endif
            <ol class="breadcrumb justify-content-center mb-0 wow fadeInUp" data-wow-delay="0.3s">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Accueil</a></li>
                <li class="breadcrumb-item"><a href="{{ url('/shop') }}">Boutique</a></li>
                @if(request('search'))
                    <li class="breadcrumb-item active text-white">Résultats de recherche</li>
                @else
                    <li class="breadcrumb-item active text-white">Boutique</li>
                @endif
            </ol>
        </div>
        <!-- Single Page Header End -->

        <!-- Searvices Start -->
        <div class="container-fluid px-0">
        <div class="row g-0">
        <div class="container-fluid shop py-5">
            <div class="container py-5">
                <div class="row g-4">
                    <div class="col-lg-3 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="store-sidebar" id="storeSidebar">
                            <button class="sidebar-close d-lg-none" id="sidebarClose" aria-label="Close Filters">
                                <i class="fas fa-times"></i>
                            </button>
                            @include('layouts.store')
                        </div>
                    </div>
                    <div class="col-lg-9 wow fadeInUp" data-wow-delay="0.1s">
                        <!-- Results Header with Sorting -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="results-header d-flex flex-wrap justify-content-between align-items-center bg-light rounded p-3">
                                    <div class="mb-2 mb-md-0">
                                        <h5 class="mb-0">
                                            @if(request('search'))
                                                <i class="fas fa-search me-2"></i><span class="d-none d-md-inline">Résultats pour</span> "{{ request('search') }}"
                                            @elseif(request('category'))
                                                <i class="fas fa-tag me-2"></i>{{ request('category') }}
                                            @else
                                                <i class="fas fa-shopping-bag me-2"></i>Tous les produits
                                            @endif
                                            <span class="badge bg-primary ms-2">{{ method_exists($offers, 'total') ? $offers->total() : $offers->count() }}</span>
                                        </h5>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 w-100 w-md-auto mt-2 mt-md-0">
                                        <!-- Mobile Filter Toggle -->
                                        <button class="btn btn-outline-primary d-lg-none flex-grow-1" id="mobileFilterBtn" type="button">
                                            <i class="fas fa-sliders-h me-2"></i>Filtres
                                        </button>
                                        
                                        <!-- Sort Form -->
                                        <form action="{{ route('shop') }}" method="GET" id="sortForm" class="flex-grow-1">
                                            <!-- Preserve all filters -->
                                            @foreach(request()->except('sort') as $key => $value)
                                                @if(is_array($value))
                                                    @foreach($value as $v)
                                                        <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                                                    @endforeach
                                                @else
                                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                                @endif
                                            @endforeach
                                            
                                            <label for="sort-select" class="me-2 small d-none d-md-inline">Trier:</label>
                                            <select name="sort" id="sort-select" class="form-select form-select-sm w-100" onchange="this.form.submit()">
                                                <option value="">Trier par...</option>
                                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Prix ↑</option>
                                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Prix ↓</option>
                                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nom A-Z</option>
                                                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nom Z-A</option>
                                                <option value="popularity" {{ request('sort') == 'popularity' ? 'selected' : '' }}>Populaire</option>
                                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Nouveau</option>
                                            </select>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Active Filters Display -->
                        @if(request()->hasAny(['search', 'category', 'brand', 'min_price', 'max_price', 'featured', 'new_arrivals', 'on_sale', 'in_stock']))
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="d-flex flex-wrap align-items-center bg-white border rounded p-3">
                                    <span class="me-2 fw-bold"><i class="fas fa-filter me-2"></i>Filtres actifs:</span>
                                    
                                    @if(request('search'))
                                        <span class="badge bg-primary me-2 mb-2">
                                            <i class="fas fa-search me-1"></i>{{ request('search') }}
                                            <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="text-white ms-1">×</a>
                                        </span>
                                    @endif
                                    
                                    @if(request('category'))
                                        <span class="badge bg-info me-2 mb-2">
                                            <i class="fas fa-tag me-1"></i>{{ request('category') }}
                                            <a href="{{ request()->fullUrlWithQuery(['category' => null]) }}" class="text-white ms-1">×</a>
                                        </span>
                                    @endif
                                    
                                    @if(request('brand'))
                                        <span class="badge bg-secondary me-2 mb-2">
                                            <i class="fas fa-copyright me-1"></i>{{ request('brand') }}
                                            <a href="{{ request()->fullUrlWithQuery(['brand' => null]) }}" class="text-white ms-1">×</a>
                                        </span>
                                    @endif
                                    
                                    @if(request('min_price') || request('max_price'))
                                        <span class="badge bg-success me-2 mb-2">
                                            <i class="fas fa-dollar-sign me-1"></i>{{ number_format(request('min_price', $minPrice), 0) }}F - {{ number_format(request('max_price', $maxPrice), 0) }}F
                                            <a href="{{ request()->fullUrlWithQuery(['min_price' => null, 'max_price' => null]) }}" class="text-white ms-1">×</a>
                                        </span>
                                    @endif
                                    
                                    @if(request('featured'))
                                        <span class="badge bg-warning me-2 mb-2">
                                            <i class="fas fa-star me-1"></i>Vedette
                                            <a href="{{ request()->fullUrlWithQuery(['featured' => null]) }}" class="text-white ms-1">×</a>
                                        </span>
                                    @endif
                                    
                                    @if(request('new_arrivals'))
                                        <span class="badge bg-success me-2 mb-2">
                                            <i class="fas fa-sparkles me-1"></i>Nouveautés
                                            <a href="{{ request()->fullUrlWithQuery(['new_arrivals' => null]) }}" class="text-white ms-1">×</a>
                                        </span>
                                    @endif
                                    
                                    @if(request('on_sale'))
                                        <span class="badge bg-danger me-2 mb-2">
                                            <i class="fas fa-tag me-1"></i>En promo
                                            <a href="{{ request()->fullUrlWithQuery(['on_sale' => null]) }}" class="text-white ms-1">×</a>
                                        </span>
                                    @endif
                                    
                                    @if(request('in_stock'))
                                        <span class="badge bg-info me-2 mb-2">
                                            <i class="fas fa-check-circle me-1"></i>En stock
                                            <a href="{{ request()->fullUrlWithQuery(['in_stock' => null]) }}" class="text-white ms-1">×</a>
                                        </span>
                                    @endif
                                    
                                    <a href="{{ route('shop') }}" class="btn btn-sm btn-outline-danger ms-auto mb-2">
                                        <i class="fas fa-times me-1"></i>Tout effacer
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Banner -->
                        <div class="rounded mb-4 position-relative">
                            <img src="{{ asset('img/product-banner-3.jpg') }}" class="img-fluid rounded w-100" style="height: 250px; object-fit: cover;" alt="Banner">
                            <div class="position-absolute rounded d-flex flex-column align-items-center justify-content-center text-center" style="width: 100%; height: 250px; top: 0; left: 0; background: rgba(242, 139, 0, 0.3);">
                                <h4 class="display-5 text-primary">SOLDES</h4>
                                <h3 class="display-4 text-white mb-4">Jusqu'à -50%</h3>
                            </div>
                        </div>
                        
                        <!-- Products Grid -->
                        <div class="tab-content">
                            <div id="tab-5" class="tab-pane fade show p-0 active">
                                <div class="row g-4 product">
                                    @if($offers->count() > 0)
                                        @foreach ($offers as $offer)
                                            <div class="col-lg-4 col-md-6 col-sm-12">
                                                <div class="product-item rounded wow fadeInUp" data-wow-delay="0.1s">
                                                    <div class="product-item-inner border rounded position-relative">
                                                        <div class="product-item-inner-item">
                                                            @php
                                                                $images = is_string($offer->images) ? json_decode($offer->images, true) : $offer->images;
                                                                $firstImage = $images && is_array($images) && count($images) > 0 
                                                                    ? '/storage/offer_img/product' . $offer->id . "/" . $images[0]
                                                                    : '/img/default-product.jpg';
                                                            @endphp
                                                            <img src="{{ asset($firstImage) }}" 
                                                                 class="img-fluid w-100 rounded-top" 
                                                                 style="height: 250px; object-fit: cover;" 
                                                                 alt="{{ $offer->name }}"
                                                                 loading="lazy">
                                                            
                                                            <!-- Badges -->
                                                            <div class="position-absolute top-0 start-0 m-2">
                                                                @if($offer->featured)
                                                                    <span class="badge bg-warning text-dark mb-1 d-block">
                                                                        <i class="fas fa-star me-1"></i>Vedette
                                                                    </span>
                                                                @endif
                                                                @if($offer->discount_percentage > 0)
                                                                    <span class="badge bg-danger mb-1 d-block">-{{ $offer->discount_percentage }}%</span>
                                                                @endif
                                                                @if($offer->created_at >= now()->subDays(30))
                                                                    <span class="badge bg-success mb-1 d-block">Nouveau</span>
                                                                @endif
                                                            </div>
                                                            
                                                            <!-- Stock Badge -->
                                                            @if($offer->quantity == 0)
                                                                <div class="position-absolute top-0 end-0 m-2">
                                                                    <span class="badge bg-secondary">Rupture de stock</span>
                                                                </div>
                                                            @elseif($offer->quantity < 10)
                                                                <div class="position-absolute top-0 end-0 m-2">
                                                                    <span class="badge bg-warning text-dark">Stock limité</span>
                                                                </div>
                                                            @endif
                                                            
                                                            <div class="product-details">
                                                                <a href="{{ url('/product/details/' . $offer->id) }}"><i class="fa fa-eye fa-1x"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="text-center rounded-bottom p-4">
                                                            <a href="{{ route('shop') }}?category={{ $offer->category }}" class="d-block mb-2 text-muted small">
                                                                <i class="fas fa-tag me-1"></i>{{ $offer->category }}
                                                            </a>
                                                            <a href="{{ url('/product/details/' . $offer->id) }}" class="d-block h5 text-dark text-decoration-none">
                                                                {{ Str::limit($offer->name, 50) }}
                                                            </a>
                                                            @if($offer->brand)
                                                                <p class="text-muted small mb-2"><i class="fas fa-copyright me-1"></i>{{ $offer->brand }}</p>
                                                            @endif
                                                            <div class="mt-2">
                                                                @if($offer->discount_percentage > 0)
                                                                    <del class="me-2 fs-6 text-muted">{{ number_format($offer->price, 0, '.', ',') }} FCFA</del>
                                                                    <span class="text-danger fw-bold fs-5">{{ number_format($offer->discounted_price, 0, '.', ',') }} FCFA</span>
                                                                @else
                                                                    <span class="text-primary fw-bold fs-5">{{ number_format($offer->price, 0, '.', ',') }} FCFA</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="product-item-add border border-top-0 rounded-bottom text-center p-4 pt-0">
                                                        @if($offer->quantity > 0)
                                                            @if(Auth::check())
                                                                <a href="{{ route('cart.add', $offer->id) }}" class="btn btn-primary border-secondary rounded-pill py-2 px-4 mb-3 w-100">
                                                                    <i class="fas fa-shopping-cart me-2"></i>Ajouter au panier
                                                                </a>
                                                            @else
                                                                <a href="{{ route('login') }}" class="btn btn-primary border-secondary rounded-pill py-2 px-4 mb-3 w-100">
                                                                    <i class="fas fa-shopping-cart me-2"></i>Ajouter au panier
                                                                </a>
                                                            @endif
                                                        @else
                                                            <button class="btn btn-secondary border-secondary rounded-pill py-2 px-4 mb-3 w-100" disabled>
                                                                <i class="fas fa-times me-2"></i>Indisponible
                                                            </button>
                                                        @endif
                                                        
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div class="d-flex">
                                                                @for($i = 0; $i < 5; $i++)
                                                                    <i class="fas fa-star {{ $i < 4 ? 'text-primary' : 'text-muted' }} fa-xs"></i>
                                                                @endfor
                                                            </div>
                                                            <div class="d-flex align-items-center">
                                                                <span class="text-muted small me-3">
                                                                    <i class="fas fa-eye me-1"></i>{{ $offer->views }}
                                                                </span>
                                                                @if(Auth::check())
                                                                    <a href="{{ route('wishlist.add', $offer->id) }}" class="text-danger">
                                                                        <i class="fas fa-heart fa-lg"></i>
                                                                    </a>
                                                                @else
                                                                    <a href="{{ route('login') }}" class="text-danger">
                                                                        <i class="fas fa-heart fa-lg"></i>
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="col-12 text-center py-5">
                                            <div class="bg-light rounded p-5">
                                                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                                <h3 class="text-muted">Aucun produit trouvé</h3>
                                                @if(request('search'))
                                                    <p class="mt-3">Aucun résultat trouvé pour "<strong>{{ request('search') }}</strong>"</p>
                                                @endif
                                                <a href="{{ route('shop') }}" class="btn btn-primary mt-3">
                                                    <i class="fas fa-shopping-bag me-2"></i>Voir tous les produits
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <!-- Pagination -->
                                    @if($offers->hasPages())
                                    <div class="col-12 wow fadeInUp" data-wow-delay="0.1s">
                                        <div class="d-flex justify-content-center mt-4">
                                            {{ $offers->appends(request()->except('page'))->links() }}
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Shop Section End -->
        
        </div>
        </div>
        <!-- Services End -->

        <!-- Related Product Start -->
        @include('layouts.related')
        <!-- Related Product End -->

        <!-- Product Banner Start -->
        <div class="container-fluid py-5">
            <div class="container pb-5">
                <div class="row g-4">
                    <div class="col-lg-6 wow fadeInLeft" data-wow-delay="0.1s">
                        <a href="{{ route('shop') }}?on_sale=1">
                            <div class="bg-primary rounded position-relative">
                                <img src="{{ asset('img/product-banner.jpg') }}" class="img-fluid w-100 rounded" alt="Product Banner">
                                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center rounded p-4" style="background: rgba(255, 255, 255, 0.5);">
                                    <h3 class="display-5 text-primary">Produits <br> <span>Électroniques</span></h3>
                                    <p class="fs-4 text-muted">Jusqu'à -40%</p>
                                    <span class="btn btn-primary rounded-pill align-self-start py-2 px-4">Voir les offres</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-6 wow fadeInRight" data-wow-delay="0.2s">
                        <a href="{{ route('shop') }}?featured=1">
                            <div class="text-center bg-primary rounded position-relative">
                                <img src="{{ asset('img/product-banner-2.jpg') }}" class="img-fluid w-100" alt="Sale Banner">
                                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center rounded p-4" style="background: rgba(242, 139, 0, 0.5);">
                                    <h2 class="display-2 text-secondary">SOLDES</h2>
                                    <h4 class="display-5 text-white mb-4">Jusqu'à -50%</h4>
                                    <span class="btn btn-secondary rounded-pill align-self-center py-2 px-4">Découvrir</span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Product Banner End -->

<!-- Scroll to Top Button -->
<div class="scroll-to-top" id="scrollToTop">
    <i class="fas fa-arrow-up"></i>
</div>

<style>
/* Ensure mobile filter toggle is visible and working */
@media (max-width: 991px) {
    .mobile-menu-toggle {
        display: flex !important;
        align-items: center;
        justify-content: center;
        position: fixed;
        bottom: 80px;
        left: 15px;
        z-index: 9999;
        background: #f28b00;
        color: white;
        border: none;
        width: 56px;
        height: 56px;
        border-radius: 50%;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .mobile-menu-toggle:hover,
    .mobile-menu-toggle:active {
        transform: scale(0.95);
        background: #d17700;
    }
    
    .mobile-menu-toggle i {
        font-size: 24px;
    }
    
    .store-sidebar {
        position: fixed !important;
        left: -100% !important;
        top: 0 !important;
        width: 85% !important;
        max-width: 320px !important;
        height: 100vh !important;
        background: white !important;
        z-index: 9998 !important;
        transition: left 0.3s ease !important;
        overflow-y: auto !important;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1) !important;
        padding: 20px !important;
        padding-top: 80px !important;
    }
    
    .store-sidebar.active {
        left: 0 !important;
    }
    
    .sidebar-overlay {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        height: 100vh !important;
        background: rgba(0, 0, 0, 0.5) !important;
        z-index: 9997 !important;
        display: none !important;
        opacity: 0 !important;
        transition: opacity 0.3s ease !important;
    }
    
    .sidebar-overlay.active {
        display: block !important;
        opacity: 1 !important;
    }
    
    .sidebar-close {
        position: absolute;
        top: 20px;
        right: 20px;
        background: #dc3545;
        color: white;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        z-index: 10;
    }
}

/* Desktop - hide mobile elements */
@media (min-width: 992px) {
    .mobile-menu-toggle {
        display: none !important;
    }
    
    .sidebar-overlay {
        display: none !important;
    }
    
    .sidebar-close {
        display: none !important;
    }
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const mobileFilterBtn = document.getElementById('mobileFilterBtn');
    const storeSidebar = document.getElementById('storeSidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const sidebarClose = document.getElementById('sidebarClose');
    
    // Debug log
    console.log('Filter elements found:', {
        mobileMenuToggle: !!mobileMenuToggle,
        mobileFilterBtn: !!mobileFilterBtn,
        storeSidebar: !!storeSidebar,
        sidebarOverlay: !!sidebarOverlay,
        sidebarClose: !!sidebarClose
    });
    
    // Toggle sidebar
    function toggleSidebar(e) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        }
        console.log('Toggle sidebar clicked!');
        
        if (storeSidebar && sidebarOverlay) {
            storeSidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
            document.body.style.overflow = storeSidebar.classList.contains('active') ? 'hidden' : '';
            console.log('Sidebar active:', storeSidebar.classList.contains('active'));
        }
    }
    
    // Close sidebar
    function closeSidebar(e) {
        if (e) {
            e.preventDefault();
        }
        console.log('Close sidebar');
        
        if (storeSidebar && sidebarOverlay) {
            storeSidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }
    }
    
    // Event listeners for menu toggle
    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', toggleSidebar, false);
        mobileMenuToggle.addEventListener('touchstart', toggleSidebar, false);
        console.log('Mobile menu toggle listener added');
    }
    
    if (mobileFilterBtn) {
        mobileFilterBtn.addEventListener('click', toggleSidebar, false);
        mobileFilterBtn.addEventListener('touchstart', toggleSidebar, false);
        console.log('Mobile filter button listener added');
    }
    
    if (sidebarClose) {
        sidebarClose.addEventListener('click', closeSidebar, false);
        sidebarClose.addEventListener('touchstart', closeSidebar, false);
    }
    
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', closeSidebar, false);
        sidebarOverlay.addEventListener('touchstart', closeSidebar, false);
    }
    
    // Close sidebar on window resize to desktop
    window.addEventListener('resize', function() {
        if (window.innerWidth > 991) {
            closeSidebar();
        }
    });
    
    // Collapsible filter sections on mobile
    if (window.innerWidth < 992) {
        const filterSections = document.querySelectorAll('.filter-section');
        filterSections.forEach((section, index) => {
            const heading = section.querySelector('h5');
            if (heading) {
                heading.style.cursor = 'pointer';
                
                // Collapse all except first section
                if (index > 0) {
                    section.classList.add('collapsed');
                }
                
                heading.addEventListener('click', function() {
                    section.classList.toggle('collapsed');
                });
            }
        });
    }
    
    // Scroll to top button
    const scrollToTop = document.getElementById('scrollToTop');
    
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            scrollToTop.classList.add('show');
        } else {
            scrollToTop.classList.remove('show');
        }
    });
    
    if (scrollToTop) {
        scrollToTop.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
    
    // Smooth scroll behavior for iOS Safari
    if (/iPad|iPhone|iPod/.test(navigator.userAgent)) {
        document.documentElement.style.scrollBehavior = 'smooth';
    }
    
    // Prevent zoom on input focus (iOS)
    const inputs = document.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        if (window.innerWidth < 768) {
            const fontSize = window.getComputedStyle(input).fontSize;
            if (parseFloat(fontSize) < 16) {
                input.style.fontSize = '16px';
            }
        }
    });
    
    // Add touch feedback for buttons
    const buttons = document.querySelectorAll('.btn, .badge a');
    buttons.forEach(button => {
        button.addEventListener('touchstart', function() {
            this.style.transform = 'scale(0.95)';
        });
        button.addEventListener('touchend', function() {
            this.style.transform = 'scale(1)';
        });
    });
    
    // Lazy loading for product images
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        observer.unobserve(img);
                    }
                }
            });
        });
        
        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }
    
    // Handle landscape orientation
    window.addEventListener('orientationchange', function() {
        setTimeout(() => {
            closeSidebar();
        }, 200);
    });
    
    // Performance optimization: debounce scroll events
    let scrollTimeout;
    window.addEventListener('scroll', function() {
        if (scrollTimeout) {
            window.cancelAnimationFrame(scrollTimeout);
        }
        scrollTimeout = window.requestAnimationFrame(function() {
            // Scroll-dependent code here
        });
    }, { passive: true });
    
    // Add swipe gesture to close sidebar
    let touchStartX = 0;
    let touchEndX = 0;
    
    if (storeSidebar) {
        storeSidebar.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });
        
        storeSidebar.addEventListener('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        }, { passive: true });
    }
    
    function handleSwipe() {
        if (touchStartX - touchEndX > 50) {
            // Swipe left - close sidebar
            closeSidebar();
        }
    }
});
</script>
@endpush

@endsection