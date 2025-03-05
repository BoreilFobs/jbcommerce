@extends('layouts.web')
@section('content')
    <main class="main">
        <div class="intro-section pt-3 pb-3 mb-2">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="intro-slider-container slider-container-ratio mb-2 mb-lg-0">
                            <div class="intro-slider owl-carousel owl-simple owl-dark owl-nav-inside" data-toggle="owl"
                                data-owl-options='{
                                        "nav": false,
                                        "dots": true,
                                        "responsive": {
                                            "768": {
                                                "nav": true,
                                                "dots": false
                                            }
                                        }
                                    }'>
                                <div class="intro-slide">
                                    <figure class="slide-image">
                                        <picture>
                                            <source media="(max-width: 480px)"
                                                srcset="
                                                            assets/images/demos/demo-3/slider/slide-1-480w.jpg
                                                        " />
                                            <img src="assets/images/demos/demo-3/slider/slide-1.jpg" alt="Image Desc" />
                                        </picture>
                                    </figure>
                                    <!-- End .slide-image -->

                                    <div class="intro-content">
                                        <h3 class="intro-subtitle text-primary">
                                            Daily Deals
                                        </h3>
                                        <!-- End .h3 intro-subtitle -->
                                        <h1 class="intro-title">
                                            AirPods <br />Earphones
                                        </h1>
                                        <!-- End .intro-title -->

                                        <div class="intro-price">
                                            <sup>Today:</sup>
                                            <span class="text-primary">
                                                15,000FCFA
                                            </span>
                                        </div>
                                        <!-- End .intro-price -->

                                        <a href="#" class="btn btn-primary btn-round">
                                            <span>Click Here</span>
                                            <i class="icon-long-arrow-right"></i>
                                        </a>
                                    </div>
                                    <!-- End .intro-content -->
                                </div>
                                <!-- End .intro-slide -->

                                <div class="intro-slide">
                                    <figure class="slide-image">
                                        <picture>
                                            <source media="(max-width: 480px)"
                                                srcset="
                                                            assets/images/demos/demo-3/slider/slide-2-480w.jpg
                                                        " />
                                            <img src="assets/images/demos/demo-3/slider/slide-2.jpg" alt="Image Desc" />
                                        </picture>
                                    </figure>
                                    <!-- End .slide-image -->

                                    <div class="intro-content">
                                        <h3 class="intro-subtitle text-primary">
                                            Deals and Promotions
                                        </h3>
                                        <!-- End .h3 intro-subtitle -->
                                        <h1 class="intro-title">
                                            Echo Dot <br />3rd Gen
                                        </h1>
                                        <!-- End .intro-title -->

                                        <div class="intro-price">
                                            <sup class="intro-old-price">75,000FCFA</sup>
                                            <span class="text-primary">
                                                45,000FCFA
                                            </span>
                                        </div>
                                        <!-- End .intro-price -->

                                        <a href="#" class="btn btn-primary btn-round">
                                            <span>Click Here</span>
                                            <i class="icon-long-arrow-right"></i>
                                        </a>
                                    </div>
                                    <!-- End .intro-content -->
                                </div>
                                <!-- End .intro-slide -->
                            </div>
                            <!-- End .intro-slider owl-carousel owl-simple -->

                            <span class="slider-loader"></span><!-- End .slider-loader -->
                        </div>
                        <!-- End .intro-slider-container -->
                    </div>
                    <!-- End .col-lg-8 -->

                    <div class="col-lg-4">
                        <div class="intro-banners">
                            <div class="banner mb-lg-1 mb-xl-2">
                                <a href="#">
                                    <img src="assets/images/demos/demo-3/banners/banner-1.jpg" alt="Banner" />
                                </a>

                                <div class="banner-content">
                                    <h4 class="banner-subtitle d-lg-none d-xl-block">
                                        <a href="#">Top Product</a>
                                    </h4>
                                    <!-- End .banner-subtitle -->
                                    <h3 class="banner-title">
                                        <a href="#">Edifier <br />Stereo
                                            Bluetooth</a>
                                    </h3>
                                    <!-- End .banner-title -->
                                    <a href="#" class="banner-link">Shop Now<i class="icon-long-arrow-right"></i></a>
                                </div>
                                <!-- End .banner-content -->
                            </div>
                            <!-- End .banner -->

                            <div class="banner mb-lg-1 mb-xl-2">
                                <a href="#">
                                    <img src="assets/images/demos/demo-3/banners/banner-2.jpg" alt="Banner" />
                                </a>

                                <div class="banner-content">
                                    <h4 class="banner-subtitle d-lg-none d-xl-block">
                                        <a href="#">Clearance</a>
                                    </h4>
                                    <!-- End .banner-subtitle -->
                                    <h3 class="banner-title">
                                        <a href="#">GoPro - Fusion 360
                                            <span>Save 20,000FCFA</span></a>
                                    </h3>
                                    <!-- End .banner-title -->
                                    <a href="#" class="banner-link">Shop Now<i class="icon-long-arrow-right"></i></a>
                                </div>
                                <!-- End .banner-content -->
                            </div>
                            <!-- End .banner -->

                            <div class="banner mb-0">
                                <a href="#">
                                    <img src="assets/images/demos/demo-3/banners/banner-3.jpg" alt="Banner" />
                                </a>

                                <div class="banner-content">
                                    <h4 class="banner-subtitle d-lg-none d-xl-block">
                                        <a href="#">Featured</a>
                                    </h4>
                                    <!-- End .banner-subtitle -->
                                    <h3 class="banner-title">
                                        <a href="#">Apple Watch 4
                                            <span>Our Hottest Deals</span></a>
                                    </h3>
                                    <!-- End .banner-title -->
                                    <a href="#" class="banner-link">Shop Now<i class="icon-long-arrow-right"></i></a>
                                </div>
                                <!-- End .banner-content -->
                            </div>
                            <!-- End .banner -->
                        </div>
                        <!-- End .intro-banners -->
                    </div>
                    <!-- End .col-lg-4 -->
                </div>
                <!-- End .row -->
            </div>
            <!-- End .container -->
        </div>
        <!-- End .intro-section -->

        <div class="container featured">
            <ul class="nav nav-pills nav-border-anim nav-big justify-content-center mb-3" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="products-featured-link" data-toggle="tab" href="#products-featured-tab"
                        role="tab" aria-controls="products-featured-tab" aria-selected="true">Featured</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="products-sale-link" data-toggle="tab" href="#products-sale-tab" role="tab"
                        aria-controls="products-sale-tab" aria-selected="false">On Sale</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="products-top-link" data-toggle="tab" href="#products-top-tab" role="tab"
                        aria-controls="products-top-tab" aria-selected="false">Top Rated</a>
                </li>
            </ul>

            <div class="tab-content tab-content-carousel">
                <div class="tab-pane p-0 fade show active" id="products-featured-tab" role="tabpanel"
                    aria-labelledby="products-featured-link">
                    <div class="owl-carousel owl-full carousel-equal-height carousel-with-shadow" data-toggle="owl"
                        data-owl-options='{
                                "nav": true,
                                "dots": true,
                                "margin": 20,
                                "loop": false,
                                "responsive": {
                                    "0": {
                                        "items":2
                                    },
                                    "600": {
                                        "items":2
                                    },
                                    "992": {
                                        "items":3
                                    },
                                    "1200": {
                                        "items":4
                                    }
                                }
                            }'>
                        @foreach ($offers as $offer)
                            <div class="product product-2">
                                <figure class="product-media">
                                    <a href="product.html">
                                        <img src={{ $offer->image_path }} alt="Product image" class="product-image" />
                                    </a>

                                    <div class="product-action-vertical">
                                        @if (Auth::check())
                                            <a href={{ url('/wish-list/' . $offer->id . '/create/' . Auth::id()) }}
                                                class="btn-product-icon btn-wishlist btn-expandable"><span>add
                                                    to
                                                    wishlist</span></a>
                                        @else
                                            <a href="#"
                                                class="btn-product-icon btn-wishlist btn-expandable"><span>add
                                                    to
                                                    wishlist</span></a>
                                        @endif
                                    </div>
                                    <!-- End .product-action -->

                                    <div class="product-action product-action-dark">
                                        @if (Auth::check())
                                            <a href={{ url('/cart/' . $offer->id . '/create/' . Auth::id()) }}
                                                class="btn-product btn-cart" title="Add to cart"><span>add to
                                                    cart</span></a>
                                        @else
                                            <a href="#" class="btn-product btn-cart" title="Add to cart"><span>add
                                                    to
                                                    cart</span></a>
                                        @endif
                                        <a href="#" class="btn-product btn-quickview"
                                            title="Quick view"><span>quick view</span></a>
                                    </div>
                                    <!-- End .product-action -->
                                </figure>
                                <!-- End .product-media -->

                                <div class="product-body">
                                    <div class="product-cat">
                                        <a href="#">{{ $offer->category }}</a>
                                    </div>
                                    <!-- End .product-cat -->
                                    <h3 class="product-title">
                                        <a href="#">{{ $offer->name }}</a>
                                    </h3>
                                    <!-- End .product-title -->
                                    <div class="product-price">{{ $offer->price }}FCFA</div>
                                    <!-- End .product-price -->

                                </div>
                                <!-- End .product-body -->
                            </div>
                            <!-- End .product -->
                        @endforeach
                    </div>
                    <!-- End .owl-carousel -->
                </div>
                <!-- .End .tab-pane -->
                <div class="tab-pane p-0 fade" id="products-sale-tab" role="tabpanel"
                    aria-labelledby="products-sale-link">
                    <div class="owl-carousel owl-full carousel-equal-height carousel-with-shadow" data-toggle="owl"
                        data-owl-options='{
                                "nav": true,
                                "dots": true,
                                "margin": 20,
                                "loop": false,
                                "responsive": {
                                    "0": {
                                        "items":2
                                    },
                                    "600": {
                                        "items":2
                                    },
                                    "992": {
                                        "items":3
                                    },
                                    "1200": {
                                        "items":4
                                    }
                                }
                            }'>
                        @foreach ($offers as $offer)
                            <div class="product product-2">
                                <figure class="product-media">
                                    <a href="product.html">
                                        <img src={{ $offer->image_path }} alt="Product image" class="product-image" />
                                    </a>

                                    <div class="product-action-vertical">
                                        @if (Auth::check())
                                            <a href={{ url('/wish-list/' . $offer->id . '/create/' . Auth::id()) }}
                                                class="btn-product-icon btn-wishlist btn-expandable"><span>add
                                                    to
                                                    wishlist</span></a>
                                        @else
                                            <a href="#"
                                                class="btn-product-icon btn-wishlist btn-expandable"><span>add
                                                    to
                                                    wishlist</span></a>
                                        @endif
                                    </div>
                                    <!-- End .product-action -->

                                    <div class="product-action product-action-dark">
                                        @if (Auth::check())
                                            <a href={{ url('/cart/' . $offer->id . '/create/' . Auth::id()) }}
                                                class="btn-product btn-cart" title="Add to cart"><span>add to
                                                    cart</span></a>
                                        @else
                                            <a href="#" class="btn-product btn-cart" title="Add to cart"><span>add
                                                    to
                                                    cart</span></a>
                                        @endif
                                        <a href="#" class="btn-product btn-quickview"
                                            title="Quick view"><span>quick view</span></a>
                                    </div>
                                    <!-- End .product-action -->
                                </figure>
                                <!-- End .product-media -->

                                <div class="product-body">
                                    <div class="product-cat">
                                        <a href="#">{{ $offer->category }}</a>
                                    </div>
                                    <!-- End .product-cat -->
                                    <h3 class="product-title">
                                        <a href="#">{{ $offer->name }}</a>
                                    </h3>
                                    <!-- End .product-title -->
                                    <div class="product-price">{{ $offer->price }}FCFA</div>
                                    <!-- End .product-price -->

                                </div>
                                <!-- End .product-body -->
                            </div>
                            <!-- End .product -->
                        @endforeach
                    </div>
                    <!-- End .owl-carousel -->
                </div>
                <!-- .End .tab-pane -->
                <div class="tab-pane p-0 fade" id="products-top-tab" role="tabpanel"
                    aria-labelledby="products-top-link">
                    <div class="owl-carousel owl-full carousel-equal-height carousel-with-shadow" data-toggle="owl"
                        data-owl-options='{
                                "nav": true,
                                "dots": true,
                                "margin": 20,
                                "loop": false,
                                "responsive": {
                                    "0": {
                                        "items":2
                                    },
                                    "600": {
                                        "items":2
                                    },
                                    "992": {
                                        "items":3
                                    },
                                    "1200": {
                                        "items":4
                                    }
                                }
                            }'>
                        @foreach ($offers as $offer)
                            <div class="product product-2">
                                <figure class="product-media">
                                    <a href="product.html">
                                        <img src={{ $offer->image_path }} alt="Product image" class="product-image" />
                                    </a>

                                    <div class="product-action-vertical">
                                        @if (Auth::check())
                                            <a href={{ url('/wish-list/' . $offer->id . '/create/' . Auth::id()) }}
                                                class="btn-product-icon btn-wishlist btn-expandable"><span>add
                                                    to
                                                    wishlist</span></a>
                                        @else
                                            <a href="#"
                                                class="btn-product-icon btn-wishlist btn-expandable"><span>add
                                                    to
                                                    wishlist</span></a>
                                        @endif
                                    </div>
                                    <!-- End .product-action -->

                                    <div class="product-action product-action-dark">
                                        @if (Auth::check())
                                            <a href={{ url('/cart/' . $offer->id . '/create/' . Auth::id()) }}
                                                class="btn-product btn-cart" title="Add to cart"><span>add to
                                                    cart</span></a>
                                        @else
                                            <a href="#" class="btn-product btn-cart" title="Add to cart"><span>add
                                                    to
                                                    cart</span></a>
                                        @endif
                                        <a href="#" class="btn-product btn-quickview"
                                            title="Quick view"><span>quick view</span></a>
                                    </div>
                                    <!-- End .product-action -->
                                </figure>
                                <!-- End .product-media -->

                                <div class="product-body">
                                    <div class="product-cat">
                                        <a href="#">{{ $offer->category }}</a>
                                    </div>
                                    <!-- End .product-cat -->
                                    <h3 class="product-title">
                                        <a href="#">{{ $offer->name }}</a>
                                    </h3>
                                    <!-- End .product-title -->
                                    <div class="product-price">{{ $offer->price }}FCFA</div>
                                    <!-- End .product-price -->

                                </div>
                                <!-- End .product-body -->
                            </div>
                            <!-- End .product -->
                        @endforeach

                    </div>
                    <!-- End .owl-carousel -->
                </div>
                <!-- .End .tab-pane -->
            </div>
            <!-- End .tab-content -->
        </div>
        <!-- End .container -->

        <div class="mb-7 mb-lg-11"></div>
        <!-- End .mb-7 -->

        <div class="container">
            <div class="cta cta-border cta-border-image mb-5 mb-lg-7"
                style="
                            background-image: url(assets/images/demos/demo-3/bg-1.jpg);
                        ">
                <div class="cta-border-wrapper bg-white">
                    <div class="row justify-content-center">
                        <div class="col-md-11 col-xl-11">
                            <div class="cta-content">
                                <div class="cta-heading">
                                    <h3 class="cta-title text-right">
                                        <span class="text-primary">New Deals</span>
                                        <br />Start Daily at 12pm e.t.
                                    </h3>
                                    <!-- End .cta-title -->
                                </div>
                                <!-- End .cta-heading -->

                                <div class="cta-text">
                                    <p>
                                        Get
                                        <span class="text-dark font-weight-normal">FREE SHIPPING* & 5%
                                            rewards</span>
                                        on <br />every order with Molla
                                        Theme rewards program
                                    </p>
                                </div>
                                <!-- End .cta-text -->
                                <a href="#" class="btn btn-primary btn-round"><span>Add to Cart for
                                        10,000FCFA/yr</span><i class="icon-long-arrow-right"></i></a>
                            </div>
                            <!-- End .cta-content -->
                        </div>
                        <!-- End .col-xl-7 -->
                    </div>
                    <!-- End .row -->
                </div>
                <!-- End .bg-white -->
            </div>
            <!-- End .cta -->
        </div>
        <!-- End .container -->


        <div class="container">
            <div class="owl-carousel mt-5 mb-5 owl-simple" data-toggle="owl"
                data-owl-options='{
                            "nav": false,
                            "dots": false,
                            "margin": 30,
                            "loop": false,
                            "responsive": {
                                "0": {
                                    "items":2
                                },
                                "420": {
                                    "items":3
                                },
                                "600": {
                                    "items":4
                                },
                                "900": {
                                    "items":5
                                },
                                "1024": {
                                    "items":6
                                }
                            }
                        }'>
                <a href="#" class="brand">
                    <img src="assets/images/brands/1.png" alt="Brand Name" />
                </a>

                <a href="#" class="brand">
                    <img src="assets/images/brands/2.png" alt="Brand Name" />
                </a>

                <a href="#" class="brand">
                    <img src="assets/images/brands/3.png" alt="Brand Name" />
                </a>

                <a href="#" class="brand">
                    <img src="assets/images/brands/4.png" alt="Brand Name" />
                </a>

                <a href="#" class="brand">
                    <img src="assets/images/brands/5.png" alt="Brand Name" />
                </a>

                <a href="#" class="brand">
                    <img src="assets/images/brands/6.png" alt="Brand Name" />
                </a>
            </div>
            <!-- End .owl-carousel -->
        </div>
        <!-- End .container -->

        <div class="container">
            <hr class="mt-3 mb-6" />
        </div>
        <!-- End .container -->

        <div class="container">
            <hr class="mt-5 mb-6" />
        </div>
        <!-- End .container -->
        <div class="container">
            <hr class="mt-5 mb-0" />
        </div>
        <!-- End .container -->

        <div class="icon-boxes-container mt-2 mb-2 bg-transparent">
            <div class="container">
                <div class="row">
                    {{-- <div class="col-sm-6 col-lg-3">
                        <div class="icon-box icon-box-side">
                            <span class="icon-box-icon text-dark">
                                <i class="icon-rocket"></i>
                            </span>
                            <div class="icon-box-content">
                                <h3 class="icon-box-title">
                                    Free Shipping
                                </h3>
                                <!-- End .icon-box-title -->
                                <p>Orders $50 or more</p>
                            </div>
                            <!-- End .icon-box-content -->
                        </div>
                        <!-- End .icon-box -->
                    </div> --}}
                    <!-- End .col-sm-6 col-lg-3 -->

                    <div class="col-sm-6 col-lg-3">
                        <div class="icon-box icon-box-side">
                            <span class="icon-box-icon text-dark">
                                <i class="icon-rotate-left"></i>
                            </span>

                            <div class="icon-box-content">
                                <h3 class="icon-box-title">
                                    Free Returns
                                </h3>
                                <!-- End .icon-box-title -->
                                <p>Within 30 days</p>
                            </div>
                            <!-- End .icon-box-content -->
                        </div>
                        <!-- End .icon-box -->
                    </div>
                    <!-- End .col-sm-6 col-lg-3 -->

                    <div class="col-sm-6 col-lg-3">
                        <div class="icon-box icon-box-side">
                            <span class="icon-box-icon text-dark">
                                <i class="icon-info-circle"></i>
                            </span>

                            <div class="icon-box-content">
                                <h3 class="icon-box-title">
                                    Get 20% Off 1 Item
                                </h3>
                                <!-- End .icon-box-title -->
                                <p>when you sign up</p>
                            </div>
                            <!-- End .icon-box-content -->
                        </div>
                        <!-- End .icon-box -->
                    </div>
                    <!-- End .col-sm-6 col-lg-3 -->

                    <div class="col-sm-6 col-lg-3">
                        <div class="icon-box icon-box-side">
                            <span class="icon-box-icon text-dark">
                                <i class="icon-life-ring"></i>
                            </span>

                            <div class="icon-box-content">
                                <h3 class="icon-box-title">
                                    We Support
                                </h3>
                                <!-- End .icon-box-title -->
                                <p>24/7 amazing services</p>
                            </div>
                            <!-- End .icon-box-content -->
                        </div>
                        <!-- End .icon-box -->
                    </div>
                    <!-- End .col-sm-6 col-lg-3 -->
                </div>
                <!-- End .row -->
            </div>
            <!-- End .container -->
        </div>
        <!-- End .icon-boxes-container -->

        <div class="container">
            <div class="cta cta-separator cta-border-image cta-half mb-0"
                style="
                            background-image: url(assets/images/demos/demo-3/bg-2.jpg);
                        ">
                <div class="cta-border-wrapper bg-white">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="cta-wrapper cta-text text-center">
                                <h3 class="cta-title">Shop Social</h3>
                                <!-- End .cta-title -->
                                <p class="cta-desc">
                                    Donec nec justo eget felis facilisis
                                    fermentum. Aliquam porttitor mauris
                                    sit amet orci.
                                </p>
                                <!-- End .cta-desc -->

                                <div class="social-icons social-icons-colored justify-content-center">
                                    <a href="#" class="social-icon social-facebook" title="Facebook"
                                        target="_blank"><i class="icon-facebook-f"></i></a>
                                    <a href="#" class="social-icon social-twitter" title="Twitter"
                                        target="_blank"><i class="icon-twitter"></i></a>
                                    <a href="#" class="social-icon social-instagram" title="Instagram"
                                        target="_blank"><i class="icon-instagram"></i></a>
                                    <a href="#" class="social-icon social-youtube" title="Youtube"
                                        target="_blank"><i class="icon-youtube"></i></a>
                                    <a href="#" class="social-icon social-pinterest" title="Pinterest"
                                        target="_blank"><i class="icon-pinterest"></i></a>
                                </div>
                                <!-- End .soial-icons -->
                            </div>
                            <!-- End .cta-wrapper -->
                        </div>
                        <!-- End .col-lg-6 -->

                        <div class="col-lg-6">
                            <div class="cta-wrapper text-center">
                                <h3 class="cta-title">
                                    Get the Latest Deals
                                </h3>
                                <!-- End .cta-title -->
                                <p class="cta-desc">
                                    and <br />receive
                                    <span class="text-primary">5000FCFA coupon</span>
                                    for first shopping
                                </p>
                                <!-- End .cta-desc -->

                                <form action="#">
                                    <div class="input-group">
                                        <input type="email" class="form-control" placeholder="Enter your Email Address"
                                            aria-label="Email Adress" required />
                                        <div class="input-group-append">
                                            <button class="btn btn-primary btn-rounded" type="submit">
                                                <i class="icon-long-arrow-right"></i>
                                            </button>
                                        </div>
                                        <!-- .End .input-group-append -->
                                    </div>
                                    <!-- .End .input-group -->
                                </form>
                            </div>
                            <!-- End .cta-wrapper -->
                        </div>
                        <!-- End .col-lg-6 -->
                    </div>
                    <!-- End .row -->
                </div>
                <!-- End .bg-white -->
            </div>
            <!-- End .cta -->
        </div>
        <!-- End .container -->
    </main>
    <!-- End .main -->
@endsection
