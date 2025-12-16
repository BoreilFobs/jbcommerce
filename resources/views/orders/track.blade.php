@extends('layouts.web')
@section('content')

<!-- Page Header -->
<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6 wow fadeInUp" data-wow-delay="0.1s">Suivre ma Commande</h1>
    <ol class="breadcrumb justify-content-center mb-0 wow fadeInUp" data-wow-delay="0.3s">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Accueil</a></li>
        <li class="breadcrumb-item"><a href="#">Pages</a></li>
        <li class="breadcrumb-item active text-white">Suivi</li>
    </ol>
</div>

<!-- Tracking Section -->
<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Search Form -->
                @if(!isset($order))
                    <div class="bg-light rounded p-5 mb-5 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="text-center mb-4">
                            <i class="fas fa-search fa-3x text-primary mb-3"></i>
                            <h3 class="mb-2">Suivez votre Commande</h3>
                            <p class="text-muted">Entrez votre numéro de commande pour voir son statut</p>
                        </div>
                        
                        <form action="{{ route('orders.track') }}" method="POST">
                            @csrf
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-white">
                                    <i class="fas fa-barcode text-primary"></i>
                                </span>
                                <input type="text" class="form-control" name="order_number" 
                                       placeholder="Ex: ORD-20251020-ABC123" required
                                       value="{{ old('order_number', request('order_number')) }}">
                                <button class="btn btn-primary px-5" type="submit">
                                    <i class="fas fa-search me-2"></i>Rechercher
                                </button>
                            </div>
                            
                            <div class="mt-3 text-center">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Vous trouverez votre numéro de commande dans l'email de confirmation
                                </small>
                            </div>
                        </form>
                        
                        @if(session('error'))
                            <div class="alert alert-danger mt-4 mb-0">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                {{ session('error') }}
                            </div>
                        @endif
                    </div>
                    
                    <!-- Help Section -->
                    <div class="row g-4">
                        <div class="col-md-4 wow fadeInUp" data-wow-delay="0.2s">
                            <div class="bg-light rounded p-4 h-100 text-center">
                                <i class="fas fa-envelope fa-2x text-primary mb-3"></i>
                                <h5 class="mb-2">Email de Confirmation</h5>
                                <p class="text-muted small mb-0">
                                    Votre numéro de commande se trouve dans l'email de confirmation que vous avez reçu
                                </p>
                            </div>
                        </div>
                        
                        <div class="col-md-4 wow fadeInUp" data-wow-delay="0.3s">
                            <div class="bg-light rounded p-4 h-100 text-center">
                                <i class="fas fa-user fa-2x text-primary mb-3"></i>
                                <h5 class="mb-2">Connectez-vous</h5>
                                <p class="text-muted small mb-3">
                                    Accédez à toutes vos commandes depuis votre compte
                                </p>
                                @auth
                                    <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                        Mes Commandes
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                        Se Connecter
                                    </a>
                                @endauth
                            </div>
                        </div>
                        
                        <div class="col-md-4 wow fadeInUp" data-wow-delay="0.4s">
                            <div class="bg-light rounded p-4 h-100 text-center">
                                <i class="fas fa-headset fa-2x text-primary mb-3"></i>
                                <h5 class="mb-2">Besoin d'Aide?</h5>
                                <p class="text-muted small mb-3">
                                    Notre équipe est là pour vous aider
                                </p>
                                <a href="{{ route('contact') }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                    Nous Contacter
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Order Found - Display Tracking -->
                    <div class="wow fadeInUp" data-wow-delay="0.1s">
                        <!-- Order Header -->
                        <div class="bg-light rounded p-4 mb-4">
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <div class="mb-3 mb-md-0">
                                    <h4 class="mb-2">
                                        <i class="fas fa-receipt text-primary me-2"></i>
                                        Commande #{{ $order->order_number }}
                                    </h4>
                                    <p class="text-muted mb-0">
                                        Passée le {{ $order->created_at->format('d/m/Y à H:i') }}
                                    </p>
                                </div>
                                <div class="text-md-end">
                                    <div class="mb-2">
                                        <strong>Statut:</strong>
                                        <div class="mt-1">
                                            {!! $order->status_badge !!}
                                        </div>
                                    </div>
                                    <h5 class="mb-0 text-primary">
                                        {{ number_format($order->total_amount, 0, '.', ',') }} FCFA
                                    </h5>
                                </div>
                            </div>
                        </div>

                        <!-- Tracking Timeline -->
                        <div class="bg-light rounded p-4 mb-4">
                            <h5 class="mb-4">
                                <i class="fas fa-route text-primary me-2"></i>
                                État de Livraison
                            </h5>
                            
                            <div class="tracking-timeline">
                                <div class="tracking-item {{ in_array($order->status, ['pending', 'confirmed', 'processing', 'shipped', 'delivered']) ? 'completed' : 'cancelled' }}">
                                    <div class="tracking-icon">
                                        <i class="fas fa-shopping-cart"></i>
                                    </div>
                                    <div class="tracking-content">
                                        <h6>Commande Passée</h6>
                                        <p class="text-muted small mb-0">{{ $order->created_at->format('d/m/Y à H:i') }}</p>
                                        <p class="text-muted small mb-0">Votre commande a été enregistrée</p>
                                    </div>
                                </div>

                                <div class="tracking-item {{ in_array($order->status, ['confirmed', 'processing', 'shipped', 'delivered']) ? 'completed' : ($order->status === 'pending' ? 'active' : 'cancelled') }}">
                                    <div class="tracking-icon">
                                        <i class="fas fa-clipboard-check"></i>
                                    </div>
                                    <div class="tracking-content">
                                        <h6>Confirmée</h6>
                                        <p class="text-muted small mb-0">
                                            @if($order->status === 'pending')
                                                En attente de confirmation
                                            @else
                                                Commande confirmée et en cours de préparation
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <div class="tracking-item {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'completed' : (in_array($order->status, ['confirmed']) ? 'active' : 'cancelled') }}">
                                    <div class="tracking-icon">
                                        <i class="fas fa-box"></i>
                                    </div>
                                    <div class="tracking-content">
                                        <h6>En Préparation</h6>
                                        <p class="text-muted small mb-0">
                                            @if(in_array($order->status, ['processing', 'shipped', 'delivered']))
                                                Votre commande a été préparée
                                            @else
                                                Nous préparons votre commande
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <div class="tracking-item {{ in_array($order->status, ['shipped', 'delivered']) ? 'completed' : ($order->status === 'processing' ? 'active' : 'cancelled') }}">
                                    <div class="tracking-icon">
                                        <i class="fas fa-box-open"></i>
                                    </div>
                                    <div class="tracking-content">
                                        <h6>Prête pour Retrait/Livraison</h6>
                                        @if($order->shipped_at)
                                            <p class="text-muted small mb-0">{{ $order->shipped_at->format('d/m/Y à H:i') }}</p>
                                        @endif
                                        @if($order->tracking_number)
                                            <p class="text-primary small mb-0">
                                                <i class="fas fa-barcode me-1"></i>
                                                Référence: <strong>{{ $order->tracking_number }}</strong>
                                            </p>
                                        @else
                                            <p class="text-muted small mb-0">
                                                @if($order->status === 'shipped')
                                                    Disponible pour retrait en magasin ou en cours de livraison
                                                @else
                                                    Commande en préparation
                                                @endif
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <div class="tracking-item {{ $order->status === 'delivered' ? 'completed' : ($order->status === 'shipped' ? 'active' : 'cancelled') }}">
                                    <div class="tracking-icon">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="tracking-content">
                                        <h6>Livrée/Retirée</h6>
                                        @if($order->delivered_at)
                                            <p class="text-muted small mb-0">{{ $order->delivered_at->format('d/m/Y à H:i') }}</p>
                                            <p class="text-success small mb-0">
                                                <i class="fas fa-check-circle me-1"></i>
                                                Commande complétée avec succès
                                            </p>
                                        @else
                                            <p class="text-muted small mb-0">
                                                @if($order->status === 'shipped')
                                                    Livraison à Bafoussam: 1-2 jours | Retrait: Disponible maintenant
                                                @else
                                                    En attente
                                                @endif
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                @if($order->status === 'cancelled')
                                    <div class="tracking-item cancelled">
                                        <div class="tracking-icon">
                                            <i class="fas fa-times-circle"></i>
                                        </div>
                                        <div class="tracking-content">
                                            <h6>Annulée</h6>
                                            @if($order->cancelled_at)
                                                <p class="text-muted small mb-0">{{ $order->cancelled_at->format('d/m/Y à H:i') }}</p>
                                            @endif
                                            @if($order->cancelled_reason)
                                                <p class="text-danger small mb-0">{{ $order->cancelled_reason }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Next Steps Alert -->
                        @if($order->status !== 'delivered' && $order->status !== 'cancelled')
                            <div class="alert {{ $order->status === 'pending' ? 'alert-warning' : ($order->status === 'shipped' ? 'alert-success' : 'alert-info') }} mb-4 wow fadeInUp" data-wow-delay="0.3s">
                                <h6 class="alert-heading mb-2">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Prochaines Étapes
                                </h6>
                                @if($order->status === 'pending')
                                    <p class="mb-0 small">
                                        Votre commande est en attente de confirmation. Nous la vérifierons et vous contacterons sous 24h pour confirmer.
                                    </p>
                                @elseif($order->status === 'confirmed')
                                    <p class="mb-0 small">
                                        Votre commande a été confirmée ! Nous la préparons actuellement. Temps estimé : 1-2 jours.
                                    </p>
                                @elseif($order->status === 'processing')
                                    <p class="mb-0 small">
                                        Votre commande est en préparation. Elle sera bientôt prête pour le retrait ou la livraison.
                                    </p>
                                @elseif($order->status === 'shipped')
                                    <p class="mb-2 small">
                                        <strong>Votre commande est prête !</strong>
                                    </p>
                                    <ul class="mb-0 small">
                                        <li><strong>Retrait en magasin :</strong> Disponible maintenant à notre boutique de Bafoussam</li>
                                        <li><strong>Livraison :</strong> Arrivée prévue dans 1-2 jours à Bafoussam</li>
                                    </ul>
                                @endif
                            </div>
                        @endif

                        <!-- Order Items Summary -->
                        <div class="bg-light rounded p-4 mb-4 wow fadeInUp" data-wow-delay="0.4s">
                            <h5 class="mb-3">
                                <i class="fas fa-box text-primary me-2"></i>
                                Articles Commandés
                            </h5>
                            
                            @foreach($order->items as $item)
                                <div class="d-flex align-items-center mb-3 p-3 bg-white rounded">
                                    @if($item->offer)
                                        @php
                                            $images = is_string($item->offer->images) ? json_decode($item->offer->images, true) : $item->offer->images;
                                            $firstImage = $images && is_array($images) && count($images) > 0 
                                                ? '/storage/offer_img/product' . $item->offer->id . "/" . $images[0]
                                                : '/img/default-product.jpg';
                                        @endphp
                                        <img src="{{ asset($firstImage) }}" alt="{{ $item->product_name }}" 
                                             class="rounded me-3 d-none d-md-block" 
                                             style="width: 70px; height: 70px; object-fit: cover;">
                                    @endif
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $item->product_name }}</h6>
                                        <p class="text-muted small mb-0">Quantité: {{ $item->quantity }}</p>
                                    </div>
                                    <div class="text-end">
                                        <p class="mb-0 fw-bold">{{ number_format($item->subtotal, 0, '.', ',') }} F</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Actions -->
                        <div class="text-center">
                            @auth
                                @if(Auth::id() === $order->user_id)
                                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary rounded-pill px-5 py-3 me-2 mb-2">
                                        <i class="fas fa-eye me-2"></i>Voir Détails Complets
                                    </a>
                                @endif
                            @endauth
                            
                            <a href="{{ route('orders.track') }}" class="btn btn-outline-primary rounded-pill px-5 py-3 mb-2">
                                <i class="fas fa-search me-2"></i>Suivre une Autre Commande
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
/* Tracking Timeline */
.tracking-timeline {
    position: relative;
    padding: 20px 0;
}

