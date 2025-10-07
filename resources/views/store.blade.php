@extends('layouts.web')
@section('content')
<!-- Single Page Header start -->
        <div class="container-fluid page-header py-5">
            <h1 class="text-center text-white display-6 wow fadeInUp" data-wow-delay="0.1s">Shop Page</h1>
            <ol class="breadcrumb justify-content-center mb-0 wow fadeInUp" data-wow-delay="0.3s">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Pages</a></li>
                <li class="breadcrumb-item active text-white">Shop</li>
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
                        @include('layouts.store')
                    </div>
                    <div class="col-lg-9 wow fadeInUp" data-wow-delay="0.1s">
                        @yield('sidebar-dynamic')
                        <div class="rounded mb-4 position-relative">
                            <img src="img/product-banner-3.jpg" class="img-fluid rounded w-100" style="height: 250px;" alt="Image">
                            <div class="position-absolute rounded d-flex flex-column align-items-center justify-content-center text-center" style="width: 100%; height: 250px; top: 0; left: 0; background: rgba(242, 139, 0, 0.3);">
                                <h4 class="display-5 text-primary">SALE</h4>
                                <h3 class="display-4 text-white mb-4">Get UP To 50% Off</h3>
                                <a href="#" class="btn btn-primary rounded-pill">Shop Now</a>
                            </div>
                        </div>
                        <div class="row g-4">
                            <div class="col-xl-7">
                                <div class="input-group w-100 mx-auto d-flex">
                                    <input type="search" class="form-control p-3" placeholder="keywords" aria-describedby="search-icon-1">
                                    <span id="search-icon-1" class="input-group-text p-3"><i class="fa fa-search"></i></span>
                                </div>
                            </div>
                            <div class="col-xl-3 text-end">
                                <div class="bg-light ps-3 py-3 rounded d-flex justify-content-between">
                                    <label for="electronics">Sort By:</label>
                                    <select id="electronics" name="electronicslist" class="border-0 form-select-sm bg-light me-3" form="electronicsform">
                                        <option value="volvo">Default Sorting</option>
                                        {{-- <option value="volv">Nothing</option>
                                        <option value="sab">Popularity</option>
                                        <option value="saab">Newness</option>
                                        <option value="opel">Average Rating</option>
                                        <option value="audio">Low to high</option>
                                        <option value="audi">High to low</option> --}}
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-xl-2">
                                <ul class="nav nav-pills d-inline-flex text-center py-2 px-2 rounded bg-light mb-4">
                                    <li class="nav-item me-4">
                                        <a class="bg-light" data-bs-toggle="pill" href="#tab-5">
                                            <i class="fas fa-th fa-3x text-primary"></i>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="bg-light" data-bs-toggle="pill" href="#tab-6">
                                            <i class="fas fa-bars fa-3x text-primary"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="tab-content">
                            <div id="tab-5" class="tab-pane fade show p-0 active">
                                <div class="row g-4 product">
                                    @foreach ($offers as $offer)
                                        <div class="col-lg-4">
                                            <div class="product-item rounded wow fadeInUp" data-wow-delay="0.1s">
                                                <div class="product-item-inner border rounded">
                                                    <div class="product-item-inner-item">
                                                        <img src={{'/storage/offer_img/product' . $offer->id . "/" . json_decode($offer->images, true)[0]}} class="img-fluid w-100 rounded-top" alt="">
                                                         @if ($offer->created_at >= now()->subMonths())
                                                            <div class="product-new">New</div>
                                                        @endif
                                                        <div class="product-details">
                                                            <a href="{{url("/product/details/". $offer->id)}}"><i class="fa fa-eye fa-1x"></i></a>
                                                        </div>
                                                    </div>
                                                    <div class="text-center rounded-bottom p-4">
                                                        <a href="#" class="d-block mb-2">{{$offer->category}}</a>
                                                        <a href="#" class="d-block h4">{{$offer->name}} <br> G{{rand(1000,9999)}}</a>
                                                        <del class="me-2 fs-5">{{ number_format(($offer->price) + ($offer->price * 0.15), 0, '.', ',') }}</del>
                                                        <span class="text-primary fs-5">{{ number_format($offer->price, 0, '.', ',') }}FCFA</span>
                                                    </div>
                                                </div>
                                                <div class="product-item-add border border-top-0 rounded-bottom  text-center p-4 pt-0">
                                                    <a href="{{ url('/cart/' . $offer->id . '/create/' . Auth::id()) }}" class="btn btn-primary border-secondary rounded-pill py-2 px-4 mb-4"><i class="fas fa-shopping-cart me-2"></i> Add To Cart</a>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="d-flex">
                                                            <i class="fas fa-star text-primary"></i>
                                                            <i class="fas fa-star text-primary"></i>
                                                            <i class="fas fa-star text-primary"></i>
                                                            <i class="fas fa-star text-primary"></i>
                                                            <i class="fas fa-star"></i>
                                                        </div>
                                                        <div class="d-flex">
                                                            {{-- <a href="#" class="text-primary d-flex align-items-center justify-content-center me-3"><span class="rounded-circle btn-sm-square border"><i class="fas fa-random"></i></i></a> --}}
                                                            <a href="{{ url('/wish-list/' . $offer->id . '/create/' . Auth::id()) }}" class="text-primary d-flex align-items-center justify-content-center me-0"><span class="rounded-circle btn-sm-square border"><i class="fas fa-heart"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="col-12 wow fadeInUp" data-wow-delay="0.1s">
                                        <div class="pagination d-flex justify-content-center mt-5">
                                            <a href="#" class="rounded">&laquo;</a>
                                            <a href="#" class="active rounded">1</a>
                                            <a href="#" class="rounded">2</a>
                                            <a href="#" class="rounded">3</a>
                                            <a href="#" class="rounded">4</a>
                                            <a href="#" class="rounded">5</a>
                                            <a href="#" class="rounded">6</a>
                                            <a href="#" class="rounded">&raquo;</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <!-- recomended Product Start -->
        @include('layouts.related')
        <!-- recomended Product End -->

        <!-- Product Banner Start -->
        <div class="container-fluid py-5">
            <div class="container pb-5">
                <div class="row g-4">
                    <div class="col-lg-6 wow fadeInLeft" data-wow-delay="0.1s">
                        <a href="#">
                            <div class="bg-primary rounded position-relative">
                                <img src="img/product-banner.jpg" class="img-fluid w-100 rounded" alt="">
                                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center rounded p-4" style="background: rgba(255, 255, 255, 0.5);">
                                    <h3 class="display-5 text-primary">EOS Rebel <br> <span>T7i Kit</span></h3>
                                    <p class="fs-4 text-muted">$899.99</p>
                                    <a href="#" class="btn btn-primary rounded-pill align-self-start py-2 px-4">Shop Now</a>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-6 wow fadeInRight" data-wow-delay="0.2s">
                        <a href="#">
                            <div class="text-center bg-primary rounded position-relative">
                                <img src="img/product-banner-2.jpg" class="img-fluid w-100" alt="">
                                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center rounded p-4" style="background: rgba(242, 139, 0, 0.5);">
                                    <h2 class="display-2 text-secondary">SALE</h2>
                                    <h4 class="display-5 text-white mb-4">Get UP To 50% Off</h4>
                                    <a href="#" class="btn btn-secondary rounded-pill align-self-center py-2 px-4">Shop Now</a>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Product Banner End -->

@endsection