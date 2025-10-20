@php
    if(request()->is('product/details/*')){
        $relatedOffers = App\Models\Offers::where('category', $offer->category)->where('id', '!=', $offer->id)->take(4)->get();
    } else {
        $relatedOffers = App\Models\Offers::inRandomOrder()->take(4)->get();
    }
@endphp 

 <div class="container-fluid related-product">
    <div class="container">
        <div class="mx-auto text-center pb-5" style="max-width: 700px;">
            <h4 class="text-primary mb-4 border-bottom border-primary border-2 d-inline-block p-2 title-border-radius wow fadeInUp" data-wow-delay="0.1s">
                @if (request()->is('product/details/*'))
                    Produit Similaire
                @else
                    Produit Recommandés
                @endif
            </h4>
            <p class="wow fadeInUp" data-wow-delay="0.2s">
                @if (request()->is('product/details/*'))
                    Découvrez nos produits similaires soigneusement sélectionnés pour répondre à vos besoins. Profitez de la qualité, de la performance et des meilleures offres sur des articles qui pourraient également vous intéresser.
                @else
                    Découvrez nos produits recommandés soigneusement sélectionnés pour répondre à vos besoins. Profitez de la qualité, de la performance et des meilleures offres sur des articles qui pourraient également vous intéresser.
                @endif
            </p>
        </div>
        <div class="related-carousel owl-carousel pt-4">
            @foreach ($relatedOffers as $relatedOffer)
                <div class="related-item rounded">
                    <div class="related-item-inner border rounded">
                        <div class="related-item-inner-item">
                            @php
                                $images = is_string($relatedOffer->images) ? json_decode($relatedOffer->images, true) : $relatedOffer->images;
                                $firstImage = $images && is_array($images) && count($images) > 0 
                                    ? '/storage/offer_img/product' . $relatedOffer->id . "/" . $images[0]
                                    : '/img/default-product.jpg';
                            @endphp
                            <img src="{{ asset($firstImage) }}" class="img-fluid w-100 rounded-top" alt="{{ $relatedOffer->name }}">
                                @if ($relatedOffer->created_at >= now()->subMonths())
                                <div class="related-new">New</div>
                            @endif
                            <div class="related-details">
                                <a href="{{ url('/product/details/' . $relatedOffer->id) }}"><i class="fa fa-eye fa-1x"></i></a>
                            </div>
                        </div>
                        <div class="text-center rounded-bottom p-4">
                            <a href="{{ route('shop') }}?category={{ $relatedOffer->category }}" class="d-block mb-2 text-muted">
                                <i class="fas fa-tag me-1"></i>{{ $relatedOffer->category }}
                            </a>
                            <a href="{{ url('/product/details/' . $relatedOffer->id) }}" class="d-block h5 text-dark text-decoration-none">
                                {{ Str::limit($relatedOffer->name, 40) }}
                            </a>
                            <div class="mt-2">
                                @if($relatedOffer->discount_percentage > 0)
                                    <del class="me-2 fs-6 text-muted">{{ number_format($relatedOffer->price, 0, '.', ',') }} FCFA</del>
                                    <span class="text-danger fw-bold fs-5">{{ number_format($relatedOffer->discounted_price, 0, '.', ',') }} FCFA</span>
                                @else
                                    <span class="text-primary fw-bold fs-5">{{ number_format($relatedOffer->price, 0, '.', ',') }} FCFA</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