.tracking-item {
    position: relative;
    padding-left: 80px;
    padding-bottom: 40px;
}

.tracking-item:last-child {
    padding-bottom: 0;
}

.tracking-item::before {
    content: '';
    position: absolute;
    left: 29px;
    top: 50px;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}

.tracking-item:last-child::before {
    display: none;
}

.tracking-icon {
    position: absolute;
    left: 0;
    top: 0;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #e9ecef;
    border: 3px solid #dee2e6;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: #6c757d;
    transition: all 0.3s ease;
}

.tracking-item.completed .tracking-icon {
    background: #28a745;
    border-color: #28a745;
    color: #fff;
}

.tracking-item.completed::before {
    background: #28a745;
}

.tracking-item.active .tracking-icon {
    background: #f28b00;
    border-color: #f28b00;
    color: #fff;
    animation: pulse 2s infinite;
}

.tracking-item.cancelled .tracking-icon {
    background: #dc3545;
    border-color: #dc3545;
    color: #fff;
}

.tracking-content h6 {
    margin-bottom: 5px;
    color: #6c757d;
}

.tracking-item.completed .tracking-content h6,
.tracking-item.active .tracking-content h6 {
    color: #212529;
    font-weight: 600;
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(242, 139, 0, 0.7);
    }
    70% {
        box-shadow: 0 0 0 15px rgba(242, 139, 0, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(242, 139, 0, 0);
    }
}

/* Mobile responsiveness */
@media (max-width: 576px) {
    .tracking-item {
        padding-left: 60px;
    }
    
    .tracking-icon {
        width: 45px;
        height: 45px;
        font-size: 18px;
    }
    
    .tracking-item::before {
        left: 21px;
    }
    
    .tracking-content h6 {
        font-size: 1rem;
    }
}
</style>

@endsection
