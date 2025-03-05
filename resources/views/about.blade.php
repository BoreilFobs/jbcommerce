@extends('layouts.web')
@section('content')
    <main class="main">
        <nav aria-label="breadcrumb" class="breadcrumb-nav border-0 mb-0">
            <div class="container">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Pages</a></li>
                    <li class="breadcrumb-item active" aria-current="page">About us</li>
                </ol>
            </div><!-- End .container -->
        </nav><!-- End .breadcrumb-nav -->
        <div class="container">
            <div class="page-header page-header-big text-center"
                style="background: url('assets/img/bg-about.jpeg'); background-size: cover; background-repeat:no-repeat">
                <h1 class="page-title text-white">About us<span class="text-white">Who we are</span></h1>
            </div><!-- End .page-header -->
        </div><!-- End .container -->

        <div class="page-content pb-0">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 mb-3 mb-lg-0">
                        <h2 class="title">Our Vision</h2><!-- End .title -->
                        <p>At ElectroSphere, we envision a world where cutting-edge technology is accessible to everyone.
                            We strive to be the premier destination for tech enthusiasts and everyday consumers alike,
                            offering an unparalleled selection of electronics and smart devices that enhance and simplify
                            modern living. Our goal is to bridge the gap between innovation and practicality, bringing
                            tomorrow's technology into today's homes.</p>
                    </div><!-- End .col-lg-6 -->

                    <div class="col-lg-6">
                        <h2 class="title">Our Mission</h2><!-- End .title -->
                        <p>We are committed to providing our customers with high-quality tech products at competitive
                            prices,
                            backed by exceptional customer service and expert advice. We carefully curate our product
                            selection
                            to ensure reliability, performance, and value.
                            <br>Through educational content and personalized recommendations, we empower our customers to
                            make
                            informed decisions about their technology purchases.
                        </p>
                    </div><!-- End .col-lg-6 -->
                </div><!-- End .row -->

                <div class="mb-5"></div><!-- End .mb-4 -->
            </div><!-- End .container -->

            <div class="bg-light-2 pt-6 pb-5 mb-6 mb-lg-8">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-5 mb-3 mb-lg-0">
                            <h2 class="title">Who We Are</h2><!-- End .title -->
                            <p class="lead text-primary mb-3">A team of tech enthusiasts passionate about bringing the
                                latest
                                innovations to your fingertips with unmatched expertise and service</p>
                            <!-- End .lead text-primary -->
                            <p class="mb-2">Founded in 2015, ElectroSphere has grown from a small online retailer to a
                                comprehensive tech marketplace. Our team combines decades of experience in consumer
                                electronics,
                                IT solutions, and customer service. We pride ourselves on staying ahead of tech trends while
                                maintaining a focus on reliability and user satisfaction.</p>

                            <a href="blog.html" class="btn btn-sm btn-minwidth btn-outline-primary-2">
                                <span>VIEW OUR NEWS</span>
                                <i class="icon-long-arrow-right"></i>
                            </a>
                        </div><!-- End .col-lg-5 -->

                        <div class="col-lg-6 offset-lg-1">
                            <div class="about-images">
                                <img src="assets/images/about/img-1.jpg" alt="" class="about-img-front">
                                <img src="assets/images/about/img-2.jpg" alt="" class="about-img-back">
                            </div><!-- End .about-images -->
                        </div><!-- End .col-lg-6 -->
                    </div><!-- End .row -->
                </div><!-- End .container -->
            </div><!-- End .bg-light-2 pt-6 pb-6 -->

            <div class="container">
                <div class="row">
                    <div class="col-lg-5">
                        <div class="brands-text">
                            <h2 class="title">The world's leading tech brands in one destination.</h2><!-- End .title -->
                            <p>We partner with the most respected names in technology to bring you authentic products with
                                full warranties and dedicated support. From smartphones to smart home devices, we've got you
                                covered.</p>
                        </div><!-- End .brands-text -->
                    </div><!-- End .col-lg-5 -->
                    <div class="col-lg-7">
                        <div class="brands-display">
                            <div class="row justify-content-center">
                                <div class="col-6 col-sm-4">
                                    <a href="#" class="brand">
                                        <img src="assets/images/brands/1.png" alt="Brand Name">
                                    </a>
                                </div><!-- End .col-sm-4 -->

                                <div class="col-6 col-sm-4">
                                    <a href="#" class="brand">
                                        <img src="assets/images/brands/2.png" alt="Brand Name">
                                    </a>
                                </div><!-- End .col-sm-4 -->

                                <div class="col-6 col-sm-4">
                                    <a href="#" class="brand">
                                        <img src="assets/images/brands/3.png" alt="Brand Name">
                                    </a>
                                </div><!-- End .col-sm-4 -->

                                <div class="col-6 col-sm-4">
                                    <a href="#" class="brand">
                                        <img src="assets/images/brands/4.png" alt="Brand Name">
                                    </a>
                                </div><!-- End .col-sm-4 -->

                                <div class="col-6 col-sm-4">
                                    <a href="#" class="brand">
                                        <img src="assets/images/brands/5.png" alt="Brand Name">
                                    </a>
                                </div><!-- End .col-sm-4 -->

                                <div class="col-6 col-sm-4">
                                    <a href="#" class="brand">
                                        <img src="assets/images/brands/6.png" alt="Brand Name">
                                    </a>
                                </div><!-- End .col-sm-4 -->

                                <div class="col-6 col-sm-4">
                                    <a href="#" class="brand">
                                        <img src="assets/images/brands/7.png" alt="Brand Name">
                                    </a>
                                </div><!-- End .col-sm-4 -->

                                <div class="col-6 col-sm-4">
                                    <a href="#" class="brand">
                                        <img src="assets/images/brands/8.png" alt="Brand Name">
                                    </a>
                                </div><!-- End .col-sm-4 -->

                                <div class="col-6 col-sm-4">
                                    <a href="#" class="brand">
                                        <img src="assets/images/brands/9.png" alt="Brand Name">
                                    </a>
                                </div><!-- End .col-sm-4 -->
                            </div><!-- End .row -->
                        </div><!-- End .brands-display -->
                    </div><!-- End .col-lg-7 -->
                </div><!-- End .row -->

                <hr class="mt-4 mb-6">

                {{-- <h2 class="title text-center mb-4">Meet Our Team</h2><!-- End .title text-center mb-2 --> --}}

                {{-- <div class="row">
                    <div class="col-md-4">
                        <div class="member member-anim text-center">
                            <figure class="member-media">
                                <img src="assets/images/team/member-1.jpg" alt="member photo">

                                <figcaption class="member-overlay">
                                    <div class="member-overlay-content">
                                        <h3 class="member-title">Samanta Grey<span>Founder & CEO</span></h3>
                                        <!-- End .member-title -->
                                        <p>With over 15 years in the tech industry and a background in computer engineering,
                                            Samanta leads our vision for bringing innovative technology to consumers
                                            worldwide.</p>
                                        <div class="social-icons social-icons-simple">
                                            <a href="#" class="social-icon" title="Facebook" target="_blank"><i
                                                    class="icon-facebook-f"></i></a>
                                            <a href="#" class="social-icon" title="Twitter" target="_blank"><i
                                                    class="icon-twitter"></i></a>
                                            <a href="#" class="social-icon" title="Instagram" target="_blank"><i
                                                    class="icon-instagram"></i></a>
                                        </div><!-- End .soial-icons -->
                                    </div><!-- End .member-overlay-content -->
                                </figcaption><!-- End .member-overlay -->
                            </figure><!-- End .member-media -->
                            <div class="member-content">
                                <h3 class="member-title">Samanta Grey<span>Founder & CEO</span></h3>
                                <!-- End .member-title -->
                            </div><!-- End .member-content -->
                        </div><!-- End .member -->
                    </div><!-- End .col-md-4 -->

                    <div class="col-md-4">
                        <div class="member member-anim text-center">
                            <figure class="member-media">
                                <img src="assets/images/team/member-2.jpg" alt="member photo">

                                <figcaption class="member-overlay">
                                    <div class="member-overlay-content">
                                        <h3 class="member-title">Bruce Sutton<span>Sales & Marketing Manager</span></h3>
                                        <!-- End .member-title -->
                                        <p>Bruce brings a decade of experience in digital marketing and e-commerce strategy.
                                            His customer-centric approach has helped ElectroSphere build lasting
                                            relationships
                                            with tech enthusiasts.</p>
                                        <div class="social-icons social-icons-simple">
                                            <a href="#" class="social-icon" title="Facebook" target="_blank"><i
                                                    class="icon-facebook-f"></i></a>
                                            <a href="#" class="social-icon" title="Twitter" target="_blank"><i
                                                    class="icon-twitter"></i></a>
                                            <a href="#" class="social-icon" title="Instagram" target="_blank"><i
                                                    class="icon-instagram"></i></a>
                                        </div><!-- End .soial-icons -->
                                    </div><!-- End .member-overlay-content -->
                                </figcaption><!-- End .member-overlay -->
                            </figure><!-- End .member-media -->
                            <div class="member-content">
                                <h3 class="member-title">Bruce Sutton<span>Sales & Marketing Manager</span></h3>
                                <!-- End .member-title -->
                            </div><!-- End .member-content -->
                        </div><!-- End .member -->
                    </div><!-- End .col-md-4 -->

                    <div class="col-md-4">
                        <div class="member member-anim text-center">
                            <figure class="member-media">
                                <img src="assets/images/team/member-3.jpg" alt="member photo">

                                <figcaption class="member-overlay">
                                    <div class="member-overlay-content">
                                        <h3 class="member-title">Janet Joy<span>Product Manager</span></h3>
                                        <!-- End .member-title -->
                                        <p>With a keen eye for emerging tech trends and consumer needs, Janet oversees our
                                            product selection process. Her expertise ensures we offer only the most reliable
                                            and innovative devices.</p>
                                        <div class="social-icons social-icons-simple">
                                            <a href="#" class="social-icon" title="Facebook" target="_blank"><i
                                                    class="icon-facebook-f"></i></a>
                                            <a href="#" class="social-icon" title="Twitter" target="_blank"><i
                                                    class="icon-twitter"></i></a>
                                            <a href="#" class="social-icon" title="Instagram" target="_blank"><i
                                                    class="icon-instagram"></i></a>
                                        </div><!-- End .soial-icons -->
                                    </div><!-- End .member-overlay-content -->
                                </figcaption><!-- End .member-overlay -->
                            </figure><!-- End .member-media -->
                            <div class="member-content">
                                <h3 class="member-title">Janet Joy<span>Product Manager</span></h3>
                                <!-- End .member-title -->
                            </div><!-- End .member-content -->
                        </div><!-- End .member -->
                    </div><!-- End .col-md-4 -->
                </div><!-- End .row -->
            </div><!-- End .container --> --}}

                <div class="mb-2"></div><!-- End .mb-2 -->

                <div class="about-testimonials bg-light-2 pt-6 pb-6">
                    <div class="container">
                        <h2 class="title text-center mb-3">What Customer Say About Us</h2><!-- End .title text-center -->

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
                                <img src="assets/images/testimonials/user-1.jpg" alt="user">
                                <p>" ElectroSphere has completely transformed how I shop for tech. Their product
                                    descriptions
                                    are
                                    detailed and accurate, and their customer service team actually understands technology.
                                    <br>When
                                    my new laptop had an issue, their technical support walked me through troubleshooting
                                    steps
                                    that
                                    fixed it immediately. I won't shop for electronics anywhere else now. "
                                </p>
                                <cite>
                                    Jenson Gregory
                                    <span>Customer</span>
                                </cite>
                            </blockquote><!-- End .testimonial -->

                            <blockquote class="testimonial text-center">
                                <img src="assets/images/testimonials/user-2.jpg" alt="user">
                                <p>" As someone who's not particularly tech-savvy, I was nervous about purchasing smart home
                                    devices.
                                    The ElectroSphere team took the time to understand my needs and recommended products
                                    that
                                    were
                                    perfect for my situation. The installation guides were clear, and when I had questions,
                                    their
                                    support team was just a call away. My home is now smarter, and I didn't have to stress
                                    about
                                    it! "</p>

                                <cite>
                                    Victoria Ventura
                                    <span>Customer</span>
                                </cite>
                            </blockquote><!-- End .testimonial -->
                        </div><!-- End .testimonials-slider owl-carousel -->
                    </div><!-- End .container -->
                </div><!-- End .bg-light-2 pt-5 pb-6 -->
            </div><!-- End .page-content -->
    </main><!-- End .main -->
@endsection
