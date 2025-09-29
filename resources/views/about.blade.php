@extends('layouts.web')
@section('content')
<main class="main">
    <nav aria-label="breadcrumb" class="breadcrumb-nav border-0 mb-0 bg-light py-2">
        <div class="container">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Pages</a></li>
                <li class="breadcrumb-item active" aria-current="page">About us</li>
            </ol>
        </div>
    </nav>
    <div class="container">
        <div class="page-header page-header-big text-center text-white mb-4"
            style="background: url('{{ asset('assets/img/bg-about.jpeg') }}') center center/cover no-repeat;">
            <h1 class="page-title">About us <span class="d-block">Who we are</span></h1>
        </div>
    </div>
    <div class="page-content pb-0">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-6">
                    <h2 class="title mb-3">Our Vision</h2>
                    <p>At ElectroSphere, we envision a world where cutting-edge technology is accessible to everyone. We strive to be the premier destination for tech enthusiasts and everyday consumers alike, offering an unparalleled selection of electronics and smart devices that enhance and simplify modern living. Our goal is to bridge the gap between innovation and practicality, bringing tomorrow's technology into today's homes.</p>
                </div>
                <div class="col-lg-6">
                    <h2 class="title mb-3">Our Mission</h2>
                    <p>We are committed to providing our customers with high-quality tech products at competitive prices, backed by exceptional customer service and expert advice. We carefully curate our product selection to ensure reliability, performance, and value.<br>Through educational content and personalized recommendations, we empower our customers to make informed decisions about their technology purchases.</p>
                </div>
            </div>
            <div class="mb-5"></div>
        </div>
        <div class="bg-light py-5 mb-6">
            <div class="container">
                <div class="row align-items-center g-4">
                    <div class="col-lg-5">
                        <h2 class="title mb-3">Who We Are</h2>
                        <p class="lead text-primary mb-3">A team of tech enthusiasts passionate about bringing the latest innovations to your fingertips with unmatched expertise and service</p>
                        <p class="mb-2">Founded in 2015, ElectroSphere has grown from a small online retailer to a comprehensive tech marketplace. Our team combines decades of experience in consumer electronics, IT solutions, and customer service. We pride ourselves on staying ahead of tech trends while maintaining a focus on reliability and user satisfaction.</p>
                        <a href="{{ url('blog') }}" class="btn btn-outline-primary btn-sm">
                            VIEW OUR NEWS <i class="icon-long-arrow-right"></i>
                        </a>
                    </div>
                    <div class="col-lg-6 offset-lg-1">
                        <div class="about-images d-flex gap-3">
                            <img src="{{ asset('assets/images/about/img-1.jpg') }}" alt="" class="img-fluid rounded shadow">
                            <img src="{{ asset('assets/images/about/img-2.jpg') }}" alt="" class="img-fluid rounded shadow">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-5">
                    <div class="brands-text">
                        <h2 class="title mb-3">The world's leading tech brands in one destination.</h2>
                        <p>We partner with the most respected names in technology to bring you authentic products with full warranties and dedicated support. From smartphones to smart home devices, we've got you covered.</p>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="brands-display">
                        <div class="row justify-content-center g-2">
                            @for($i = 1; $i <= 9; $i++)
                                <div class="col-6 col-sm-4 mb-2">
                                    <a href="#" class="brand d-block p-2 bg-white rounded shadow-sm">
                                        <img src="{{ asset('assets/images/brands/' . $i . '.png') }}" alt="Brand Name" class="img-fluid">
                                    </a>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            <hr class="mt-4 mb-6">
            <div class="mb-2"></div>
            <div class="about-testimonials bg-light py-5">
                <div class="container">
                    <h2 class="title text-center mb-4">What Customer Say About Us</h2>
                    <div class="owl-carousel owl-simple owl-testimonials-photo" data-toggle="owl"
                        data-owl-options='{
                            "nav": false,
                            "dots": true,
                            "margin": 20,
                            "loop": false,
                            "responsive": {
                                "1200": {
                                    "nav": true
                                }
                            }
                        }'>
                        <blockquote class="testimonial text-center">
                            <img src="{{ asset('assets/images/testimonials/user-1.jpg') }}" alt="user" class="rounded-circle mb-3" style="width:80px;">
                            <p>" ElectroSphere has completely transformed how I shop for tech. Their product descriptions are detailed and accurate, and their customer service team actually understands technology.<br>When my new laptop had an issue, their technical support walked me through troubleshooting steps that fixed it immediately. I won't shop for electronics anywhere else now. "</p>
                            <cite>
                                Jenson Gregory
                                <span class="d-block text-muted">Customer</span>
                            </cite>
                        </blockquote>
                        <blockquote class="testimonial text-center">
                            <img src="{{ asset('assets/images/testimonials/user-2.jpg') }}" alt="user" class="rounded-circle mb-3" style="width:80px;">
                            <p>" As someone who's not particularly tech-savvy, I was nervous about purchasing smart home devices. The ElectroSphere team took the time to understand my needs and recommended products that were perfect for my situation. The installation guides were clear, and when I had questions, their support team was just a call away. My home is now smarter, and I didn't have to stress about it! "</p>
                            <cite>
                                Victoria Ventura
                                <span class="d-block text-muted">Customer</span>
                            </cite>
                        </blockquote>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
