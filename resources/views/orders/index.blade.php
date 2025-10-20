@extends('layouts.web')
@section('content')

<!-- Page Header -->
<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6 wow fadeInUp" data-wow-delay="0.1s">Mes Commandes</h1>
    <ol class="breadcrumb justify-content-center mb-0 wow fadeInUp" data-wow-delay="0.3s">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
        <li class="breadcrumb-item active text-white">Mes Commandes</li>
    </ol>
</div>

<!-- Orders Section -->
<div class="container-fluid py-5">
    <div class="container py-5">
        @if($orders->count() > 0)
            <!-- Orders List -->
            <div class="row g-4">
                @foreach($orders as $order)
                    <div class="col-12 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="order-card bg-light rounded p-4">
                            <div class="row align-items-center">
                                <!-- Order Info (Mobile & Desktop) -->
                                <div class="col-lg-8">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <h5 class="mb-2">
                                                <i class="fas fa-receipt text-primary me-2"></i>
                                                {{ $order->order_number }}
                                            </h5>
                                            <p class="text-muted mb-1">
                                                <i class="fas fa-calendar me-2"></i>
                                                {{ $order->created_at->format('d/m/Y à H:i') }}
                                            </p>
                                            <p class="text-muted mb-0">
                                                <i class="fas fa-box me-2"></i>
                                                {{ $order->items->count() }} {{ $order->items->count() > 1 ? 'articles' : 'article' }}
                                            </p>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="mb-2">
                                                <strong>Statut:</strong>
                                                <div class="mt-1">
                                                    {!! $order->status_badge !!}
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <strong>Paiement:</strong>
                                                <div class="mt-1">
                                                    {!! $order->payment_status_badge !!}
                                                </div>
                                            </div>
                                            <div>
                                                <strong>Méthode:</strong>
                                                <p class="mb-0 small text-muted">{{ $order->payment_method_name }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Order Items Preview (Desktop Only) -->
                                    <div class="d-none d-lg-block mt-3">
                                        <div class="d-flex align-items-center gap-2">
                                            @foreach($order->items->take(3) as $item)
                                                @if($item->offer)
                                                    @php
                                                        $images = is_string($item->offer->images) ? json_decode($item->offer->images, true) : $item->offer->images;
                                                        $firstImage = $images && is_array($images) && count($images) > 0 
                                                            ? '/storage/offer_img/product' . $item->offer->id . "/" . $images[0]
                                                            : '/img/default-product.jpg';
                                                    @endphp
                                                    <img src="{{ asset($firstImage) }}" alt="{{ $item->product_name }}" 
                                                         class="rounded" 
                                                         style="width: 50px; height: 50px; object-fit: cover;"
                                                         title="{{ $item->product_name }}">
                                                @endif
                                            @endforeach
                                            @if($order->items->count() > 3)
                                                <span class="badge bg-secondary">+{{ $order->items->count() - 3 }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Total & Actions -->
                                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                                    <h4 class="text-primary mb-3">
                                        {{ number_format($order->total_amount, 0, '.', ',') }} FCFA
                                    </h4>
                                    
                                    <div class="d-flex flex-column flex-lg-row gap-2 justify-content-lg-end">
                                        <a href="{{ route('orders.show', $order->id) }}" 
                                           class="btn btn-primary rounded-pill px-4">
                                            <i class="fas fa-eye me-2"></i>Détails
                                        </a>
                                        
                                        @if($order->tracking_number)
                                            <a href="{{ route('orders.track') }}?order_number={{ $order->order_number }}" 
                                               class="btn btn-outline-primary rounded-pill px-4">
                                                <i class="fas fa-truck me-2"></i>Suivre
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            @if($orders->hasPages())
                <div class="row mt-5">
                    <div class="col-12">
                        <div class="d-flex justify-content-center">
                            {{ $orders->links() }}
                        </div>
                    </div>
                </div>
            @endif
            
        @else
            <!-- Empty State -->
            <div class="row justify-content-center">
                <div class="col-lg-6 text-center wow fadeInUp" data-wow-delay="0.1s">
                    <div class="bg-light rounded p-5">
                        <i class="fas fa-shopping-bag fa-5x text-muted mb-4"></i>
                        <h3 class="mb-3">Aucune commande</h3>
                        <p class="text-muted mb-4">
                            Vous n'avez pas encore passé de commande. Commencez vos achats maintenant!
                        </p>
                        <a href="{{ route('shop') }}" class="btn btn-primary rounded-pill px-5 py-3">
                            <i class="fas fa-shopping-cart me-2"></i>
                            Commencer vos Achats
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
.order-card {
    transition: all 0.3s ease;
    border: 1px solid transparent;
}

.order-card:hover {
    border-color: #f28b00;
    box-shadow: 0 4px 15px rgba(242, 139, 0, 0.1);
    transform: translateY(-2px);
}

/* Mobile responsiveness */
@media (max-width: 991px) {
    .order-card {
        font-size: 0.9rem;
    }
    
    .order-card h5 {
        font-size: 1.1rem;
    }
}

@media (max-width: 576px) {
    .order-card .btn {
        font-size: 0.875rem;
        padding: 0.5rem 1rem;
    }
}
</style>

@endsection
