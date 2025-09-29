@extends('layouts.web')
@section('content')
        <!-- Single Page Header start -->
            <div class="container-fluid page-header py-5">
                <h1 class="text-center text-white display-6 wow fadeInUp" data-wow-delay="0.1s">Contactez-nous</h1>
                <ol class="breadcrumb justify-content-center mb-0 wow fadeInUp" data-wow-delay="0.3s">
                    <li class="breadcrumb-item"><a href="#">Accueil</a></li>
                    <li class="breadcrumb-item"><a href="#">Pages</a></li>
                    <li class="breadcrumb-item active text-white">Contact</li>
                </ol>
            </div>
            <!-- Single Page Header End -->

            <!-- Contacts Start -->
            <div class="container-fluid contact py-5">
                <div class="container py-5">
                    <div class="p-5 bg-light rounded">
                        <div class="row g-4">
                            <div class="col-12">
                                <div class="text-center mx-auto wow fadeInUp" data-wow-delay="0.1s" style="max-width: 900px;">
                                    <h4 class="text-primary border-bottom border-primary border-2 d-inline-block pb-2">Entrer en contact</h4>
                                    <p class="mb-5 fs-5 text-dark">Nous sommes là pour vous ! Comment pouvons-nous vous aider ? Nous sommes là pour vous !</p>
                                </div>
                            </div>
                           <!-- Contact Form Start -->
<div class="col-lg-7">
    <h5 class="text-primary wow fadeInUp" data-wow-delay="0.1s">Restons connectés</h5>
    <h1 class="display-5 mb-4 wow fadeInUp" data-wow-delay="0.3s">Envoyez votre message</h1>
    <p class="mb-4 wow fadeInUp" data-wow-delay="0.5s">
        Le formulaire de contact est actuellement inactif. Obtenez un formulaire de contact fonctionnel 
        avec Ajax & PHP en quelques minutes. Copiez et collez simplement les fichiers, ajoutez un peu de code et c’est terminé. 
        <a href="https://htmlcodex.com/contact-form">Télécharger maintenant</a>.
    </p>
    <form>
        <div class="row g-4 wow fadeInUp" data-wow-delay="0.1s">
            <div class="col-lg-12 col-xl-6">
                <div class="form-floating">
                    <input type="text" class="form-control" id="name" placeholder="Votre nom">
                    <label for="name">Votre nom</label>
                </div>
            </div>
            <div class="col-lg-12 col-xl-6">
                <div class="form-floating">
                    <input type="email" class="form-control" id="email" placeholder="Votre e-mail">
                    <label for="email">Votre e-mail</label>
                </div>
            </div>
            <div class="col-lg-12 col-xl-6">
                <div class="form-floating">
                    <input type="tel" class="form-control" id="phone" placeholder="Votre téléphone">
                    <label for="phone">Votre téléphone</label>
                </div>
            </div>
            <div class="col-lg-12 col-xl-6">
                <div class="form-floating">
                    <input type="text" class="form-control" id="project" placeholder="Votre projet">
                    <label for="project">Votre projet</label>
                </div>
            </div>
            <div class="col-12">
                <div class="form-floating">
                    <input type="text" class="form-control" id="subject" placeholder="Sujet">
                    <label for="subject">Sujet</label>
                </div>
            </div>
            <div class="col-12">
                <div class="form-floating">
                    <textarea class="form-control" placeholder="Écrivez votre message ici" id="message" style="height: 160px"></textarea>
                    <label for="message">Message</label>
                </div>
            </div>
            <div class="col-12">
                <button class="btn btn-primary w-100 py-3">Envoyer le message</button>
            </div>
        </div>
    </form>
</div>
<!-- Contact Form End -->

                            <div class="col-lg-5 wow fadeInUp" data-wow-delay="0.2s">
                                <div class="h-100 rounded">
                                    <iframe class="rounded w-100" 
                                    style="height: 100%;" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d387191.33750346623!2d-73.97968099999999!3d40.6974881!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c24fa5d33f083b%3A0xc80b8f06e177fe62!2sNew%20York%2C%20NY%2C%20USA!5e0!3m2!1sen!2sbd!4v1694259649153!5m2!1sen!2sbd" 
                                    loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="row g-4 align-items-center justify-content-center">
                                    <div class="col-md-6 col-lg-6 col-xl-3 wow fadeInUp" data-wow-delay="0.1s">
                                        <div class="rounded p-4">
                                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center mb-4" style="width: 70px; height: 70px;">
                                                <i class="fas fa-map-marker-alt fa-2x text-primary"></i>
                                            </div>
                                            <div>
                                                <h4>Adresse</h4>
                                                <p class="mb-2">123 Rue New York, USA</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-xl-3 wow fadeInUp" data-wow-delay="0.3s">
                                        <div class="rounded p-4">
                                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center mb-4" style="width: 70px; height: 70px;">
                                                <i class="fas fa-envelope fa-2x text-primary"></i>
                                            </div>
                                            <div>
                                                <h4>Envoyez-nous un mail</h4>
                                                <p class="mb-2">info@example.com</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-xl-3 wow fadeInUp" data-wow-delay="0.5s">
                                        <div class="rounded p-4">
                                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center mb-4" style="width: 70px; height: 70px;">
                                                <i class="fa fa-phone-alt fa-2x text-primary"></i>
                                            </div>
                                            <div>
                                                <h4>Téléphone</h4>
                                                <p class="mb-2">(+012) 3456 7890</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-xl-3 wow fadeInUp" data-wow-delay="0.7s">
                                        <div class="rounded p-4">
                                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center mb-4" style="width: 70px; height: 70px;">
                                                <i class="fab fa-firefox-browser fa-2x text-primary"></i>
                                            </div>
                                            <div>
                                                <h4>VotreSite@ex.com</h4>
                                                <p class="mb-2">(+012) 3456 7890</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Contacts End -->
@endsection
