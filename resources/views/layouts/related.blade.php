@php
    // Better logic for related products
    if(request()->is('product/details/*') && isset($offer)) {
        // Get similar products from same category, excluding current product
        $relatedOffers = App\Models\Offers::where('category', $offer->category)
            ->where('id', '!=', $offer->id)
            ->where('status', 'active')
            ->inStock()
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();
        
        // If not enough in same category, fill with random products
        if($relatedOffers->count() < 4) {
            $additionalOffers = App\Models\Offers::where('id', '!=', $offer->id)
                ->where('status', 'active')
                ->inStock()
                ->inRandomOrder()
                ->take(8 - $relatedOffers->count())
                ->get();
            $relatedOffers = $relatedOffers->merge($additionalOffers);
        }
        
        $sectionTitle = 'Produits Similaires';
        $sectionDescription = 'Découvrez nos produits similaires de la catégorie "' . $offer->category . '". Des articles qui pourraient également vous intéresser avec la même qualité et performance.';
    } else {
        // Get recommended products (featured, popular, or recent)
        $relatedOffers = App\Models\Offers::where('status', 'active')
            ->inStock()
            ->where(function($query) {
                $query->where('featured', true)
                      ->orWhere('views', '>', 10);
            })
            ->orderByDesc('featured')
            ->orderByDesc('views')
            ->orderByDesc('created_at')
            ->take(8)
            ->get();
        
        // If not enough featured/popular, fill with recent products
        if($relatedOffers->count() < 4) {
            $additionalOffers = App\Models\Offers::where('status', 'active')
                ->inStock()
                ->whereNotIn('id', $relatedOffers->pluck('id'))
                ->orderByDesc('created_at')
                ->take(8 - $relatedOffers->count())
                ->get();
            $relatedOffers = $relatedOffers->merge($additionalOffers);
        }
        
        $sectionTitle = 'Produits Recommandés';
        $sectionDescription = 'Découvrez notre sélection de produits populaires et tendances. Profitez des meilleures offres sur des articles soigneusement sélectionnés pour vous.';
    }
@endphp

@if($relatedOffers && $relatedOffers->count() > 0)
<div class="container-fluid related-product py-5">
    <div class="container py-4">
        <!-- Section Header -->
        <div class="mx-auto text-center pb-4" style="max-width: 800px;">
            <h4 class="text-primary mb-3 border-bottom border-primary border-2 d-inline-block p-2 title-border-radius wow fadeInUp" data-wow-delay="0.1s">
                <i class="fas fa-star me-2"></i>{{ $sectionTitle }}
            </h4>
            <p class="text-muted wow fadeInUp" data-wow-delay="0.2s">{{ $sectionDescription }}</p>
        </div>

        <!-- Mobile: Horizontal Scroll | Desktop: Grid -->
        <div class="related-products-wrapper">
            <div class="related-products-container">
                @foreach ($relatedOffers as $index => $relatedOffer)
                    <div class="related-product-card wow fadeInUp" data-wow-delay="{{ 0.1 * ($index + 1) }}s">
                        <div class="card h-100 border-0 shadow-sm">
                            <!-- Product Image -->
                            <div class="position-relative overflow-hidden">
                                @php
                                    $images = is_string($relatedOffer->images) ? json_decode($relatedOffer->images, true) : $relatedOffer->images;
                                    $firstImage = $images && is_array($images) && count($images) > 0 
                                        ? '/storage/offer_img/product' . $relatedOffer->id . "/" . $images[0]
                                        : '/img/default-product.jpg';
                                @endphp
                                <img src="{{ asset($firstImage) }}" 
                                     class="card-img-top" 
                                     alt="{{ $relatedOffer->name }}"
                                     loading="lazy"
                                     style="height: 200px; object-fit: cover;">
                                
                                <!-- Badges -->
                                <div class="position-absolute top-0 start-0 m-2">
                                    @if($relatedOffer->created_at >= now()->subDays(30))
                                        <span class="badge bg-success">Nouveau</span>
                                    @endif
                                    @if($relatedOffer->discount_percentage > 0)
                                        <span class="badge bg-danger ms-1">-{{ $relatedOffer->discount_percentage }}%</span>
                                    @endif
                                    @if($relatedOffer->featured)
                                        <span class="badge bg-warning text-dark ms-1">⭐ Vedette</span>
                                    @endif
                                </div>

                                <!-- Quick View Button -->
                                <div class="position-absolute bottom-0 end-0 m-2">
                                    <a href="{{ url('/product/details/' . $relatedOffer->id) }}" 
                                       class="btn btn-light btn-sm rounded-circle"
                                       style="width: 40px; height: 40px; padding: 0;"
                                       title="Voir les détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>

                            <!-- Product Info -->
                            <div class="card-body d-flex flex-column p-3">
                                <!-- Category -->
                                <a href="{{ route('shop') }}?category={{ $relatedOffer->category }}" 
                                   class="text-muted text-decoration-none small mb-2">
                                    <i class="fas fa-tag me-1"></i>{{ $relatedOffer->category }}
                                </a>

                                <!-- Product Name -->
                                <a href="{{ url('/product/details/' . $relatedOffer->id) }}" 
                                   class="text-dark text-decoration-none fw-bold mb-2 flex-grow-1"
                                   style="font-size: 0.95rem; line-height: 1.3; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    {{ $relatedOffer->name }}
                                </a>

                                <!-- Brand -->
                                @if($relatedOffer->brand)
                                    <p class="text-muted small mb-2">
                                        <i class="fas fa-copyright me-1"></i>{{ $relatedOffer->brand }}
                                    </p>
                                @endif

                                <!-- Price -->
                                <div class="mb-3">
                                    @if($relatedOffer->discount_percentage > 0)
                                        <del class="text-muted small d-block">{{ number_format($relatedOffer->price, 0, '.', ',') }} FCFA</del>
                                        <span class="text-danger fw-bold fs-5">{{ number_format($relatedOffer->discounted_price, 0, '.', ',') }} FCFA</span>
                                    @else
                                        <span class="text-primary fw-bold fs-5">{{ number_format($relatedOffer->price, 0, '.', ',') }} FCFA</span>
                                    @endif
                                </div>

                                <!-- Add to Cart Button -->
                                <div class="mt-auto">
                                    @if($relatedOffer->quantity > 0)
                                        @auth
                                            <a href="{{ url('/cart/' . $relatedOffer->id . '/create/' . Auth::id()) }}" 
                                               class="btn btn-primary btn-sm w-100">
                                                <i class="fas fa-shopping-cart me-1"></i>Ajouter
                                            </a>
                                        @else
                                            <a href="{{ route('login') }}" 
                                               class="btn btn-primary btn-sm w-100">
                                                <i class="fas fa-shopping-cart me-1"></i>Ajouter
                                            </a>
                                        @endauth
                                    @else
                                        <button class="btn btn-secondary btn-sm w-100" disabled>
                                            <i class="fas fa-times me-1"></i>Rupture
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- View All Button -->
        <div class="text-center mt-4">
            <a href="{{ route('shop') }}" class="btn btn-primary rounded-pill py-3 px-5">
                <i class="fas fa-th me-2"></i>Voir tous les produits
            </a>
        </div>
    </div>
</div>
@endif