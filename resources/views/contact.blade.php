@extends('layouts.web')
@section('content')
    <main class="main">
        <nav aria-label="breadcrumb" class="breadcrumb-nav border-0 mb-0">
            <div class="container">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Pages</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Contact us</li>
                </ol>
            </div><!-- End .container -->
        </nav><!-- End .breadcrumb-nav -->
        <div class="container">
            <div class="page-header page-header-big text-center"
                style="background-image: url('assets/img/bg contact us.jpeg')">
                <h1 class="page-title text-white">Contact us<span class="text-white">keep in touch with us</span></h1>
            </div><!-- End .page-header -->
        </div><!-- End .container -->

        <div class="page-content pb-0">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 mb-2 mb-lg-0">
                        <h2 class="title mb-1">Contact Information</h2><!-- End .title mb-2 -->
                        <p class="mb-3">Welcome to our contact page. We value your feedback and inquiries. Our dedicated
                            team is ready to assist you with any questions, concerns, or information you may need about our
                            products and services. Please feel free to reach out using the contact details below or fill out
                            the form on this page.</p>
                        <div class="row">
                            <div class="col-sm-7">
                                <div class="contact-info">
                                    <h3>The Office</h3>

                                    <ul class="contact-list">
                                        <li>
                                            <i class="icon-map-marker"></i>
                                            70 Washington Square South New York, NY 10012, United States
                                        </li>
                                        <li>
                                            <i class="icon-phone"></i>
                                            <a href="tel:#">+92 423 567</a>
                                        </li>
                                        <li>
                                            <i class="icon-envelope"></i>
                                            <a href="mailto:#">info@Molla.com</a>
                                        </li>
                                    </ul><!-- End .contact-list -->
                                </div><!-- End .contact-info -->
                            </div><!-- End .col-sm-7 -->

                            <div class="col-sm-5">
                                <div class="contact-info">
                                    <h3>The Office</h3>

                                    <ul class="contact-list">
                                        <li>
                                            <i class="icon-clock-o"></i>
                                            <span class="text-dark">Monday-Saturday</span> <br>11am-7pm ET
                                        </li>
                                        <li>
                                            <i class="icon-calendar"></i>
                                            <span class="text-dark">Sunday</span> <br>11am-6pm ET
                                        </li>
                                    </ul><!-- End .contact-list -->
                                </div><!-- End .contact-info -->
                            </div><!-- End .col-sm-5 -->
                        </div><!-- End .row -->
                    </div><!-- End .col-lg-6 -->
                    <div class="col-lg-6">
                        <h2 class="title mb-1">Got Any Questions?</h2><!-- End .title mb-2 -->
                        <p class="mb-2">Use the form below to get in touch with the sales team</p>
                        @if ($errors->any())
                            @foreach ($errors->all() as $error)
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>error!</strong> {{ $error }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endforeach
                        @endif
                        <form method="POST" action={{ url('message/create') }} class="contact-form mb-3">
                            @csrf
                            @method('post')
                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="name" class="sr-only">Name</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="Name *" required>
                                </div><!-- End .col-sm-6 -->

                                <div class="col-sm-6">
                                    <label for="email" class="sr-only">Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="Email *" required>
                                </div><!-- End .col-sm-6 -->
                            </div><!-- End .row -->

                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="phone" class="sr-only">Phone</label>
                                    <input type="tel" class="form-control" id="phone" name="phone"
                                        placeholder="Phone">
                                </div><!-- End .col-sm-6 -->

                                <div class="col-sm-6">
                                    <label for="object" class="sr-only">Subject</label>
                                    <input type="text" class="form-control" id="object" name="object"
                                        placeholder="Subject">
                                </div><!-- End .col-sm-6 -->
                            </div><!-- End .row -->

                            <label for="message" class="sr-only">Message</label>
                            <textarea class="form-control" cols="30" rows="4" id="message" name="message" required
                                placeholder="Message *"></textarea>

                            <button type="submit" class="btn btn-outline-primary-2 btn-minwidth-sm">
                                <span>SUBMIT</span>
                                <i class="icon-long-arrow-right"></i>
                            </button>
                        </form><!-- End .contact-form -->
                    </div><!-- End .col-lg-6 -->
                </div><!-- End .row -->

                <hr class="mt-4 mb-5">

                <div class="stores mb-4 mb-lg-5">
                    <h2 class="title text-center mb-3">Our Stores</h2><!-- End .title text-center mb-2 -->

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="store">
                                <div class="row">
                                    <div class="col-sm-5 col-xl-6">
                                        <figure class="store-media mb-2 mb-lg-0">
                                            <img src="assets/images/stores/img-1.jpg" alt="image">
                                        </figure><!-- End .store-media -->
                                    </div><!-- End .col-xl-6 -->
                                    <div class="col-sm-7 col-xl-6">
                                        <div class="store-content">
                                            <h3 class="store-title">Wall Street Plaza</h3><!-- End .store-title -->
                                            <address>88 Pine St, New York, NY 10005, USA</address>
                                            <div><a href="tel:#">+1 987-876-6543</a></div>

                                            <h4 class="store-subtitle">Store Hours:</h4><!-- End .store-subtitle -->
                                            <div>Monday - Saturday 11am to 7pm</div>
                                            <div>Sunday 11am to 6pm</div>

                                            <a href="#" class="btn btn-link" target="_blank"><span>View
                                                    Map</span><i class="icon-long-arrow-right"></i></a>
                                        </div><!-- End .store-content -->
                                    </div><!-- End .col-xl-6 -->
                                </div><!-- End .row -->
                            </div><!-- End .store -->
                        </div><!-- End .col-lg-6 -->

                        <div class="col-lg-6">
                            <div class="store">
                                <div class="row">
                                    <div class="col-sm-5 col-xl-6">
                                        <figure class="store-media mb-2 mb-lg-0">
                                            <img src="assets/images/stores/img-2.jpg" alt="image">
                                        </figure><!-- End .store-media -->
                                    </div><!-- End .col-xl-6 -->

                                    <div class="col-sm-7 col-xl-6">
                                        <div class="store-content">
                                            <h3 class="store-title">One New York Plaza</h3><!-- End .store-title -->
                                            <address>88 Pine St, New York, NY 10005, USA</address>
                                            <div><a href="tel:#">+1 987-876-6543</a></div>

                                            <h4 class="store-subtitle">Store Hours:</h4><!-- End .store-subtitle -->
                                            <div>Monday - Friday 9am to 8pm</div>
                                            <div>Saturday - 9am to 2pm</div>
                                            <div>Sunday - Closed</div>

                                            <a href="#" class="btn btn-link" target="_blank"><span>View
                                                    Map</span><i class="icon-long-arrow-right"></i></a>
                                        </div><!-- End .store-content -->
                                    </div><!-- End .col-xl-6 -->
                                </div><!-- End .row -->
                            </div><!-- End .store -->
                        </div><!-- End .col-lg-6 -->
                    </div><!-- End .row -->
                </div><!-- End .stores -->
            </div><!-- End .container -->
            {{-- <div id="map"></div><!-- End #map --> --}}
        </div><!-- End .page-content -->
    </main><!-- End .main -->
@endsection
