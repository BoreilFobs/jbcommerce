@extends('layouts.web')

@section('content')
<div class="container-fluid py-5 bg-light">
    <div class="container py-5">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Accueil</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Mon Profil</li>
                    </ol>
                </nav>
                <h1 class="display-5 fw-bold text-primary mb-2">Mon Profil</h1>
                <p class="text-muted">Gérez vos informations personnelles et votre mot de passe</p>
            </div>
        </div>

        <!-- Success Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            <!-- Profile Information Card -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-user me-2"></i>Informations Personnelles
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf
                            @method('PATCH')

                            <!-- Name -->
                            <div class="mb-4">
                                <label for="name" class="form-label fw-semibold">
                                    <i class="fas fa-user text-primary me-2"></i>Nom Complet
                                </label>
                                <input type="text" 
                                       class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $user->name) }}" 
                                       required
                                       placeholder="Entrez votre nom complet">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div class="mb-4">
                                <label for="phone" class="form-label fw-semibold">
                                    <i class="fas fa-phone text-primary me-2"></i>Numéro de Téléphone
                                </label>
                                <input type="tel" 
                                       class="form-control form-control-lg @error('phone') is-invalid @enderror" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone', $user->phone) }}" 
                                       required
                                       placeholder="Ex: +237 6XX XXX XXX">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>Ce numéro est utilisé pour vous identifier
                                </div>
                            </div>

                            <!-- Account Info (Read-only) -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-calendar text-primary me-2"></i>Membre Depuis
                                </label>
                                <input type="text" 
                                       class="form-control form-control-lg" 
                                       value="{{ $user->created_at->format('d/m/Y') }}" 
                                       readonly
                                       disabled>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save me-2"></i>Enregistrer les Modifications
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Password Update Card -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-warning text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-lock me-2"></i>Changer le Mot de Passe
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('profile.password.update') }}">
                            @csrf
                            @method('PUT')

                            <!-- Current Password -->
                            <div class="mb-4">
                                <label for="current_password" class="form-label fw-semibold">
                                    <i class="fas fa-key text-warning me-2"></i>Mot de Passe Actuel
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control form-control-lg @error('current_password') is-invalid @enderror" 
                                           id="current_password" 
                                           name="current_password" 
                                           required
                                           placeholder="Entrez votre mot de passe actuel">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('current_password')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('current_password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- New Password -->
                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold">
                                    <i class="fas fa-lock text-warning me-2"></i>Nouveau Mot de Passe
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           required
                                           placeholder="Minimum 8 caractères">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <i class="fas fa-shield-alt me-1"></i>Utilisez au moins 8 caractères avec lettres et chiffres
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label fw-semibold">
                                    <i class="fas fa-check-circle text-warning me-2"></i>Confirmer le Mot de Passe
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control form-control-lg" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           required
                                           placeholder="Confirmez le nouveau mot de passe">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-warning btn-lg text-white">
                                    <i class="fas fa-sync-alt me-2"></i>Mettre à Jour le Mot de Passe
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Info Cards -->
        <div class="row g-4 mt-2">
            <!-- Order Summary -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="fas fa-shopping-bag fa-3x text-primary"></i>
                        </div>
                        <h3 class="fw-bold text-primary">{{ $user->orders()->count() }}</h3>
                        <p class="text-muted mb-3">Commandes Passées</p>
                        <a href="{{ route('orders.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-eye me-1"></i>Voir Mes Commandes
                        </a>
                    </div>
                </div>
            </div>

            <!-- Wishlist Summary -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="fas fa-heart fa-3x text-danger"></i>
                        </div>
                        <h3 class="fw-bold text-danger">{{ $user->wishlistItems()->count() }}</h3>
                        <p class="text-muted mb-3">Articles Favoris</p>
                        <a href="{{ url('/wish-list/' . $user->id) }}" class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-eye me-1"></i>Voir Mes Favoris
                        </a>
                    </div>
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="fas fa-shopping-cart fa-3x text-success"></i>
                        </div>
                        <h3 class="fw-bold text-success">{{ $user->cartItems()->count() }}</h3>
                        <p class="text-muted mb-3">Articles au Panier</p>
                        <a href="{{ route('cart.index') }}" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-eye me-1"></i>Voir Mon Panier
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Security Notice -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="alert alert-info border-0 shadow-sm">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-shield-alt fa-2x me-3"></i>
                        <div>
                            <h6 class="mb-1 fw-bold">Sécurité de Votre Compte</h6>
                            <p class="mb-0 small">Vos informations sont protégées et ne seront jamais partagées avec des tiers. Changez régulièrement votre mot de passe pour plus de sécurité.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
    }
    
    .form-control:focus {
        border-color: #f28b00;
        box-shadow: 0 0 0 0.25rem rgba(242, 139, 0, 0.25);
    }
    
    .btn-primary {
        background-color: #f28b00;
        border-color: #f28b00;
    }
    
    .btn-primary:hover {
        background-color: #d67700;
        border-color: #d67700;
    }
    
    .btn-outline-primary {
        color: #f28b00;
        border-color: #f28b00;
    }
    
    .btn-outline-primary:hover {
        background-color: #f28b00;
        border-color: #f28b00;
        color: white;
    }
    
    .text-primary {
        color: #f28b00 !important;
    }
    
    .bg-primary {
        background-color: #f28b00 !important;
    }
    
    .border-primary {
        border-color: #f28b00 !important;
    }
    
    .card-header {
        border-bottom: 2px solid rgba(255,255,255,0.1);
    }
    
    .input-group .btn {
        border-color: #ced4da;
    }
    
    .alert {
        border-left: 4px solid;
    }
    
    .alert-success {
        border-left-color: #28a745;
    }
    
    .alert-info {
        border-left-color: #17a2b8;
    }
</style>

<script>
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const button = field.nextElementSibling;
        const icon = button.querySelector('i');
        
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
    
    // Auto-hide success messages after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert-success');
        alerts.forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });
    });
</script>
@endsection