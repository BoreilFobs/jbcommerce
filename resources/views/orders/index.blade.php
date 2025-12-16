@extends('layouts.web')
@section('content')

<!-- Page Header -->
<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6 wow fadeInUp" data-wow-delay="0.1s">Mes Commandes</h1>
    <ol class="breadcrumb justify-content-center mb-0 wow fadeInUp" data-wow-delay="0.3s">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Accueil</a></li>
        <li class="breadcrumb-item"><a href="#">Pages</a></li>
        <li class="breadcrumb-item active text-white">Commandes</li>
    </ol>
</div>

<!-- Orders Section -->
<div class="container-fluid py-5">
    <div class="container py-5">
        @if($orders->count() > 0)
            <!-- Order Stats (Mobile & Desktop) -->
            <div class="row g-3 mb-5 wow fadeInUp" data-wow-delay="0.1s">
                <div class="col-6 col-md-3">
                    <div class="stat-card bg-white rounded-3 p-3 p-md-4 shadow-sm text-center">
                        <div class="stat-icon bg-primary bg-opacity-10 rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="fas fa-box text-primary fa-lg"></i>
                        </div>
                        <h3 class="mb-0 text-primary">{{ $orders->total() }}</h3>
                        <small class="text-muted">Total</small>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card bg-white rounded-3 p-3 p-md-4 shadow-sm text-center">
                        <div class="stat-icon bg-warning bg-opacity-10 rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="fas fa-clock text-warning fa-lg"></i>
                        </div>
                        <h3 class="mb-0 text-warning">{{ $orders->whereIn('status', ['pending', 'confirmed', 'processing'])->count() }}</h3>
                        <small class="text-muted">En cours</small>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card bg-white rounded-3 p-3 p-md-4 shadow-sm text-center">
                        <div class="stat-icon bg-success bg-opacity-10 rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="fas fa-check-circle text-success fa-lg"></i>
                        </div>
                        <h3 class="mb-0 text-success">{{ $orders->where('status', 'delivered')->count() }}</h3>
                        <small class="text-muted">Livrées</small>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card bg-white rounded-3 p-3 p-md-4 shadow-sm text-center">
                        <div class="stat-icon bg-info bg-opacity-10 rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="fas fa-coins text-info fa-lg"></i>
                        </div>
                        <h3 class="mb-0 text-info" style="font-size: 1.3rem;">{{ number_format($orders->sum('total_amount'), 0, '.', ',') }}</h3>
                        <small class="text-muted">FCFA</small>
                    </div>
                </div>
            </div>

            <!-- Filter Tabs (Mobile Optimized) -->
            <div class="row mb-4 wow fadeInUp" data-wow-delay="0.2s">
                <div class="col-12">
                    <div class="btn-group w-100 order-filter-tabs" role="group">
                        <a href="{{ route('orders.index') }}" 
                           class="btn btn-outline-primary flex-fill {{ !request('status') ? 'active' : '' }}" 
                           style="border-radius: 0.5rem 0 0 0.5rem;">
                            Toutes
                        </a>
                        <a href="{{ route('orders.index', ['status' => 'pending']) }}" 
                           class="btn btn-outline-warning flex-fill {{ request('status') == 'pending' ? 'active' : '' }}">
                            En attente
                        </a>
                        <a href="{{ route('orders.index', ['status' => 'processing']) }}" 
                           class="btn btn-outline-info flex-fill {{ request('status') == 'processing' ? 'active' : '' }}">
                            En cours
                        </a>
                        <a href="{{ route('orders.index', ['status' => 'delivered']) }}" 
                           class="btn btn-outline-success flex-fill {{ request('status') == 'delivered' ? 'active' : '' }}" 
                           style="border-radius: 0 0.5rem 0.5rem 0;">
                            Livrées
                        </a>
                    </div>
                </div>
            </div>

            <!-- Orders List -->
            <div class="row g-4">
                @foreach($orders as $order)
                    <div class="col-12 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="order-card bg-white rounded-3 shadow-sm p-3 p-md-4 border border-light">
                            <!-- Order Header (Mobile & Desktop) -->
                            <div class="row align-items-center mb-3">
                                <div class="col-12 col-md-6 mb-2 mb-md-0">
                                    <h5 class="mb-1 d-flex align-items-center flex-wrap">
                                        <i class="fas fa-receipt text-primary me-2"></i>
                                        <span class="order-number">{{ $order->order_number }}</span>
                                        <span class="ms-2">
                                            {!! $order->status_badge !!}
                                        </span>
                                    </h5>
                                    <p class="text-muted mb-0 small">
                                        <i class="far fa-calendar me-1"></i>
                                        {{ $order->created_at->format('d/m/Y à H:i') }}
                                    </p>
                                </div>
                                <div class="col-12 col-md-6 text-md-end">
                                    <h4 class="text-primary mb-0">
                                        {{ number_format($order->total_amount, 0, '.', ',') }} <small>FCFA</small>
                                    </h4>
                                </div>
                            </div>

                            <!-- Order Items Preview -->
                            <div class="order-items-preview mb-3">
                                <div class="row g-2">
                                    @foreach($order->items->take(4) as $item)
                                        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                                            @if($item->offer)
                                                @php
                                                    $images = is_string($item->offer->images) ? json_decode($item->offer->images, true) : $item->offer->images;
                                                    $firstImage = $images && is_array($images) && count($images) > 0 
                                                        ? '/storage/offer_img/product' . $item->offer->id . "/" . $images[0]
                                                        : '/img/default-product.jpg';
                                                @endphp
                                                <div class="product-thumb position-relative">
                                                    <img src="{{ asset($firstImage) }}" 
                                                         alt="{{ $item->product_name }}" 
                                                         class="img-fluid rounded"
                                                         style="aspect-ratio: 1; object-fit: cover;">
                                                    <span class="position-absolute top-0 end-0 badge bg-dark rounded-pill m-1" style="font-size: 0.7rem;">
                                                        ×{{ $item->quantity }}
                                                    </span>
                                                </div>
                                                <p class="text-truncate small mb-0 mt-1" title="{{ $item->product_name }}">
                                                    {{ $item->product_name }}
                                                </p>
                                            @endif
                                        </div>
                                    @endforeach
                                    @if($order->items->count() > 4)
                                        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                                            <div class="more-items d-flex align-items-center justify-content-center bg-light rounded" style="aspect-ratio: 1;">
                                                <div class="text-center">
                                                    <h4 class="mb-0 text-muted">+{{ $order->items->count() - 4 }}</h4>
                                                    <small class="text-muted">Autres</small>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Order Details & Actions -->
                            <div class="row g-3 align-items-center">
                                <div class="col-12 col-md-6">
                                    <div class="order-meta small">
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <i class="fas fa-box text-muted me-1"></i>
                                                <strong>{{ $order->items->count() }}</strong> article(s)
                                            </div>
                                            <div class="col-6">
                                                <i class="fas fa-credit-card text-muted me-1"></i>
                                                {!! $order->payment_status_badge !!}
                                            </div>
                                            @if($order->tracking_number)
                                                <div class="col-12">
                                                    <i class="fas fa-truck text-muted me-1"></i>
                                                    Suivi: <strong>{{ $order->tracking_number }}</strong>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="d-flex flex-column flex-sm-row gap-2 justify-content-md-end">
                                        <a href="{{ route('orders.show', $order->id) }}" 
                                           class="btn btn-primary rounded-pill px-4 flex-fill flex-sm-grow-0">
                                            <i class="fas fa-eye me-2"></i>
                                            <span class="d-none d-sm-inline">Voir Détails</span>
                                            <span class="d-inline d-sm-none">Détails</span>
                                        </a>
                                        
                                        @if($order->tracking_number || in_array($order->status, ['shipped', 'delivered']))
                                            <a href="{{ route('orders.track') }}?order_number={{ $order->order_number }}" 
                                               class="btn btn-outline-primary rounded-pill px-4 flex-fill flex-sm-grow-0">
                                                <i class="fas fa-map-marker-alt me-2"></i>
                                                <span class="d-none d-sm-inline">Suivre</span>
                                                <span class="d-inline d-sm-none">Suivi</span>
                                            </a>
                                        @endif
                                        
                                        @if($order->status === 'pending' || $order->status === 'confirmed')
                                            <button type="button" 
                                                    class="btn btn-outline-danger rounded-pill px-4 flex-fill flex-sm-grow-0"
                                                    onclick="cancelOrder({{ $order->id }})">
                                                <i class="fas fa-times me-2"></i>
                                                <span class="d-none d-sm-inline">Annuler</span>
                                                <span class="d-inline d-sm-none">Annuler</span>
                                            </button>
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
                <div class="col-lg-6 col-md-8 text-center wow fadeInUp" data-wow-delay="0.1s">
                    <div class="empty-state bg-white rounded-4 p-5 shadow-sm">
                        <div class="empty-icon bg-primary bg-opacity-10 rounded-circle mx-auto mb-4 d-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                            <i class="fas fa-shopping-bag fa-4x text-primary"></i>
                        </div>
                        <h3 class="mb-3">Aucune commande pour le moment</h3>
                        <p class="text-muted mb-4">
                            Vous n'avez pas encore passé de commande. <br>
                            Découvrez nos produits et commencez vos achats dès maintenant!
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
/* Stats Cards */
.stat-card {
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.stat-icon {
    transition: all 0.3s ease;
}

.stat-card:hover .stat-icon {
    transform: scale(1.1) rotate(5deg);
}

/* Filter Tabs */
.order-filter-tabs .btn {
    font-weight: 500;
    padding: 0.75rem 0.5rem;
    font-size: 0.85rem;
}

.order-filter-tabs .btn.active {
    color: #fff !important;
}

.order-filter-tabs .btn-outline-primary.active {
    background-color: var(--bs-primary);
    border-color: var(--bs-primary);
}

.order-filter-tabs .btn-outline-warning.active {
    background-color: var(--bs-warning);
    border-color: var(--bs-warning);
}

.order-filter-tabs .btn-outline-info.active {
    background-color: var(--bs-info);
    border-color: var(--bs-info);
}

.order-filter-tabs .btn-outline-success.active {
    background-color: var(--bs-success);
    border-color: var(--bs-success);
}

/* Order Cards */
.order-card {
    transition: all 0.3s ease;
}

.order-card:hover {
    border-color: #f28b00 !important;
    box-shadow: 0 0.5rem 1.5rem rgba(242, 139, 0, 0.15) !important;
    transform: translateY(-3px);
}

.order-number {
    font-family: 'Courier New', monospace;
    font-weight: bold;
    font-size: 1.1rem;
}

.product-thumb img {
    transition: all 0.3s ease;
}

.product-thumb:hover img {
    transform: scale(1.05);
}

/* Empty State */
.empty-state {
    animation: fadeInUp 0.6s ease;
}

.empty-icon {
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

/* Mobile responsiveness */
@media (max-width: 768px) {
    .stat-card h3 {
        font-size: 1.5rem;
    }
    
    .stat-card small {
        font-size: 0.75rem;
    }
    
    .order-card {
        font-size: 0.9rem;
    }
    
    .order-number {
        font-size: 0.95rem;
    }
    
    .order-filter-tabs .btn {
        font-size: 0.75rem;
        padding: 0.5rem 0.25rem;
    }
}

@media (max-width: 576px) {
    .stat-icon {
        width: 40px !important;
        height: 40px !important;
    }
    
    .stat-icon i {
        font-size: 1rem !important;
    }
    
    .order-card h5 {
        font-size: 1rem;
    }
    
    .order-card h4 {
        font-size: 1.25rem;
    }
    
    .product-thumb p {
        font-size: 0.7rem;
    }
}

/* Badge Styling */
.badge {
    font-weight: 500;
}

/* Button Hover Effects */
.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}
</style>

<script>
function cancelOrder(orderId) {
    if (confirm('Êtes-vous sûr de vouloir annuler cette commande?')) {
        fetch(`/orders/${orderId}/cancel`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Commande annulée avec succès');
                location.reload();
            } else {
                alert('Erreur lors de l\'annulation de la commande');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Une erreur est survenue');
        });
    }
}

// Smooth scroll animation
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.order-card');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });
});
</script>

@endsection
