@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="row g-0">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 bg-dark text-white min-vh-100">
            @include('layouts.admin-sidebar')
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0"><i class="fas fa-user me-2"></i>Détails de l'utilisateur</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Utilisateurs</a></li>
                            <li class="breadcrumb-item active">{{ $user->name }}</li>
                        </ol>
                    </nav>
                </div>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Retour
                </a>
            </div>

            <div class="row">
                <!-- User Info Card -->
                <div class="col-lg-4 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center">
                            <div class="avatar-circle bg-primary text-white mx-auto mb-3" 
                                 style="width: 100px; height: 100px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 36px; font-weight: bold;">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                            <h4 class="mb-1">{{ $user->name }}</h4>
                            <p class="text-muted mb-3">
                                <i class="fas fa-envelope me-1"></i>{{ $user->email }}
                            </p>
                            @if($user->name === 'admin')
                                <span class="badge bg-danger mb-3">Administrateur</span>
                            @else
                                <span class="badge bg-success mb-3">Utilisateur</span>
                            @endif
                            
                            <hr>
                            
                            <div class="text-start">
                                <p class="mb-2">
                                    <strong><i class="fas fa-calendar-plus me-2 text-primary"></i>Inscrit le :</strong><br>
                                    <span class="text-muted">{{ $user->created_at->format('d/m/Y à H:i') }}</span>
                                </p>
                                <p class="mb-2">
                                    <strong><i class="fas fa-clock me-2 text-primary"></i>Depuis :</strong><br>
                                    <span class="text-muted">{{ $user->created_at->diffForHumans() }}</span>
                                </p>
                                <p class="mb-0">
                                    <strong><i class="fas fa-sync me-2 text-primary"></i>Dernière mise à jour :</strong><br>
                                    <span class="text-muted">{{ $user->updated_at->format('d/m/Y à H:i') }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Activity Cards -->
                <div class="col-lg-8">
                    <!-- Statistics -->
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <div class="card border-0 shadow-sm bg-info text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0">Articles dans le panier</h6>
                                            <h2 class="mb-0 mt-2">{{ $user->cartItems->count() }}</h2>
                                        </div>
                                        <div>
                                            <i class="fas fa-shopping-cart fa-3x opacity-50"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card border-0 shadow-sm bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0">Liste de souhaits</h6>
                                            <h2 class="mb-0 mt-2">{{ $user->wishlistItems->count() }}</h2>
                                        </div>
                                        <div>
                                            <i class="fas fa-heart fa-3x opacity-50"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cart Items -->
                    @if($user->cartItems->count() > 0)
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Panier ({{ $user->cartItems->count() }})</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-sm mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Produit</th>
                                            <th class="text-center">Prix</th>
                                            <th class="text-center">Date d'ajout</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($user->cartItems as $item)
                                            <tr>
                                                <td>
                                                    @if($item->offer)
                                                        <a href="{{ url('/product/details/' . $item->offer->id) }}" target="_blank" class="text-decoration-none">
                                                            {{ $item->offer->name }}
                                                        </a>
                                                    @else
                                                        <span class="text-muted">Produit supprimé</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if($item->offer)
                                                        {{ number_format($item->offer->price, 0, '.', ',') }} FCFA
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td class="text-center text-muted">
                                                    {{ $item->created_at->format('d/m/Y') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Wishlist Items -->
                    @if($user->wishlistItems->count() > 0)
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-warning text-white">
                            <h5 class="mb-0"><i class="fas fa-heart me-2"></i>Liste de souhaits ({{ $user->wishlistItems->count() }})</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-sm mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Produit</th>
                                            <th class="text-center">Prix</th>
                                            <th class="text-center">Date d'ajout</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($user->wishlistItems as $item)
                                            <tr>
                                                <td>
                                                    @if($item->offer)
                                                        <a href="{{ url('/product/details/' . $item->offer->id) }}" target="_blank" class="text-decoration-none">
                                                            {{ $item->offer->name }}
                                                        </a>
                                                    @else
                                                        <span class="text-muted">Produit supprimé</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if($item->offer)
                                                        {{ number_format($item->offer->price, 0, '.', ',') }} FCFA
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td class="text-center text-muted">
                                                    {{ $item->created_at->format('d/m/Y') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($user->cartItems->count() == 0 && $user->wishlistItems->count() == 0)
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucune activité</h5>
                            <p class="text-muted">Cet utilisateur n'a pas encore ajouté de produits.</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
