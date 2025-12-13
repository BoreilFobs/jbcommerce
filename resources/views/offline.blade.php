@extends('layouts.web')

@section('content')
<div class="container-fluid py-5" style="min-height: 70vh;">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <!-- Offline Icon -->
                <div class="mb-4">
                    <i class="fas fa-wifi-slash" style="font-size: 120px; color: #dc3545;"></i>
                </div>
                
                <!-- Title -->
                <h1 class="display-4 mb-3" style="color: #333;">
                    Pas de Connexion Internet
                </h1>
                
                <!-- Description -->
                <p class="lead text-muted mb-4">
                    Vous semblez être hors ligne. Veuillez vérifier votre connexion Internet.
                </p>
                
                <!-- Offline Features -->
                <div class="alert alert-info mb-4" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Mode Hors Ligne :</strong> Vous pouvez toujours parcourir les pages que vous avez déjà visitées.
                </div>
                
                <!-- Tips -->
                <div class="card mb-4">
                    <div class="card-body text-start">
                        <h5 class="card-title mb-3">
                            <i class="fas fa-lightbulb text-warning me-2"></i>
                            Que faire ?
                        </h5>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Vérifiez votre connexion Wi-Fi ou données mobiles
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Activez le mode avion puis désactivez-le
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Redémarrez votre routeur si nécessaire
                            </li>
                            <li class="mb-0">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                Réessayez dans quelques instants
                            </li>
                        </ul>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="d-grid gap-2 d-md-block">
                    <button onclick="window.location.reload()" class="btn btn-primary btn-lg px-5">
                        <i class="fas fa-sync-alt me-2"></i>
                        Réessayer
                    </button>
                    <a href="{{ url('/') }}" class="btn btn-outline-secondary btn-lg px-5">
                        <i class="fas fa-home me-2"></i>
                        Accueil (Hors ligne)
                    </a>
                </div>
                
                <!-- Connection Status -->
                <div class="mt-4">
                    <div id="connection-status" class="badge bg-danger py-2 px-3">
                        <i class="fas fa-circle me-2" style="font-size: 8px;"></i>
                        Hors Ligne
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }
    
    #connection-status {
        animation: pulse 2s infinite;
    }
    
    .card {
        border: 1px solid #e0e0e0;
        border-radius: 10px;
    }
    
    .btn-lg {
        border-radius: 50px;
    }
</style>

<script>
    // Monitor connection status
    function updateConnectionStatus() {
        const statusBadge = document.getElementById('connection-status');
        
        if (navigator.onLine) {
            statusBadge.className = 'badge bg-success py-2 px-3';
            statusBadge.innerHTML = '<i class="fas fa-circle me-2" style="font-size: 8px;"></i> En Ligne';
            
            // Automatically reload after 2 seconds if back online
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            statusBadge.className = 'badge bg-danger py-2 px-3';
            statusBadge.innerHTML = '<i class="fas fa-circle me-2" style="font-size: 8px;"></i> Hors Ligne';
        }
    }
    
    // Check connection every 3 seconds
    setInterval(updateConnectionStatus, 3000);
    
    // Listen for online/offline events
    window.addEventListener('online', updateConnectionStatus);
    window.addEventListener('offline', updateConnectionStatus);
    
    // Initial check
    updateConnectionStatus();
</script>
@endsection
