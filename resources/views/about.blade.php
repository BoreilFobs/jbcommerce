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
                    <h2 class="title mb-3">Notre Vision</h2>
                    <p>Chez JB Shop, nous envisageons un monde où les technologies de pointe sont accessibles à tous. Nous nous efforçons d'être la destination privilégiée pour les passionnés de technologie et les consommateurs du quotidien, offrant une sélection inégalée d'électronique et d'appareils intelligents qui améliorent et simplifient la vie moderne. Notre objectif est de combler le fossé entre l'innovation et la praticité.</p>
                </div>
                <div class="col-lg-6">
                    <h2 class="title mb-3">Notre Mission</h2>
                    <p>Nous nous engageons à fournir à nos clients des produits technologiques de haute qualité à des prix compétitifs, soutenus par un service client exceptionnel et des conseils d'experts. Nous sélectionnons soigneusement nos produits pour garantir la fiabilité, les performances et la valeur. Grâce à un contenu éducatif et des recommandations personnalisées, nous permettons à nos clients de prendre des décisions éclairées.</p>
                </div>
            </div>
            <div class="mb-5"></div>
        </div>
        <div class="bg-light py-5 mb-6">
            <div class="container">
                <div class="row align-items-center g-4">
                    <div class="col-lg-5">
                        <h2 class="title mb-3">Qui Sommes-Nous</h2>
                        <p class="lead text-primary mb-3">Une équipe de passionnés de technologie dédiée à apporter les dernières innovations à portée de main avec une expertise et un service inégalés</p>
                        <p class="mb-2">Fondée en 2015, JB Shop est passée d'un petit détaillant en ligne à un marché technologique complet. Notre équipe combine des décennies d'expérience dans l'électronique grand public, les solutions informatiques et le service client. Nous sommes fiers de rester à l'avant-garde des tendances technologiques tout en maintenant l'accent sur la fiabilité et la satisfaction des utilisateurs.</p>
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
                            <p>" JB Shop a complètement transformé ma façon d'acheter de la technologie. Leurs descriptions de produits sont détaillées et précises, et leur équipe de service client comprend vraiment la technologie. Lorsque mon nouvel ordinateur portable a eu un problème, leur support technique m'a guidé à travers les étapes de dépannage qui l'ont résolu immédiatement. Je n'achèterai plus d'électronique ailleurs maintenant. "</p>
                            <cite>
                                Jenson Gregory
                                <span class="d-block text-muted">Customer</span>
                            </cite>
                        </blockquote>
                        <blockquote class="testimonial text-center">
                            <img src="{{ asset('assets/images/testimonials/user-2.jpg') }}" alt="user" class="rounded-circle mb-3" style="width:80px;">
                            <p>" En tant que personne qui n'est pas particulièrement experte en technologie, j'étais nerveux à l'idée d'acheter des appareils domotiques. L'équipe de JB Shop a pris le temps de comprendre mes besoins et a recommandé des produits parfaits pour ma situation. Les guides d'installation étaient clairs, et quand j'avais des questions, leur équipe de support était à portée de main. Ma maison est maintenant plus intelligente, et je n'ai pas eu à stresser ! "</p>
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
