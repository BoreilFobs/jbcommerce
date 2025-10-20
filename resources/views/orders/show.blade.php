@extends('layouts.web')
@section('content')

<!-- Page Header -->
<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6 wow fadeInUp" data-wow-delay="0.1s">Détails de la Commande</h1>
    <ol class="breadcrumb justify-content-center mb-0 wow fadeInUp" data-wow-delay="0.3s">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
        <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">Mes Commandes</a></li>
        <li class="breadcrumb-item active text-white">{{ $order->order_number }}</li>
    </ol>
</div>

<!-- Order Details Section -->
<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="row g-4">
            <!-- Left Column - Order Information -->
            <div class="col-lg-8">
                <!-- Order Header -->
                <div class="bg-light rounded p-4 mb-4 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="row align-items-center">
                        <div class="col-md-8 mb-3 mb-md-0">
                            <h4 class="mb-2">
                                <i class="fas fa-receipt text-primary me-2"></i>
                                Commande #{{ $order->order_number }}
                            </h4>
                            <p class="text-muted mb-0">
                                <i class="fas fa-calendar me-2"></i>
                                Passée le {{ $order->created_at->format('d/m/Y à H:i') }}
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <div class="mb-2">
                                <strong>Statut:</strong>
                                <div class="mt-1">
                                    {!! $order->status_badge !!}
                                </div>
                            </div>
                            <div>
                                <strong>Paiement:</strong>
                                <div class="mt-1">
                                    {!! $order->payment_status_badge !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Timeline -->
                @if(in_array($order->status, ['shipped', 'delivered']))
                    <div class="bg-light rounded p-4 mb-4 wow fadeInUp" data-wow-delay="0.2s">
                        <h5 class="mb-4">
                            <i class="fas fa-truck text-primary me-2"></i>
                            Suivi de Livraison
                        </h5>
                        
                        <div class="timeline">
                            <div class="timeline-item {{ in_array($order->status, ['pending', 'confirmed', 'processing', 'shipped', 'delivered']) ? 'active' : '' }}">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <h6>Commande Passée</h6>
                                    <p class="text-muted small mb-0">{{ $order->created_at->format('d/m/Y à H:i') }}</p>
                                </div>
                            </div>
                            
                            <div class="timeline-item {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'active' : '' }}">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <h6>En Traitement</h6>
                                    <p class="text-muted small mb-0">Préparation de votre commande</p>
                                </div>
                            </div>
                            
                            <div class="timeline-item {{ in_array($order->status, ['shipped', 'delivered']) ? 'active' : '' }}">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <h6>Expédiée</h6>
                                    @if($order->shipped_at)
                                        <p class="text-muted small mb-0">{{ $order->shipped_at->format('d/m/Y à H:i') }}</p>
                                    @endif
                                    @if($order->tracking_number)
                                        <p class="text-primary small mb-0">
                                            <i class="fas fa-barcode me-1"></i>
                                            Tracking: <strong>{{ $order->tracking_number }}</strong>
                                        </p>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="timeline-item {{ $order->status === 'delivered' ? 'active' : '' }}">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <h6>Livrée</h6>
                                    @if($order->delivered_at)
                                        <p class="text-muted small mb-0">{{ $order->delivered_at->format('d/m/Y à H:i') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Order Items -->
                <div class="bg-light rounded p-4 mb-4 wow fadeInUp" data-wow-delay="0.3s">
                    <h5 class="mb-4">
                        <i class="fas fa-box text-primary me-2"></i>
                        Articles Commandés ({{ $order->items->count() }})
                    </h5>
                    
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <thead class="border-bottom">
                                <tr>
                                    <th>Produit</th>
                                    <th class="text-center">Quantité</th>
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
                                                         style="width: 60px; height: 60px; object-fit: cover;">
                                                @endif
                                                <div>
                                                    <h6 class="mb-0">{{ $item->product_name }}</h6>
                                                    @if($item->discount_percentage > 0)
                                                        <small class="badge bg-danger">-{{ $item->discount_percentage }}%</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center align-middle">
                                            <span class="badge bg-primary">{{ $item->quantity }}</span>
                                        </td>
                                        <td class="text-end align-middle">
                                            {{ number_format($item->unit_price, 0, '.', ',') }} F
                                        </td>
                                        <td class="text-end align-middle">
                                            <strong>{{ number_format($item->subtotal, 0, '.', ',') }} F</strong>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Customer Notes -->
                @if($order->customer_notes)
                    <div class="bg-light rounded p-4 wow fadeInUp" data-wow-delay="0.4s">
                        <h5 class="mb-3">
                            <i class="fas fa-comment-dots text-primary me-2"></i>
                            Vos Notes
                        </h5>
                        <p class="mb-0 text-muted">{{ $order->customer_notes }}</p>
                    </div>
                @endif
            </div>

            <!-- Right Column - Summary & Actions -->
            <div class="col-lg-4">
                <!-- Order Summary -->
                <div class="bg-light rounded p-4 mb-4 sticky-top wow fadeInUp" data-wow-delay="0.2s" style="top: 100px;">
                    <h5 class="mb-4">
                        <i class="fas fa-file-invoice-dollar text-primary me-2"></i>
                        Résumé
                    </h5>
                    
                    <div class="border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Sous-total:</span>
                            <span>{{ number_format($order->subtotal, 0, '.', ',') }} F</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Livraison:</span>
                            <span>{{ number_format($order->shipping_cost, 0, '.', ',') }} F</span>
                        </div>
                        @if($order->discount_amount > 0)
                            <div class="d-flex justify-content-between mb-2 text-success">
                                <span>Réduction:</span>
                                <span>-{{ number_format($order->discount_amount, 0, '.', ',') }} F</span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="d-flex justify-content-between mb-4">
                        <h5 class="mb-0">Total:</h5>
                        <h5 class="mb-0 text-primary">{{ number_format($order->total_amount, 0, '.', ',') }} FCFA</h5>
                    </div>
                    
                    <!-- Payment Method -->
                    <div class="border-top pt-3 mb-4">
                        <h6 class="mb-2">Méthode de Paiement</h6>
                        <p class="mb-0 text-muted">
                            <i class="fas fa-credit-card me-2"></i>
                            {{ $order->payment_method_name }}
                        </p>
                        @if($order->payment_phone)
                            <p class="mb-0 text-muted small">{{ $order->payment_phone }}</p>
                        @endif
                    </div>
                    
                    <!-- Actions -->
                    <div class="d-grid gap-2">
                        @if($order->canBeCancelled())
                            <button type="button" class="btn btn-outline-danger rounded-pill" 
                                    data-bs-toggle="modal" data-bs-target="#cancelModal">
                                <i class="fas fa-times-circle me-2"></i>Annuler la Commande
                            </button>
                        @endif
                        
                        @if($order->tracking_number)
                            <a href="{{ route('orders.track') }}?order_number={{ $order->order_number }}" 
                               class="btn btn-outline-primary rounded-pill">
                                <i class="fas fa-truck me-2"></i>Suivre la Livraison
                            </a>
                        @endif
                        
                        <a href="{{ route('contact') }}" class="btn btn-outline-secondary rounded-pill">
                            <i class="fas fa-headset me-2"></i>Contacter le Support
                        </a>
                    </div>
                </div>

                <!-- Shipping Information -->
                <div class="bg-light rounded p-4 wow fadeInUp" data-wow-delay="0.3s">
                    <h5 class="mb-3">
                        <i class="fas fa-shipping-fast text-primary me-2"></i>
                        Informations de Livraison
                    </h5>
                    
                    <p class="mb-2"><strong>{{ $order->shipping_name }}</strong></p>
                    <p class="mb-2 text-muted">
                        <i class="fas fa-phone me-2"></i>{{ $order->shipping_phone }}
                    </p>
                    <p class="mb-2 text-muted">
                        <i class="fas fa-envelope me-2"></i>{{ $order->shipping_email }}
                    </p>
                    <p class="mb-0 text-muted">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        {{ $order->shipping_address }}<br>
                        <span class="ms-4">{{ $order->shipping_city }}, {{ $order->shipping_region }}</span>
                        @if($order->shipping_postal_code)
                            <br><span class="ms-4">{{ $order->shipping_postal_code }}</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Order Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelModalLabel">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                    Annuler la Commande
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('orders.cancel', $order->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="mb-3">Êtes-vous sûr de vouloir annuler cette commande?</p>
                    
                    <div class="mb-3">
                        <label for="reason" class="form-label">Raison de l'annulation (optionnel)</label>
                        <textarea class="form-control" id="reason" name="reason" rows="3" 
                                  placeholder="Pourquoi annulez-vous cette commande?"></textarea>
                    </div>
                    
                    <div class="alert alert-warning mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Note:</strong> Les produits seront remis en stock après l'annulation.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times-circle me-2"></i>Annuler la Commande
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Timeline Styles */
.timeline {
    position: relative;
    padding-left: 40px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 10px;
    bottom: 10px;
    width: 2px;
    background: #dee2e6;
}

.timeline-item {
    position: relative;
    padding-bottom: 30px;
}

.timeline-item:last-child {
    padding-bottom: 0;
}

.timeline-marker {
    position: absolute;
    left: -29px;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #dee2e6;
    border: 3px solid #fff;
    box-shadow: 0 0 0 2px #dee2e6;
}

.timeline-item.active .timeline-marker {
    background: #f28b00;
    box-shadow: 0 0 0 2px #f28b00;
}

.timeline-content h6 {
    margin-bottom: 5px;
    color: #6c757d;
}

.timeline-item.active .timeline-content h6 {
    color: #f28b00;
    font-weight: 600;
}

/* Sticky sidebar */
@media (max-width: 991px) {
    .sticky-top {
        position: relative !important;
        top: 0 !important;
    }
}

/* Mobile responsiveness */
@media (max-width: 576px) {
    .table {
        font-size: 0.875rem;
    }
    
    .timeline {
        padding-left: 30px;
    }
    
    .timeline-marker {
        left: -24px;
        width: 24px;
        height: 24px;
    }
}
</style>

@endsection
