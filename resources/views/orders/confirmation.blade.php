@extends('layouts.web')
@section('content')

<!-- Page Header -->
<div class="container-fluid page-header py-5" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
    <h1 class="text-center text-white display-6 wow fadeInUp" data-wow-delay="0.1s">
        <i class="fas fa-check-circle me-3"></i>Commande Confirmée
    </h1>
    <ol class="breadcrumb justify-content-center mb-0 wow fadeInUp" data-wow-delay="0.3s">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">Accueil</a></li>
        <li class="breadcrumb-item"><a href="{{ route('orders.index') }}" class="text-white">Mes Commandes</a></li>
        <li class="breadcrumb-item active text-white">Confirmation</li>
    </ol>
</div>

<!-- Confirmation Section -->
<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Success Message -->
                <div class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="success-icon mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 100px;"></i>
                    </div>
                    <h2 class="text-success mb-3">Merci pour votre commande!</h2>
                    <p class="lead text-muted">
                        Votre commande a été passée avec succès. Vous recevrez un email de confirmation sous peu.
                    </p>
                </div>

                <!-- Order Details Card -->
                <div class="bg-light rounded p-4 mb-4 wow fadeInUp" data-wow-delay="0.2s">
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <h5 class="mb-3">
                                <i class="fas fa-receipt text-primary me-2"></i>
                                Détails de la Commande
                            </h5>
                            <p class="mb-2">
                                <strong>Numéro de Commande:</strong><br>
                                <span class="text-primary fs-5">{{ $order->order_number }}</span>
                            </p>
                            <p class="mb-2">
                                <strong>Date:</strong><br>
                                {{ $order->created_at->format('d/m/Y à H:i') }}
                            </p>
                            <p class="mb-0">
                                <strong>Montant Total:</strong><br>
                                <span class="text-success fs-4">{{ number_format($order->total_amount, 0, '.', ',') }} FCFA</span>
                            </p>
                        </div>
                        
                        <div class="col-md-6">
                            <h5 class="mb-3">
                                <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                Adresse de Livraison/Retrait
                            </h5>
                            <p class="mb-2">
                                <strong>{{ $order->shipping_name }}</strong>
                            </p>
                            <p class="mb-2">
                                <i class="fas fa-phone me-2"></i>{{ $order->shipping_phone }}
                            </p>
                            <p class="mb-2">
                                <i class="fas fa-envelope me-2"></i>{{ $order->shipping_email }}
                            </p>
                            <p class="mb-0">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                {{ $order->shipping_address }}, {{ $order->shipping_city }}, {{ $order->shipping_region }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Payment Info -->
                <div class="bg-light rounded p-4 mb-4 wow fadeInUp" data-wow-delay="0.3s">
                    <h5 class="mb-3">
                        <i class="fas fa-credit-card text-primary me-2"></i>
                        Méthode de Paiement
                    </h5>
                    
                    <div class="d-flex align-items-center mb-3">
                        @if($order->payment_method === 'cash_on_delivery')
                            <i class="fas fa-money-bill-wave fa-2x text-success me-3"></i>
                            <div>
                                <p class="mb-0"><strong>Paiement à la Livraison</strong></p>
                                <small class="text-muted">Vous paierez en espèces lors de la livraison</small>
                            </div>
                        @elseif($order->payment_method === 'mobile_money_mtn')
                            <i class="fas fa-mobile-alt fa-2x text-warning me-3"></i>
                            <div>
                                <p class="mb-0"><strong>MTN Mobile Money</strong></p>
                                <small class="text-muted">Numéro: {{ $order->payment_phone }}</small>
                            </div>
                        @elseif($order->payment_method === 'mobile_money_orange')
                            <i class="fas fa-mobile-alt fa-2x text-danger me-3"></i>
                            <div>
                                <p class="mb-0"><strong>Orange Money</strong></p>
                                <small class="text-muted">Numéro: {{ $order->payment_phone }}</small>
                            </div>
                        @elseif($order->payment_method === 'bank_transfer')
                            <i class="fas fa-university fa-2x text-info me-3"></i>
                            <div>
                                <p class="mb-0"><strong>Virement Bancaire</strong></p>
                                <small class="text-muted">Coordonnées bancaires envoyées par email</small>
                            </div>
                        @endif
                    </div>
                    
                    @if(in_array($order->payment_method, ['mobile_money_mtn', 'mobile_money_orange']))
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Instructions de Paiement:</strong>
                            <ol class="mb-0 mt-2 ps-3">
                                <li>Vous recevrez une demande de paiement sur votre téléphone</li>
                                <li>Entrez votre code PIN pour confirmer le paiement</li>
                                <li>Votre commande sera traitée dès réception du paiement</li>
                            </ol>
                        </div>
                    @endif
                </div>

                <!-- Order Items -->
                <div class="bg-light rounded p-4 mb-4 wow fadeInUp" data-wow-delay="0.4s">
                    <h5 class="mb-3">
                        <i class="fas fa-box text-primary me-2"></i>
                        Articles Commandés ({{ $order->items->count() }})
                    </h5>
                    
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <thead class="border-bottom">
                                <tr>
                                    <th>Produit</th>
                                    <th class="text-center">Qté</th>
                                    <th class="text-end">Prix Unit.</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr class="border-bottom">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($item->offer)
                                                    @php
                                                        $images = is_string($item->offer->images) ? json_decode($item->offer->images, true) : $item->offer->images;
                                                        $firstImage = $images && is_array($images) && count($images) > 0 
                                                            ? '/storage/offer_img/product' . $item->offer->id . "/" . $images[0]
                                                            : '/img/default-product.jpg';
                                                    @endphp
                                                    <img src="{{ asset($firstImage) }}" alt="{{ $item->product_name }}" 
                                                         class="rounded me-3 d-none d-md-block" 
                                                         style="width: 50px; height: 50px; object-fit: cover;">
                                                @endif
                                                <span>{{ $item->product_name }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">{{ number_format($item->unit_price, 0, '.', ',') }} F</td>
                                        <td class="text-end fw-bold">{{ number_format($item->subtotal, 0, '.', ',') }} F</td>
                                    </tr>
                                @endforeach
                                
                                <tr class="border-0">
                                    <td colspan="3" class="text-end"><strong>Sous-total:</strong></td>
                                    <td class="text-end">{{ number_format($order->subtotal, 0, '.', ',') }} FCFA</td>
                                </tr>
                                <tr class="border-0">
                                    <td colspan="3" class="text-end"><strong>Livraison:</strong></td>
                                    <td class="text-end">{{ number_format($order->shipping_cost, 0, '.', ',') }} FCFA</td>
                                </tr>
                                @if($order->discount_amount > 0)
                                    <tr class="border-0">
                                        <td colspan="3" class="text-end"><strong>Réduction:</strong></td>
                                        <td class="text-end text-success">-{{ number_format($order->discount_amount, 0, '.', ',') }} FCFA</td>
                                    </tr>
                                @endif
                                <tr class="border-top">
                                    <td colspan="3" class="text-end"><h5 class="mb-0">Total:</h5></td>
                                    <td class="text-end"><h5 class="mb-0 text-primary">{{ number_format($order->total_amount, 0, '.', ',') }} FCFA</h5></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- What's Next -->
                <div class="bg-light rounded p-4 mb-4 wow fadeInUp" data-wow-delay="0.5s">
                    <h5 class="mb-3">
                        <i class="fas fa-question-circle text-primary me-2"></i>
                        Quelle est la suite?
                    </h5>
                    
                    <div class="timeline-steps">
                        <div class="d-flex align-items-start mb-3">
                            <div class="step-icon bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                 style="width: 40px; height: 40px; min-width: 40px;">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">1. Confirmation de Commande</h6>
                                <p class="text-muted mb-0 small">Un email de confirmation vous a été envoyé avec tous les détails.</p>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-start mb-3">
                            <div class="step-icon bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                 style="width: 40px; height: 40px; min-width: 40px;">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">2. Traitement</h6>
                                <p class="text-muted mb-0 small">Nous préparons votre commande (1-2 jours ouvrables).</p>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-start mb-3">
                            <div class="step-icon bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                 style="width: 40px; height: 40px; min-width: 40px;">
                                <i class="fas fa-box-open"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">3. Préparation</h6>
                                <p class="text-muted mb-0 small">Votre commande est emballée et prête.</p>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-start">
                            <div class="step-icon bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                 style="width: 40px; height: 40px; min-width: 40px;">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">4. Livraison/Retrait</h6>
                                <p class="text-muted mb-0 small">Livraison à Bafoussam (1-2 jours) ou retrait en magasin disponible.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="text-center wow fadeInUp" data-wow-delay="0.6s">
                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary rounded-pill px-5 py-3 me-2 mb-2">
                        <i class="fas fa-eye me-2"></i>Voir la Commande
                    </a>
                    <a href="{{ route('shop') }}" class="btn btn-outline-primary rounded-pill px-5 py-3 mb-2">
                        <i class="fas fa-shopping-bag me-2"></i>Continuer vos Achats
                    </a>
                </div>

                <!-- Contact Support -->
                <div class="text-center mt-4 wow fadeInUp" data-wow-delay="0.7s">
                    <p class="text-muted mb-2">
                        <i class="fas fa-headset me-2"></i>
                        Besoin d'aide? <a href="{{ route('contact') }}" class="text-primary">Contactez-nous</a>
                    </p>
                    <p class="text-muted small">
                        <i class="fas fa-phone me-2"></i>+237 657 528 859 / +237 693 662 715
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.success-icon {
    animation: scaleIn 0.5s ease-out;
}

@keyframes scaleIn {
    0% {
        transform: scale(0);
        opacity: 0;
    }
    50% {
        transform: scale(1.1);
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

.step-icon {
    flex-shrink: 0;
}

@media (max-width: 576px) {
    .table-responsive table {
        font-size: 0.9rem;
    }
    
    .success-icon i {
        font-size: 70px !important;
    }
}
</style>

@endsection
