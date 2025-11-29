@extends('layouts.web')
@section('content')
    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6 wow fadeInUp" data-wow-delay="0.1s">Liste de Souhaits</h1>
        <ol class="breadcrumb justify-content-center mb-0 wow fadeInUp" data-wow-delay="0.3s">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
            <li class="breadcrumb-item active text-white">Liste de Souhaits</li>
        </ol>
    </div>
    <div class="container-fluid py-5">
        <div class="container py-5">
            {{--
                La classe table-responsive est cruciale pour le mobile.
                Elle permet à la table de défiler horizontalement si elle dépasse la largeur de l'écran,
                ce qui est la méthode standard pour les grandes tables sur petit écran.
            --}}
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            {{-- On donne plus de largeur à la colonne Produit --}}
                            <th scope="col" style="width: 40%;">Produit</th>
                            <th scope="col" style="width: 20%;">Prix</th>
                            <th scope="col" style="width: 20%;">Stock</th>
                            <th scope="col" style="width: 20%;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($wishes as $wish)
                            @php
                                // Récupérer les données de l'offre
                                $offer = App\Models\offers::findOrFail($wish->offer_id);
                                $quantity = $offer->quantity; // Utiliser la quantité pour la vérification du stock
                                $isAvailable = $quantity > 0;
                            @endphp
                            <tr data-wish-id="{{ $wish->id }}">
                                <th scope="row" class="py-3">
                                    <div class="d-flex align-items-center">
                                        {{-- Image du Produit: Réduction de la taille de l'image pour le mobile --}}
                                        {{-- <img src="{{ $offer->image_path }}" class="img-fluid me-3 rounded" style="width: 60px; height: 60px; object-fit: cover;" alt="{{ $offer->name }}"> --}}
                                        {{-- Nom du Produit --}}
                                        <p class="mb-0 fw-bold">{{ $offer->name }}</p>
                                    </div>
                                </th>
                                <td class="py-3">
                                    <p class="mb-0">{{ number_format($offer->price, 0, '.', ',') }}FCFA</p>
                                </td>
                                <td class="py-3">
                                    <p class="mb-0">
                                        @if ($isAvailable)
                                            <span class="text-success fw-bold">{{ $quantity }} </span>
                                        @else
                                            <span class="text-danger fw-bold">Rupture</span>
                                        @endif
                                    </p>
                                </td>
                                <td class="py-3">
                                    <div class="d-flex flex-column align-items-start">
                                        {{-- Bouton Ajouter au Panier --}}
                                        @if ($isAvailable)
                                            <a href="{{ route('cart.add', $wish->offer_id) }}"
                                                class="btn btn-sm btn-primary rounded-pill px-3 py-2 mb-2 w-100">
                                                <i class="fa fa-shopping-bag me-1"></i> Panier
                                            </a>
                                        @else
                                            <button class="btn btn-sm btn-secondary rounded-pill px-3 py-2 mb-2 w-100" disabled>
                                                <i class="fa fa-ban me-1"></i> Indisponible
                                            </button>
                                        @endif

                                        {{-- Bouton Supprimer --}}
                                        <a href="{{ route('wishlist.delete', $wish->id) }}"
                                            class="btn btn-sm rounded-pill bg-light border w-100" title="Supprimer">
                                            <i class="fa fa-times text-danger"></i> Supprimer
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Bloc Récapitulatif --}}
            <div class="row g-4 justify-content-end mt-4">
                <div class="col-sm-12 col-md-7 col-lg-6 col-xl-4">
                    <div class="bg-light rounded p-4">
                        <h1 class="display-6 mb-4">Total <span class="fw-normal">des Articles</span></h1>
                        <div class="d-flex justify-content-between mb-4">
                            <h5 class="mb-0 me-4">Nombre d'articles :</h5>
                            <p class="mb-0">{{ count($wishes) }}</p>
                        </div>
                        <p class="mb-0 text-end">Votre liste de souhaits.</p>
                        <div class="py-4 mt-4 border-top border-bottom d-flex justify-content-center">
                            <a href="{{ route('home') }}" class="btn btn-primary rounded-pill px-4 py-3 text-uppercase w-100">Continuer vos achats</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection