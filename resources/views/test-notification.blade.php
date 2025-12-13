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
                        <li class="breadcrumb-item active" aria-current="page">Test Notifications</li>
                    </ol>
                </nav>
                <h1 class="display-5 fw-bold text-primary mb-2">
                    <i class="fas fa-bell me-3"></i>Test Notifications FCM
                </h1>
                <p class="text-muted">Testez le système de notifications push Firebase</p>
            </div>
        </div>

        <!-- Status Messages -->
        <div id="notification-status"></div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- FCM Status Card -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>État du Système FCM
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="status-icon me-3" id="auth-status">
                                        <i class="fas fa-user fa-2x text-muted"></i>
                                    </div>
                                    <div>
                                        <strong>Authentification</strong>
                                        <p class="mb-0 text-muted small" id="auth-text">Vérification...</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="status-icon me-3" id="permission-status">
                                        <i class="fas fa-bell fa-2x text-muted"></i>
                                    </div>
                                    <div>
                                        <strong>Permission Notifications</strong>
                                        <p class="mb-0 text-muted small" id="permission-text">Vérification...</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="status-icon me-3" id="token-status">
                                        <i class="fas fa-key fa-2x text-muted"></i>
                                    </div>
                                    <div>
                                        <strong>FCM Token</strong>
                                        <p class="mb-0 text-muted small" id="token-text">Vérification...</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="status-icon me-3" id="db-token-status">
                                        <i class="fas fa-database fa-2x text-muted"></i>
                                    </div>
                                    <div>
                                        <strong>Token en Base</strong>
                                        <p class="mb-0 text-muted small" id="db-token-text">
                                            @if(Auth::user()->fcm_token)
                                                Enregistré ✓
                                            @else
                                                Non enregistré
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Test Notification Cards -->
        <div class="row g-4">
            <!-- Test 1: Simple Notification -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-check-circle me-2"></i>Test Simple
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Envoyer une notification de test basique</p>
                        <form action="{{ route('test.notification') }}" method="POST" class="test-notification-form">
                            @csrf
                            <input type="hidden" name="type" value="simple">
                            <button type="submit" class="btn btn-success w-100 btn-lg">
                                <i class="fas fa-paper-plane me-2"></i>Envoyer Test Simple
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Test 2: Order Notification -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-shopping-cart me-2"></i>Test Commande
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Simuler une notification de commande</p>
                        <form action="{{ route('test.notification') }}" method="POST" class="test-notification-form">
                            @csrf
                            <input type="hidden" name="type" value="order">
                            <button type="submit" class="btn btn-info w-100 btn-lg">
                                <i class="fas fa-box me-2"></i>Notification Commande
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Test 3: Status Change -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-warning text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-sync-alt me-2"></i>Test Changement Statut
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Notification de changement de statut</p>
                        <form action="{{ route('test.notification') }}" method="POST" class="test-notification-form">
                            @csrf
                            <input type="hidden" name="type" value="status">
                            <div class="mb-3">
                                <select name="status" class="form-select" required>
                                    <option value="">Choisir un statut</option>
                                    <option value="pending">En Attente</option>
                                    <option value="processing">En Traitement</option>
                                    <option value="shipped">Expédié</option>
                                    <option value="delivered">Livré</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-warning w-100 btn-lg">
                                <i class="fas fa-exchange-alt me-2"></i>Test Statut
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Test 4: Promotion -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-gift me-2"></i>Test Promotion
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Notification promotionnelle personnalisée</p>
                        <form action="{{ route('test.notification') }}" method="POST" class="test-notification-form">
                            @csrf
                            <input type="hidden" name="type" value="promotion">
                            <div class="mb-3">
                                <input type="text" name="promo_title" class="form-control" placeholder="Titre de la promo" value="🎁 Offre Spéciale!">
                            </div>
                            <div class="mb-3">
                                <textarea name="promo_body" class="form-control" rows="2" placeholder="Message de la promo">Profitez de 20% de réduction sur tous les smartphones!</textarea>
                            </div>
                            <button type="submit" class="btn btn-danger w-100 btn-lg">
                                <i class="fas fa-bullhorn me-2"></i>Test Promotion
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Info -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">
                            <i class="fas fa-user me-2"></i>Informations Utilisateur
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <strong>ID:</strong> {{ Auth::id() }}
                            </div>
                            <div class="col-md-6">
                                <strong>Nom:</strong> {{ Auth::user()->name }}
                            </div>
                            <div class="col-md-12">
                                <strong>FCM Token:</strong>
                                @if(Auth::user()->fcm_token)
                                    <code class="small">{{ substr(Auth::user()->fcm_token, 0, 50) }}...</code>
                                @else
                                    <span class="text-danger">Non enregistré</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Instructions -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="alert alert-info border-0 shadow-sm">
                    <h6 class="fw-bold mb-2">
                        <i class="fas fa-lightbulb me-2"></i>Instructions
                    </h6>
                    <ol class="mb-0">
                        <li>Assurez-vous que les notifications sont autorisées dans votre navigateur</li>
                        <li>Vérifiez que vous êtes authentifié (connecté)</li>
                        <li>Cliquez sur un des boutons de test ci-dessus</li>
                        <li>La notification devrait apparaître dans quelques secondes</li>
                        <li>Si aucune notification n'apparaît, vérifiez la console du navigateur (F12)</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .status-icon i {
        transition: all 0.3s ease;
    }
    
    .status-icon.success i {
        color: #28a745 !important;
    }
    
    .status-icon.error i {
        color: #dc3545 !important;
    }
    
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.2) !important;
    }
    
    .btn {
        transition: all 0.3s ease;
    }
    
    .btn:disabled {
        cursor: not-allowed;
        opacity: 0.6;
    }
    
    .spinner-border-sm {
        width: 1rem;
        height: 1rem;
        border-width: 0.15em;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check authentication status
        const isAuthenticated = document.querySelector('meta[name="user-authenticated"]')?.content === 'true';
        updateStatus('auth-status', 'auth-text', isAuthenticated, 
            isAuthenticated ? 'Connecté ✓' : 'Non connecté ✗');

        // Check notification permission
        if ('Notification' in window) {
            const permission = Notification.permission;
            const granted = permission === 'granted';
            updateStatus('permission-status', 'permission-text', granted, 
                granted ? 'Autorisée ✓' : (permission === 'denied' ? 'Refusée ✗' : 'Non demandée'));
            
            // Request permission if not granted
            if (permission === 'default' && isAuthenticated) {
                setTimeout(() => {
                    if (confirm('Voulez-vous activer les notifications pour tester?')) {
                        Notification.requestPermission().then(perm => {
                            location.reload();
                        });
                    }
                }, 1000);
            }
        } else {
            updateStatus('permission-status', 'permission-text', false, 'Non supporté ✗');
        }

        // Check FCM token
        const tokenNative = localStorage.getItem('fcm_token_native');
        const tokenWeb = localStorage.getItem('fcm_token');
        const token = tokenNative || tokenWeb;
        
        updateStatus('token-status', 'token-text', !!token, 
            token ? 'Token disponible ✓' : 'Token manquant ✗');

        // Check DB token
        const dbToken = {{ Auth::user()->fcm_token ? 'true' : 'false' }};
        updateStatus('db-token-status', 'db-token-text', dbToken, 
            dbToken ? 'Enregistré ✓' : 'Non enregistré ✗');

        // Handle form submissions
        document.querySelectorAll('.test-notification-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const btn = this.querySelector('button[type="submit"]');
                const originalText = btn.innerHTML;
                
                // Disable button and show loading
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Envoi...';
                
                // Submit form
                fetch(this.action, {
                    method: 'POST',
                    body: new FormData(this),
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    showNotificationStatus(data.success, data.message);
                    
                    // Re-enable button
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                })
                .catch(error => {
                    showNotificationStatus(false, 'Erreur lors de l\'envoi: ' + error.message);
                    
                    // Re-enable button
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                });
            });
        });
    });

    function updateStatus(iconId, textId, success, text) {
        const icon = document.getElementById(iconId);
        const textEl = document.getElementById(textId);
        
        if (icon) {
            icon.classList.remove('success', 'error');
            icon.classList.add(success ? 'success' : 'error');
        }
        
        if (textEl) {
            textEl.textContent = text;
            textEl.classList.remove('text-success', 'text-danger', 'text-muted');
            textEl.classList.add(success ? 'text-success' : 'text-danger');
        }
    }

    function showNotificationStatus(success, message) {
        const container = document.getElementById('notification-status');
        const alert = document.createElement('div');
        alert.className = `alert alert-${success ? 'success' : 'danger'} alert-dismissible fade show`;
        alert.innerHTML = `
            <i class="fas fa-${success ? 'check-circle' : 'exclamation-circle'} me-2"></i>${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        container.innerHTML = '';
        container.appendChild(alert);
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
        
        // Scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
</script>
@endsection
