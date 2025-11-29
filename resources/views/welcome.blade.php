@extends('layouts.web')
@section('content')

        <!-- Carousel Start -->
        <div class="container-fluid carousel bg-light px-0">
            <div class="row g-0 justify-content-end">
            <div class="col-12 col-lg-7 col-xl-9">
                <div class="header-carousel owl-carousel bg-light py-5">
                <div class="row g-0 header-carousel-item align-items-center">
                    <div class="col-xl-6 carousel-img wow fadeInLeft" data-wow-delay="0.1s">
                    <img src="img/carousel-1.png" class="img-fluid w-100" alt="Image">
                    </div>
                    <div class="col-xl-6 carousel-content p-4">
                    <h4 class="text-uppercase fw-bold mb-4 wow fadeInRight" data-wow-delay="0.1s" style="letter-spacing: 3px;">Économisez jusqu'à 400 000 FCFA</h4>
                    <h1 class="display-3 text-capitalize mb-4 wow fadeInRight" data-wow-delay="0.3s">Sur une sélection d'ordinateurs portables, de bureaux ou de smartphones</h1>
                    <p class="text-dark wow fadeInRight" data-wow-delay="0.5s">Conditions générales applicables</p>
                    <a class="btn btn-primary rounded-pill py-3 px-5 wow fadeInRight" data-wow-delay="0.7s" href="{{route("shop")}}">Acheter maintenant</a>
                    @if (!Auth::user())
                        <a class="btn btn-primary rounded-pill py-3 px-5 wow fadeInRight" data-wow-delay="0.7s" href="{{route("login")}}">Se Connecter</a>
                    @endif
                    </div>
                </div>
                <div class="row g-0 header-carousel-item align-items-center">
                    <div class="col-xl-6 carousel-img wow fadeInLeft" data-wow-delay="0.1s">
                    <img src="img/carousel-2.png" class="img-fluid w-100" alt="Image">
                    </div>
                    <div class="col-xl-6 carousel-content p-4">
                    <h4 class="text-uppercase fw-bold mb-4 wow fadeInRight" data-wow-delay="0.1s" style="letter-spacing: 3px;">Économisez jusqu'à 200 000 FCFA</h4>
                    <h1 class="display-3 text-capitalize mb-4 wow fadeInRight" data-wow-delay="0.3s">Sur une sélection d'ordinateurs portables, de bureaux ou de smartphones</h1>
                    <p class="text-dark wow fadeInRight" data-wow-delay="0.5s">Conditions générales applicables</p>
                    <a class="btn btn-primary rounded-pill py-3 px-5 wow fadeInRight" data-wow-delay="0.7s" href="{{route("shop")}}">Acheter maintenant</a>
                    @if (!Auth::user())
                        <a class="btn btn-primary rounded-pill py-3 px-5 wow fadeInRight" data-wow-delay="0.7s" href="{{route("login")}}">Se Connecter</a>
                    @endif
                    </div>
                </div>
                </div>
            </div>
            <div class="col-12 col-lg-5 col-xl-3 wow fadeInRight" data-wow-delay="0.1s">
                <div class="carousel-header-banner h-100">
                <img src="img/header-img.png" class="img-fluid w-100 h-100" style="object-fit: cover;" alt="Image">
                <div class="carousel-banner-offer">
                    <p class="bg-primary text-white rounded fs-5 py-2 px-4 mb-0 me-3">Économisez 25 000 FCFA</p>
                    <p class="text-primary fs-5 fw-bold mb-0">Offre spéciale</p>
                </div>
                <div class="carousel-banner">
                    <div class="carousel-banner-content text-center p-4">
                    <a href="#" class="d-block mb-2">Téléphone</a>
                    <a href="#" class="d-block text-white fs-3">Apple iPad Mini <br> G2356</a>
                    <del class="me-2 text-white fs-5">750 000 FCFA</del>
                    <span class="text-primary fs-5">725 000 FCFA</span>
                    </div>
                    <a href="#" class="btn btn-primary rounded-pill py-2 px-4"><i class="fas fa-shopping-cart me-2"></i> Ajouter au panier</a>
                </div>
                </div>
            </div>
            </div>
        </div>
        <!-- Carousel End -->

        <!-- Services Start -->
        <div class="container-fluid px-0">
            <div class="row g-0">
            <div class="col-6 col-md-4 col-lg-2 border-start border-end wow fadeInUp" data-wow-delay="0.1s">
                <div class="p-4">
                <div class="d-inline-flex align-items-center">
                    <i class="fa fa-sync-alt fa-2x text-primary"></i>
                    <div class="ms-4">
                    <h6 class="text-uppercase mb-2">Retour gratuit</h6>
                    <p class="mb-0">30 jours satisfait ou remboursé !</p>
                    </div>
                </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2 border-end wow fadeInUp" data-wow-delay="0.2s">
                <div class="p-4">
                <div class="d-flex align-items-center">
                    <i class="fab fa-telegram-plane fa-2x text-primary"></i>
                    <div class="ms-4">
                    <h6 class="text-uppercase mb-2">Livraison gratuite</h6>
                    <p class="mb-0">Livraison gratuite sur toutes les commandes à Bafoussam</p>
                    </div>
                </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2 border-end wow fadeInUp" data-wow-delay="0.3s">
                <div class="p-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-life-ring fa-2x text-primary"></i>
                    <div class="ms-4">
                    <h6 class="text-uppercase mb-2">Assistance 24/7</h6>
                    <p class="mb-0">Nous sommes disponibles 24h/24</p>
                    </div>
                </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2 border-end wow fadeInUp" data-wow-delay="0.4s">
                <div class="p-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-credit-card fa-2x text-primary"></i>
                    <div class="ms-4">
                    <h6 class="text-uppercase mb-2">Recevez une carte cadeau</h6>
                    <p class="mb-0">Recevez une carte cadeau pour toute commande supérieure à 30 000 FCFA</p>
                    </div>
                </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2 border-end wow fadeInUp" data-wow-delay="0.5s">
                <div class="p-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-lock fa-2x text-primary"></i>
                    <div class="ms-4">
                    <h6 class="text-uppercase mb-2">Paiement sécurisé</h6>
                    <p class="mb-0">Votre sécurité est notre priorité</p>
                    </div>
                </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2 border-end wow fadeInUp" data-wow-delay="0.6s">
                <div class="p-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-blog fa-2x text-primary"></i>
                    <div class="ms-4">
                    <h6 class="text-uppercase mb-2">Service en ligne</h6>
                    <p class="mb-0">Retour gratuit sous 30 jours</p>
                    </div>
                </div>
                </div>
            </div>
            </div>
        </div>
        <!-- Services End -->

        <!-- Offres Produits Start -->
        <div class="container-fluid bg-light py-5">
            <div class="container">
            <div class="row g-4">
                <div class="col-lg-6 wow fadeInLeft" data-wow-delay="0.2s">
                <a href="#" class="d-flex align-items-center justify-content-between border bg-white rounded p-4">
                    <div>
                    <p class="text-muted mb-3">Trouvez la meilleure caméra pour vous !</p>
                    <h3 class="text-primary">Caméra intelligente</h3>
                    <h1 class="display-3 text-secondary mb-0">40% <span class="text-primary fw-normal">de réduction</span></h1>
                    </div>
                    <img src="img/product-1.png" class="img-fluid" alt="">
                </a>
                </div>
                <div class="col-lg-6 wow fadeInRight" data-wow-delay="0.3s">
                <a href="#" class="d-flex align-items-center justify-content-between border bg-white rounded p-4">
                    <div>
                    <p class="text-muted mb-3">Trouvez la meilleure montre pour vous !</p>
                    <h3 class="text-primary">Montre connectée</h3>
                    <h1 class="display-3 text-secondary mb-0">20% <span class="text-primary fw-normal">de réduction</span></h1>
                    </div>
                    <img src="img/product-2.png" class="img-fluid" alt="">
                </a>
                </div>
            </div>
            </div>
        </div>
        <!-- Offres Produits End -->


        <!-- Our Products Start -->
        <div class="container-fluid product py-5">
            <div class="container py-5">
                <div class="tab-class">
                    <div class="row g-4">
                        <div class="col-lg-4 text-start wow fadeInLeft" data-wow-delay="0.1s">
                            <h1>Nos Produits</h1>
                        </div>
                        <div class="col-lg-8 text-end wow fadeInRight" data-wow-delay="0.1s">
                            <ul class="nav nav-pills d-inline-flex text-center mb-5">
                                <li class="nav-item mb-4">
                                    <a class="d-flex mx-2 py-2 bg-light rounded-pill active" data-bs-toggle="pill" href="#tab-1">
                                        <span class="text-dark" style="width: 130px;">Tous les Produits</span>
                                    </a>
                                </li>
                                <li class="nav-item mb-4">
                                    <a class="d-flex py-2 mx-2 bg-light rounded-pill" data-bs-toggle="pill" href="#tab-2">
                                        <span class="text-dark" style="width: 130px;">Nouveautés</span>
                                    </a>
                                </li>
                                <li class="nav-item mb-4">
                                    <a class="d-flex mx-2 py-2 bg-light rounded-pill" data-bs-toggle="pill" href="#tab-4">
                                        <span class="text-dark" style="width: 130px;">Meilleures Ventes</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="tab-content">
                        <div id="tab-1" class="tab-pane fade show p-0 active">
                            <div class="row g-4">
                                @foreach ($offers as $offer)
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <div class="product-item rounded wow fadeInUp" data-wow-delay="0.1s">
                                            <div class="product-item-inner border rounded">
                                                <div class="product-item-inner-item">
                                                    @php
                                                        $images = is_string($offer->images) ? json_decode($offer->images, true) : $offer->images;
                                                        $firstImage = $images && is_array($images) && count($images) > 0 
                                                            ? '/storage/offer_img/product' . $offer->id . "/" . $images[0]
                                                            : '/img/default-product.jpg';
                                                    @endphp
                                                    <img src="{{ asset($firstImage) }}" 
                                                         class="img-fluid w-100 rounded-top" 
                                                         alt="{{ $offer->name }}"
                                                         loading="lazy"
                                                         style="height: 250px; object-fit: cover;">
                                                    @if ($offer->created_at >= now()->subMonths())
                                                        <div class="product-new">Nouveau</div>
                                                    @endif
                                                    @if ($offer->discount_percentage > 0)
                                                        <div class="product-sale">-{{ $offer->discount_percentage }}%</div>
                                                    @endif
                                                    <div class="product-details">
                                                        <a href="{{ url('/product/details/' . $offer->id) }}"><i class="fa fa-eye fa-1x"></i></a>
                                                    </div>
                                                </div>
                                                <div class="text-center rounded-bottom p-4">
                                                    <a href="{{ route('shop') }}?category={{ $offer->category }}" class="d-block mb-2 text-muted small">{{ $offer->category }}</a>
                                                    <a href="{{ url('/product/details/' . $offer->id) }}" class="d-block h5 text-dark mb-2">{{ Str::limit($offer->name, 40) }}</a>
                                                    @if($offer->discount_percentage > 0)
                                                        <del class="me-2 text-muted">{{ number_format($offer->price / (1 - $offer->discount_percentage/100), 0, '.', ',') }} FCFA</del>
                                                    @endif
                                                    <span class="text-primary fw-bold fs-5">{{ number_format($offer->price, 0, '.', ',') }} FCFA</span>
                                                </div>
                                            </div>
                                            <div class="product-item-add border border-top-0 rounded-bottom text-center p-4 pt-0">
                                                @if(Auth::check())
                                                    <a href="{{ route('cart.add', $offer->id) }}" class="btn btn-primary border-secondary rounded-pill py-2 px-4 mb-3">
                                                        <i class="fas fa-shopping-cart me-2"></i>Ajouter au panier
                                                    </a>
                                                @else
                                                    <a href="{{ route('login') }}" class="btn btn-primary border-secondary rounded-pill py-2 px-4 mb-3">
                                                        <i class="fas fa-shopping-cart me-2"></i>Ajouter au panier
                                                    </a>
                                                @endif
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="d-flex">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <i class="fas fa-star {{ $i <= 4 ? 'text-primary' : 'text-muted' }}"></i>
                                                        @endfor
                                                    </div>
                                                    <div class="d-flex">
                                                        @if(Auth::check())
                                                            <a href="{{ route('wishlist.add', $offer->id) }}" 
                                                               class="text-primary d-flex align-items-center justify-content-center" 
                                                               title="Ajouter aux favoris">
                                                                <span class="rounded-circle btn-sm-square border">
                                                                    <i class="fas fa-heart"></i>
                                                                </span>
                                                            </a>
                                                        @else
                                                            <a href="{{ route('login') }}" 
                                                               class="text-primary d-flex align-items-center justify-content-center"
                                                               title="Connectez-vous pour ajouter aux favoris">
                                                                <span class="rounded-circle btn-sm-square border">
                                                                    <i class="fas fa-heart"></i>
                                                                </span>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div id="tab-2" class="tab-pane fade show p-0">
                            <div class="row g-4">
                                @forelse ($newArrivals as $newArrival)
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <div class="product-item rounded wow fadeInUp" data-wow-delay="0.1s">
                                            <div class="product-item-inner border rounded">
                                                <div class="product-item-inner-item">
                                                    @php
                                                        $images = is_string($newArrival->images) ? json_decode($newArrival->images, true) : $newArrival->images;
                                                        $firstImage = $images && is_array($images) && count($images) > 0 
                                                            ? '/storage/offer_img/product' . $newArrival->id . "/" . $images[0]
                                                            : '/img/default-product.jpg';
                                                    @endphp
                                                    <img src="{{ asset($firstImage) }}" 
                                                         class="img-fluid w-100 rounded-top" 
                                                         alt="{{ $newArrival->name }}"
                                                         loading="lazy"
                                                         style="height: 250px; object-fit: cover;">
                                                    <div class="product-new">Nouveau</div>
                                                    @if ($newArrival->discount_percentage > 0)
                                                        <div class="product-sale">-{{ $newArrival->discount_percentage }}%</div>
                                                    @endif
                                                    <div class="product-details">
                                                        <a href="{{ url('/product/details/' . $newArrival->id) }}"><i class="fa fa-eye fa-1x"></i></a>
                                                    </div>
                                                </div>
                                                <div class="text-center rounded-bottom p-4">
                                                    <a href="{{ route('shop') }}?category={{ $newArrival->category }}" class="d-block mb-2 text-muted small">{{ $newArrival->category }}</a>
                                                    <a href="{{ url('/product/details/' . $newArrival->id) }}" class="d-block h5 text-dark mb-2">{{ Str::limit($newArrival->name, 40) }}</a>
                                                    @if($newArrival->discount_percentage > 0)
                                                        <del class="me-2 text-muted">{{ number_format($newArrival->price / (1 - $newArrival->discount_percentage/100), 0, '.', ',') }} FCFA</del>
                                                    @endif
                                                    <span class="text-primary fw-bold fs-5">{{ number_format($newArrival->price, 0, '.', ',') }} FCFA</span>
                                                </div>
                                            </div>
                                            <div class="product-item-add border border-top-0 rounded-bottom text-center p-4 pt-0">
                                                @if(Auth::check())
                                                    <a href="{{ route('cart.add', $newArrival->id) }}" class="btn btn-primary border-secondary rounded-pill py-2 px-4 mb-3">
                                                        <i class="fas fa-shopping-cart me-2"></i>Ajouter au panier
                                                    </a>
                                                @else
                                                    <a href="{{ route('login') }}" class="btn btn-primary border-secondary rounded-pill py-2 px-4 mb-3">
                                                        <i class="fas fa-shopping-cart me-2"></i>Ajouter au panier
                                                    </a>
                                                @endif
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="d-flex">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <i class="fas fa-star {{ $i <= 4 ? 'text-primary' : 'text-muted' }}"></i>
                                                        @endfor
                                                    </div>
                                                    <div class="d-flex">
                                                        @if(Auth::check())
                                                            <a href="{{ route('wishlist.add', $newArrival->id) }}" 
                                                               class="text-primary d-flex align-items-center justify-content-center" 
                                                               title="Ajouter aux favoris">
                                                                <span class="rounded-circle btn-sm-square border">
                                                                    <i class="fas fa-heart"></i>
                                                                </span>
                                                            </a>
                                                        @else
                                                            <a href="{{ route('login') }}" 
                                                               class="text-primary d-flex align-items-center justify-content-center"
                                                               title="Connectez-vous pour ajouter aux favoris">
                                                                <span class="rounded-circle btn-sm-square border">
                                                                    <i class="fas fa-heart"></i>
                                                                </span>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-center">
                                        <p class="text-muted">Aucune nouveauté pour le moment</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                        <div id="tab-4" class="tab-pane fade show p-0">
                            <div class="row g-4">
                                @forelse ($bestsellers as $bestseller)
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <div class="product-item rounded wow fadeInUp" data-wow-delay="0.1s">
                                            <div class="product-item-inner border rounded">
                                                <div class="product-item-inner-item">
                                                    @php
                                                        $images = is_string($bestseller->images) ? json_decode($bestseller->images, true) : $bestseller->images;
                                                        $firstImage = $images && is_array($images) && count($images) > 0 
                                                            ? '/storage/offer_img/product' . $bestseller->id . "/" . $images[0]
                                                            : '/img/default-product.jpg';
                                                    @endphp
                                                    <img src="{{ asset($firstImage) }}" 
                                                         class="img-fluid w-100 rounded-top" 
                                                         alt="{{ $bestseller->name }}"
                                                         loading="lazy"
                                                         style="height: 250px; object-fit: cover;">
                                                    @if ($bestseller->created_at >= now()->subMonths())
                                                        <div class="product-new">Nouveau</div>
                                                    @endif
                                                    @if ($bestseller->discount_percentage > 0)
                                                        <div class="product-sale">-{{ $bestseller->discount_percentage }}%</div>
                                                    @endif
                                                    <div class="product-details">
                                                        <a href="{{ url('/product/details/' . $bestseller->id) }}"><i class="fa fa-eye fa-1x"></i></a>
                                                    </div>
                                                </div>
                                                <div class="text-center rounded-bottom p-4">
                                                    <a href="{{ route('shop') }}?category={{ $bestseller->category }}" class="d-block mb-2 text-muted small">{{ $bestseller->category }}</a>
                                                    <a href="{{ url('/product/details/' . $bestseller->id) }}" class="d-block h5 text-dark mb-2">{{ Str::limit($bestseller->name, 40) }}</a>
                                                    @if($bestseller->discount_percentage > 0)
                                                        <del class="me-2 text-muted">{{ number_format($bestseller->price / (1 - $bestseller->discount_percentage/100), 0, '.', ',') }} FCFA</del>
                                                    @endif
                                                    <span class="text-primary fw-bold fs-5">{{ number_format($bestseller->price, 0, '.', ',') }} FCFA</span>
                                                </div>
                                            </div>
                                            <div class="product-item-add border border-top-0 rounded-bottom text-center p-4 pt-0">
                                                @if(Auth::check())
                                                    <a href="{{ route('cart.add', $bestseller->id) }}" class="btn btn-primary border-secondary rounded-pill py-2 px-4 mb-3">
                                                        <i class="fas fa-shopping-cart me-2"></i>Ajouter au panier
                                                    </a>
                                                @else
                                                    <a href="{{ route('login') }}" class="btn btn-primary border-secondary rounded-pill py-2 px-4 mb-3">
                                                        <i class="fas fa-shopping-cart me-2"></i>Ajouter au panier
                                                    </a>
                                                @endif
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="d-flex">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <i class="fas fa-star {{ $i <= 4 ? 'text-primary' : 'text-muted' }}"></i>
                                                        @endfor
                                                    </div>
                                                    <div class="d-flex">
                                                        @if(Auth::check())
                                                            <a href="{{ route('wishlist.add', $bestseller->id) }}" 
                                                               class="text-primary d-flex align-items-center justify-content-center" 
                                                               title="Ajouter aux favoris">
                                                                <span class="rounded-circle btn-sm-square border">
                                                                    <i class="fas fa-heart"></i>
                                                                </span>
                                                            </a>
                                                        @else
                                                            <a href="{{ route('login') }}" 
                                                               class="text-primary d-flex align-items-center justify-content-center"
                                                               title="Connectez-vous pour ajouter aux favoris">
                                                                <span class="rounded-circle btn-sm-square border">
                                                                    <i class="fas fa-heart"></i>
                                                                </span>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-center">
                                        <p class="text-muted">Aucun produit populaire pour le moment</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Our Products End -->

        <!-- Product Banner Start -->
        <div class="container-fluid py-5">
            <div class="container">
                <div class="row g-4">
                    <div class="col-lg-6 wow fadeInLeft" data-wow-delay="0.1s">
                        <a href="#">
                            <div class="bg-primary rounded position-relative">
                                <img src="img/product-banner.jpg" class="img-fluid w-100 rounded" alt="">
                                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center rounded p-4" style="background: rgba(255, 255, 255, 0.5);">
                                    <h3 class="display-5 text-primary">EOS Rebel <br> <span>T7i Kit</span></h3>
                                    <p class="fs-4 text-muted">Au prix de rien</p>
                                    <a href="{{route("shop")}}" class="btn btn-primary rounded-pill align-self-start py-2 px-4">Shop Now</a>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-6 wow fadeInRight" data-wow-delay="0.2s">
                        <a href="#">
                            <div class="text-center bg-primary rounded position-relative">
                                <img src="img/product-banner-2.jpg" class="img-fluid w-100" alt="">
                                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center rounded p-4" style="background: rgba(242, 139, 0, 0.5);">
                                    <h2 class="display-2 text-secondary">Top Vente</h2>
                                    <h4 class="display-5 text-white mb-4">Recevez jusqua 20% de reduction</h4>
                                    <a href="{{route("shop")}}" class="btn btn-secondary rounded-pill align-self-center py-2 px-4">Shop Now</a>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Product Banner End -->

        <!-- Product List Satrt -->
        <div class="container-fluid products productList overflow-hidden">
            <div class="container products-mini py-5">
                <div class="mx-auto text-center mb-5" style="max-width: 900px;">
                    <h4 class="text-primary border-bottom border-primary border-2 d-inline-block p-2 title-border-radius wow fadeInUp" data-wow-delay="0.1s">Products</h4>
                    <h1 class="mb-0 display-3 wow fadeInUp" data-wow-delay="0.3s">Tout les produit</h1>
                </div>
                <div class="productList-carousel owl-carousel pt-4 wow fadeInUp" data-wow-delay="0.3s">
                    @foreach ($categories as $category)    
                    <div class="productImg-carousel owl-carousel productList-item">
                        @foreach ($offers->shuffle()->take(4) as $offer)
                            <div class="productImg-item products-mini-item border">
                                <div class="row g-0">
                                    <div class="col-5">
                                        <div class="products-mini-img border-end h-100">
                                            @php
                                                $images = is_string($offer->images) ? json_decode($offer->images, true) : $offer->images;
                                                $firstImage = $images && is_array($images) && count($images) > 0 
                                                    ? '/storage/offer_img/product' . $offer->id . "/" . $images[0]
                                                    : '/img/default-product.jpg';
                                            @endphp
                                            <img src="{{ asset($firstImage) }}" class="img-fluid w-100 h-100" alt="{{ $offer->name }}">
                                            <div class="products-mini-icon rounded-circle bg-primary">
                                                <a href="{{url("/product/details/". $offer->id)}}"><i class="fa fa-eye fa-1x text-white"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-7">
                                        <div class="products-mini-content p-3">
                                            <a href="#" class="d-block mb-2">{{$offer->category}}</a>
                                            <a href="#" class="d-block h4">{{$offer->name}}<br> G{{rand(1000,9999)}}</a>
                                            <del class="me-2 fs-5">{{ number_format(($offer->price) + ($offer->price * 0.15), 0, '.', ',') }}FCFA</del>
                                            <span class="text-primary fs-5">{{ number_format($offer->price, 0, '.', ',') }}FCFA</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="products-mini-add border p-3">
                                    @if(Auth::check())
                                        <a href="{{ route('cart.add', $offer->id) }}" class="btn btn-primary border-secondary rounded-pill py-2 px-4"><i class="fas fa-shopping-cart me-2"></i> Ajouter au panier</a>
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-primary border-secondary rounded-pill py-2 px-4"><i class="fas fa-shopping-cart me-2"></i> Ajouter au panier</a>
                                    @endif
                                    <div class="d-flex">
                                        <a href="#" class="text-primary d-flex align-items-center justify-content-center me-3"><span class="rounded-circle btn-sm-square border"><i class="fas fa-random"></i></i></a>
                                        @if(Auth::check())
                                            <a href="{{ route('wishlist.add', $offer->id) }}" class="text-primary d-flex align-items-center justify-content-center me-0"><span class="rounded-circle btn-sm-square border"><i class="fas fa-heart"></i></a>
                                        @else
                                            <a href="{{ route('login') }}" class="text-primary d-flex align-items-center justify-content-center me-0"><span class="rounded-circle btn-sm-square border"><i class="fas fa-heart"></i></a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <!-- Product List End -->

        <!-- Bestseller Products Start -->
        <div class="container-fluid products pb-5">
            <div class="container products-mini py-5">
                <div class="mx-auto text-center mb-5" style="max-width: 700px;">
                    <h4 class="text-primary mb-4 border-bottom border-primary border-2 d-inline-block p-2 title-border-radius wow fadeInUp" data-wow-delay="0.1s">Meilleures Ventes</h4>
                    <p class="mb-0 wow fadeInUp" data-wow-delay="0.2s">Découvrez nos produits les plus populaires et les plus appréciés par nos clients</p>
                </div>
                <div class="row g-4">
                    @forelse ($bestsellers as $index => $bestseller)
                        <div class="col-md-6 col-lg-6 col-xl-4 wow fadeInUp" data-wow-delay="{{ 0.1 + ($index * 0.2) }}s">
                            <div class="products-mini-item border">
                                <div class="row g-0">
                                    <div class="col-5">
                                        <div class="products-mini-img border-end h-100">
                                            @php
                                                $images = is_string($bestseller->images) ? json_decode($bestseller->images, true) : $bestseller->images;
                                                $firstImage = $images && is_array($images) && count($images) > 0 
                                                    ? '/storage/offer_img/product' . $bestseller->id . "/" . $images[0]
                                                    : '/img/default-product.jpg';
                                            @endphp
                                            <img src="{{ asset($firstImage) }}" class="img-fluid w-100 h-100" alt="{{ $bestseller->name }}" style="object-fit: cover;">
                                            <div class="products-mini-icon rounded-circle bg-primary">
                                                <a href="{{ url('/product/details/' . $bestseller->id) }}"><i class="fa fa-eye fa-1x text-white"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-7">
                                        <div class="products-mini-content p-3">
                                            <a href="{{ route('shop') }}?category={{ $bestseller->category }}" class="d-block mb-2 text-muted small">{{ $bestseller->category }}</a>
                                            <a href="{{ url('/product/details/' . $bestseller->id) }}" class="d-block h5 mb-2">{{ Str::limit($bestseller->name, 35) }}</a>
                                            @if($bestseller->discount_percentage > 0)
                                                <del class="me-2 fs-6 text-muted">{{ number_format($bestseller->price / (1 - $bestseller->discount_percentage/100), 0, '.', ',') }} FCFA</del>
                                            @endif
                                            <span class="text-primary fw-bold fs-6">{{ number_format($bestseller->price, 0, '.', ',') }} FCFA</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="products-mini-add border p-3">
                                    @if(Auth::check())
                                        <a href="{{ route('cart.add', $bestseller->id) }}" class="btn btn-primary border-secondary rounded-pill py-2 px-4"><i class="fas fa-shopping-cart me-2"></i> Ajouter au panier</a>
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-primary border-secondary rounded-pill py-2 px-4"><i class="fas fa-shopping-cart me-2"></i> Ajouter au panier</a>
                                    @endif
                                    <div class="d-flex">
                                        @if(Auth::check())
                                            <a href="{{ route('wishlist.add', $bestseller->id) }}" class="text-primary d-flex align-items-center justify-content-center me-0" title="Ajouter aux favoris"><span class="rounded-circle btn-sm-square border"><i class="fas fa-heart"></i></a>
                                        @else
                                            <a href="{{ route('login') }}" class="text-primary d-flex align-items-center justify-content-center me-0" title="Connectez-vous pour ajouter aux favoris"><span class="rounded-circle btn-sm-square border"><i class="fas fa-heart"></i></a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center">
                            <p class="text-muted">Aucun produit populaire pour le moment</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        <!-- Bestseller Products End -->   

{{-- @section('scripts') --}}
<script src="{{ asset('js/new-arrivals-filter.js') }}"></script>
@endsection
