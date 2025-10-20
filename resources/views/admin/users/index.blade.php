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
                    <h1 class="h3 mb-0"><i class="fas fa-users me-2"></i>Gestion des Utilisateurs</h1>
                    <p class="text-muted">Gérer tous les utilisateurs de la plateforme</p>
                </div>
                <div class="text-end">
                    <span class="badge bg-primary fs-6">{{ $users->total() }} utilisateur(s)</span>
                </div>
            </div>

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Users Table Card -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Liste des Utilisateurs</h5>
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-sm btn-outline-primary" onclick="window.print()">
                                <i class="fas fa-print me-1"></i>Imprimer
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($users->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width: 60px;">ID</th>
                                        <th>Nom</th>
                                        <th>Email</th>
                                        <th class="text-center">Panier</th>
                                        <th class="text-center">Wishlist</th>
                                        <th class="text-center">Date d'inscription</th>
                                        <th class="text-center" style="width: 200px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr id="user-row-{{ $user->id }}">
                                            <td class="text-center fw-bold">#{{ $user->id }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle bg-primary text-white me-2" style="width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold">{{ $user->name }}</div>
                                                        @if($user->name === 'admin')
                                                            <span class="badge bg-danger">Administrateur</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <i class="fas fa-envelope text-muted me-1"></i>
                                                {{ $user->email }}
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-info">
                                                    <i class="fas fa-shopping-cart me-1"></i>{{ $user->cartItems->count() }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-heart me-1"></i>{{ $user->wishlistItems->count() }}
                                                </span>
                                            </td>
                                            <td class="text-center text-muted">
                                                <i class="far fa-calendar-alt me-1"></i>
                                                {{ $user->created_at->format('d/m/Y') }}
                                                <br>
                                                <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.users.show', $user->id) }}" 
                                                       class="btn btn-sm btn-outline-info" 
                                                       title="Voir les détails">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($user->name !== 'admin')
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-danger" 
                                                                onclick="confirmDelete({{ $user->id }}, '{{ $user->name }}')"
                                                                title="Supprimer l'utilisateur">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    @else
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-secondary" 
                                                                disabled
                                                                title="Impossible de supprimer l'admin">
                                                            <i class="fas fa-lock"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                                
                                                <!-- Hidden delete form -->
                                                <form id="delete-form-{{ $user->id }}" 
                                                      action="{{ route('admin.users.destroy', $user->id) }}" 
                                                      method="POST" 
                                                      class="d-none">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun utilisateur trouvé</h5>
                        </div>
                    @endif
                </div>
                @if($users->hasPages())
                    <div class="card-footer bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted">
                                Affichage de {{ $users->firstItem() }} à {{ $users->lastItem() }} sur {{ $users->total() }} utilisateurs
                            </div>
                            <div>
                                {{ $users->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirmer la suppression
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="fas fa-user-times fa-3x text-danger"></i>
                </div>
                <p class="text-center mb-0">
                    Êtes-vous sûr de vouloir supprimer l'utilisateur <strong id="userName"></strong> ?
                </p>
                <p class="text-center text-danger small mt-2">
                    <i class="fas fa-info-circle me-1"></i>
                    Cette action est irréversible et supprimera également :
                </p>
                <ul class="small text-muted">
                    <li>Tous les articles dans le panier</li>
                    <li>Tous les articles dans la liste de souhaits</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Annuler
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="fas fa-trash me-1"></i>Supprimer
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let deleteUserId = null;

    function confirmDelete(userId, userName) {
        deleteUserId = userId;
        document.getElementById('userName').textContent = userName;
        
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    }

    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if (deleteUserId) {
            document.getElementById('delete-form-' + deleteUserId).submit();
        }
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
</script>

<style>
    .table tbody tr {
        transition: all 0.3s ease;
    }
    
    .table tbody tr:hover {
        background-color: #f8f9fa;
        transform: scale(1.01);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .btn-group .btn {
        margin: 0 2px;
    }

    .avatar-circle {
        font-size: 14px;
    }

    @media print {
        .btn, .modal, .card-footer, .btn-group {
            display: none !important;
        }
    }
</style>
@endsection
