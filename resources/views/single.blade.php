@extends('layouts.web')
@section('content')
 <!-- Single Products Start -->
        <div class="container-fluid shop py-5">
            <div class="container py-5">
                <div class="row g-4">
                    <div class="col-lg-5 col-xl-3 wow fadeInUp" data-wow-delay="0.1s">
                        @include('layouts.store')
                    </div>
                        <div class="col-lg-7 col-xl-9 wow fadeInUp" data-wow-delay="0.1s">
                            <div class="row g-4 single-product">
                                <div class="col-xl-6">
                                    <div class="single-carousel owl-carousel">
                                        @php
                                            $offer_images = is_string($offer->images) ? json_decode($offer->images, true) : ($offer->images ?: []);
                                        @endphp
                                    @foreach ($offer_images as $index => $img)
                                            <div class="single-item" data-dot="<img class='img-fluid' src='{{'/storage/offer_img/product' . $offer->id . "/" . $offer_images[$index]}}' alt=''>">
                                                <div class="single-inner bg-light rounded">
                                                    <img src="{{'/storage/offer_img/product' . $offer->id . "/" . $offer_images[$index]}}" class="img-fluid rounded" alt="Image">
                                                </div>
                                            </div>
                                    @endforeach
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <h4 class="fw-bold mb-3">{{$offer->name}}</h4>
                                    <p class="mb-3">Category: {{$offer->category}}</p>
                                    <h5 class="fw-bold mb-3">{{number_format($offer->price, 0, '.', ',')}}FCFA</h5>
                                    <div class="d-flex mb-4">
                                        <i class="fa fa-star text-secondary"></i>
                                        <i class="fa fa-star text-secondary"></i>
                                        <i class="fa fa-star text-secondary"></i>
                                        <i class="fa fa-star text-secondary"></i>
                                        <i class="fa fa-star"></i>
                                    </div>
                                    <div class="mb-3">
                                        <div class="btn btn-primary d-inline-block rounded text-white py-1 px-4 me-2"><i class="fab fa-facebook-f me-1"></i> Share</div>
                                        <div class="btn btn-secondary d-inline-block rounded text-white py-1 px-4 ms-2"><i class="fab fa-twitter ms-1"></i> Share</div>
                                    </div>
                                    <div class="d-flex flex-column mb-3">
                                        {{-- <small>Product SKU: N/A</small> --}}
                                        <small>Disponible: <strong class="text-primary">{{$offer->quantity}} en stock</strong></small>
                                    </div>
                                    <p class="mb-4">{{$offer->description}}</p>
                                    {{-- <p class="mb-4">Susp endisse ultricies nisi vel quam suscipit. Sabertooth peacock flounder; chain pickerel hatchetfish, pencilfish snailfish</p> --}}
                                    {{-- <div class="input-group quantity mb-5" style="width: 100px;">
                                        <div class="input-group-btn">
                                            <button class="btn btn-sm btn-minus rounded-circle bg-light border" >
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                        <input type="text" class="form-control form-control-sm text-center border-0" value="1">
                                        <div class="input-group-btn">
                                            <button class="btn btn-sm btn-plus rounded-circle bg-light border">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div> --}}
                                    @if(Auth::check())
                                        <a href="{{url("/cart/". $offer->id . "/create/" . Auth::id())}}" class="btn btn-primary border border-secondary rounded-pill px-4 py-2 mb-4 text-primary"><i class="fa fa-shopping-bag me-2 text-white"></i> Ajouter au panier</a>
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-primary border border-secondary rounded-pill px-4 py-2 mb-4 text-primary"><i class="fa fa-shopping-bag me-2 text-white"></i> Ajouter au panier</a>
                                    @endif
                                </div>
                                {{-- <div class="col-lg-12">
                                    <nav>
                                        <div class="nav nav-tabs mb-3">
                                            <button class="nav-link active border-white border-bottom-0" type="button" role="tab"
                                                id="nav-about-tab" data-bs-toggle="tab" data-bs-target="#nav-about"
                                                aria-controls="nav-about" aria-selected="true">Description</button>
                                            <button class="nav-link border-white border-bottom-0" type="button" role="tab"
                                                id="nav-mission-tab" data-bs-toggle="tab" data-bs-target="#nav-mission"
                                                aria-controls="nav-mission" aria-selected="false">Reviews</button>
                                        </div>
                                    </nav>
                                    <div class="tab-content mb-5">
                                        <div class="tab-pane active" id="nav-about" role="tabpanel" aria-labelledby="nav-about-tab">
                                            <p>Our new <b class="fw-bold">HPB12 / A12 battery</b> is rated at 2000mAh and designed to power up Black and Decker / FireStorm line of 12V tools allowing users to run multiple devices off the same battery pack. The HPB12 is compatible with the following Black and Decker power tool models:
                                            </p>
                                            <b class="fw-bold">Black & Decker Drills and Drivers:</b>
                                            <p class="small">BD12PSK, BDG1200K, BDGL12K, BDID1202, CD1200SK, CD12SFK, CDC1200K, CDC120AK, CDC120ASB, CP122K, CP122KB, CP12K, CP12KB, EPC12, EPC126, EPC126BK, EPC12CA, EPC12CABK, HP122K, HP122KD, HP126F2B, HP126F2K, HP126F3B, HP126F3K, HP126FBH, HP126FSC, HP126FSH, HP126K, HP128F3B, HP12K, HP12KD, HPD1200, HPD1202, HPD1202KF, HPD12K-2, PS122K, PS122KB, PS12HAK, SS12, SX3000, SX3500, XD1200, XD1200K, XTC121
                                            </p>
                                            <b class="fw-bold">lack & Decker Impact Wrenches:</b>
                                            <p class="small">SX5000, XTC12IK, XTC12IKH</p>
                                            <b class="fw-bold">Black & Decker Multi-Tools:</b>
                                            <p class="small">KC2000FK</p>
                                            <b class="fw-bold">Black & Decker Nailers:</b>
                                            <p class="small">BDBN1202</p>
                                            <b class="fw-bold">Black & Decker Screwdrivers:</b>
                                            <p class="small">HP9019K</p>
                                            <b class="fw-bold mb-0">Best replacement for the following Black and Decker OEM battery part numbers:</b>
                                            <p class="small">HPB12, A12, A12EX, A12-XJ, A1712, B-8315, BD1204L, BD-1204L, BPT1047, FS120B, FS120BX, FSB12.</p>
                                        </div>
                                        <div class="tab-pane" id="nav-mission" role="tabpanel" aria-labelledby="nav-mission-tab">
                                            <div class="d-flex">
                                                <img src="img/avatar.jpg" class="img-fluid rounded-circle p-3" style="width: 100px; height: 100px;" alt="">
                                                <div class="">
                                                    <p class="mb-2" style="font-size: 14px;">April 12, 2024</p>
                                                    <div class="d-flex justify-content-between">
                                                        <h5>Jason Smith</h5>
                                                        <div class="d-flex mb-3">
                                                            <i class="fa fa-star text-secondary"></i>
                                                            <i class="fa fa-star text-secondary"></i>
                                                            <i class="fa fa-star text-secondary"></i>
                                                            <i class="fa fa-star text-secondary"></i>
                                                            <i class="fa fa-star"></i>
                                                        </div>
                                                    </div>
                                                    <p>The generated Lorem Ipsum is therefore always free from repetition injected humour, or non-characteristic 
                                                        words etc. Susp endisse ultricies nisi vel quam suscipit </p>
                                                </div>
                                            </div>
                                            <div class="d-flex">
                                                <img src="img/avatar.jpg" class="img-fluid rounded-circle p-3" style="width: 100px; height: 100px;" alt="">
                                                <div class="">
                                                    <p class="mb-2" style="font-size: 14px;">April 12, 2024</p>
                                                    <div class="d-flex justify-content-between">
                                                        <h5>Sam Peters</h5>
                                                        <div class="d-flex mb-3">
                                                            <i class="fa fa-star text-secondary"></i>
                                                            <i class="fa fa-star text-secondary"></i>
                                                            <i class="fa fa-star text-secondary"></i>
                                                            <i class="fa fa-star"></i>
                                                            <i class="fa fa-star"></i>
                                                        </div>
                                                    </div>
                                                    <p class="text-dark">The generated Lorem Ipsum is therefore always free from repetition injected humour, or non-characteristic 
                                                        words etc. Susp endisse ultricies nisi vel quam suscipit </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="nav-vision" role="tabpanel">
                                            <p class="text-dark">Tempor erat elitr rebum at clita. Diam dolor diam ipsum et tempor sit. Aliqu diam
                                                amet diam et eos labore. 3</p>
                                            <p class="mb-0">Diam dolor diam ipsum et tempor sit. Aliqu diam amet diam et eos labore.
                                                Clita erat ipsum et lorem et sit</p>
                                        </div>
                                    </div>
                                </div> --}}
                                {{-- <form action="#">
                                    <h4 class="mb-5 fw-bold">Leave a Reply</h4>
                                    <div class="row g-4">
                                        <div class="col-lg-6">
                                            <div class="border-bottom rounded">
                                                <input type="text" class="form-control border-0 me-4" placeholder="Yur Name *">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="border-bottom rounded">
                                                <input type="email" class="form-control border-0" placeholder="Your Email *">
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="border-bottom rounded my-4">
                                                <textarea name="" id="" class="form-control border-0" cols="30" rows="8" placeholder="Your Review *" spellcheck="false"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="d-flex justify-content-between py-3 mb-5">
                                                <div class="d-flex align-items-center">
                                                    <p class="mb-0 me-3">Please rate:</p>
                                                    <div class="d-flex align-items-center" style="font-size: 12px;">
                                                        <i class="fa fa-star text-muted"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                    </div>
                                                </div>
                                                <a href="#" class="btn btn-primary border border-secondary text-primary rounded-pill px-4 py-3"> Post Comment</a>
                                            </div>
                                        </div>
                                    </div>
                                </form> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Single Products End -->

        <!-- Related Product Start -->
        @include('layouts.related')
        <!-- Related Product End -->
@endsection