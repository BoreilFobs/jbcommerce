@extends('layouts.web')
@section('content')
        <!-- Single Page Header start -->
        <div class="container-fluid page-header py-5">
            <h1 class="text-center text-white display-6 wow fadeInUp" data-wow-delay="0.1s">À Propos de Nous</h1>
            <ol class="breadcrumb justify-content-center mb-0 wow fadeInUp" data-wow-delay="0.3s">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Accueil</a></li>
                <li class="breadcrumb-item"><a href="#">Pages</a></li>
                <li class="breadcrumb-item active text-white">À Propos</li>
            </ol>
        </div>
        <!-- Single Page Header End -->

        <!-- About Content Start -->
        <div class="page-content pb-0">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="h-100">
                        <div class="d-flex align-items-center mb-3">
                            <div class="btn-square bg-primary text-white rounded-circle me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-eye fa-lg"></i>
                            </div>
                            <h2 class="text-primary mb-0">Notre Vision</h2>
                        </div>
                        <p class="mb-4">Chez JB Shop, nous envisageons un monde où les technologies de pointe sont accessibles à tous. Nous nous efforçons d'être la destination privilégiée pour les passionnés de technologie et les consommateurs du quotidien.</p>
                        <p class="text-dark"><i class="fas fa-check text-primary me-3"></i>Innovation accessible à tous</p>
                        <p class="text-dark"><i class="fas fa-check text-primary me-3"></i>Technologie qui simplifie la vie</p>
                        <p class="text-dark"><i class="fas fa-check text-primary me-3"></i>Excellence et confiance</p>
                    </div>
                </div>
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="h-100">
                        <div class="d-flex align-items-center mb-3">
                            <div class="btn-square bg-primary text-white rounded-circle me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-bullseye fa-lg"></i>
                            </div>
                            <h2 class="text-primary mb-0">Notre Mission</h2>
                        </div>
                        <p class="mb-4">Nous nous engageons à fournir à nos clients des produits technologiques de haute qualité à des prix compétitifs, soutenus par un service client exceptionnel et des conseils d'experts.</p>
                        <p class="text-dark"><i class="fas fa-check text-primary me-3"></i>Produits de haute qualité</p>
                        <p class="text-dark"><i class="fas fa-check text-primary me-3"></i>Prix compétitifs garantis</p>
                        <p class="text-dark"><i class="fas fa-check text-primary me-3"></i>Service client dédié</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-light py-5 mb-6">
            <div class="container">
                <div class="row align-items-center g-4">
                    <div class="col-lg-5 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="d-flex align-items-center mb-4">
                            <div class="btn-square bg-primary text-white rounded-circle me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-users fa-lg"></i>
                            </div>
                            <h2 class="text-primary mb-0">Qui Sommes-Nous</h2>
                        </div>
                        <p class="text-primary fw-medium mb-4" style="font-size: 1.1rem;">Une équipe de passionnés de technologie dédiée à apporter les dernières innovations à portée de main avec une expertise et un service inégalés</p>
                        <p class="mb-4">Fondée en 2015, JB Shop est passée d'un petit détaillant en ligne à un marché technologique complet. Notre équipe combine des décennies d'expérience dans l'électronique grand public, les solutions informatiques et le service client.</p>
                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <div class="bg-white rounded p-3 text-center shadow-sm">
                                    <h3 class="text-primary mb-0">10+</h3>
                                    <small class="text-muted">Années d'expérience</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-white rounded p-3 text-center shadow-sm">
                                    <h3 class="text-primary mb-0">5000+</h3>
                                    <small class="text-muted">Clients satisfaits</small>
                                </div>
                            </div>
                        </div>
                        <a href="{{ url('/shop') }}" class="btn btn-primary rounded-pill px-4 py-3">
                            <i class="fas fa-shopping-bag me-2"></i>Découvrir nos Produits
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
            <div class="row g-5 align-items-center">
                <div class="col-lg-5 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="brands-text">
                        <div class="d-flex align-items-center mb-4">
                            <div class="btn-square bg-primary text-white rounded-circle me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-award fa-lg"></i>
                            </div>
                            <h2 class="text-primary mb-0">Marques de Confiance</h2>
                        </div>
                        <p class="mb-4">Nous collaborons avec les marques technologiques les plus respectées pour vous proposer des produits authentiques avec garanties complètes et support dédié.</p>
                        <p class="text-dark"><i class="fas fa-check text-primary me-3"></i>Produits 100% authentiques</p>
                        <p class="text-dark"><i class="fas fa-check text-primary me-3"></i>Garantie constructeur</p>
                        <p class="text-dark"><i class="fas fa-check text-primary me-3"></i>Support technique expert</p>
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
            <div class="about-testimonials bg-light py-5 wow fadeInUp" data-wow-delay="0.1s">
                <div class="container">
                    <div class="text-center mx-auto mb-5" style="max-width: 900px;">
                        <h4 class="text-primary border-bottom border-primary border-2 d-inline-block pb-2">Témoignages</h4>
                        <h2 class="display-6 mb-3">Ce que nos Clients disent de Nous</h2>
                        <p class="text-muted">Découvrez les expériences de nos clients satisfaits</p>
                    </div>
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
        <!-- About Content End -->
@endsection
