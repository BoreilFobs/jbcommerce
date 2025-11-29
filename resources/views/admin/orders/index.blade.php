@extends('layouts.app')

@section('title', 'Gestion des Commandes')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h1 class="text-2xl lg:text-3xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-shopping-bag mr-3 text-blue-600"></i>
                Gestion des Commandes
            </h1>
            <p class="text-gray-600 mt-1 hidden sm:block">Gérer toutes les commandes de la plateforme</p>
        </div>
        <div class="mt-3 sm:mt-0">
            <span class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 text-white font-semibold text-sm">
                <i class="fas fa-shopping-cart mr-2"></i>
                {{ $orders->total() }} commande(s)
            </span>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-4 p-4 rounded-lg bg-green-50 border border-green-200 flex items-center" role="alert">
            <i class="fas fa-check-circle text-green-600 mr-3 text-xl"></i>
            <span class="text-green-800">{{ session('success') }}</span>
            <button type="button" class="ml-auto text-green-600 hover:text-green-800" onclick="this.parentElement.style.display='none'">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 rounded-lg bg-red-50 border border-red-200 flex items-center" role="alert">
            <i class="fas fa-exclamation-circle text-red-600 mr-3 text-xl"></i>
            <span class="text-red-800">{{ session('error') }}</span>
            <button type="button" class="ml-auto text-red-600 hover:text-red-800" onclick="this.parentElement.style.display='none'">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Pending Orders -->
        <div class="bg-white rounded-lg shadow-sm p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600 mb-1">En attente</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['pending'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Processing Orders -->
        <div class="bg-white rounded-lg shadow-sm p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-box text-blue-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600 mb-1">En cours</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['processing'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Delivered Orders -->
        <div class="bg-white rounded-lg shadow-sm p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600 mb-1">Livrées</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['delivered'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-white rounded-lg shadow-sm p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-coins text-purple-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600 mb-1">Total</p>
                    <p class="text-lg font-bold text-gray-800">{{ number_format($stats['total_amount'] ?? 0, 0, ',', ' ') }} <span class="text-sm">FCFA</span></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <form action="{{ route('admin.orders.index') }}" method="GET">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                    <select name="status" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                        <option value="">Tous les statuts</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmée</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>En cours</option>
                        <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Expédiée</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Livrée</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                    </select>
                </div>

                <!-- Payment Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Paiement</label>
                    <select name="payment_status" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                        <option value="">Tous les paiements</option>
                        <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Payé</option>
                        <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Échoué</option>
                        <option value="refunded" {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>Remboursé</option>
                    </select>
                </div>

                <!-- Date Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                    <select name="date_filter" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                        <option value="">Toutes les dates</option>
                        <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Aujourd'hui</option>
                        <option value="week" {{ request('date_filter') == 'week' ? 'selected' : '' }}>Cette semaine</option>
                        <option value="month" {{ request('date_filter') == 'month' ? 'selected' : '' }}>Ce mois</option>
                    </select>
                </div>

                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
                    <div class="flex">
                        <input type="text" name="search" class="flex-1 rounded-l-lg border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all" placeholder="N° commande..." value="{{ request('search') }}">
                        <button type="submit" class="px-4 bg-blue-600 text-white rounded-r-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                    <i class="fas fa-filter mr-2"></i>Filtrer
                </button>
                <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors flex items-center">
                    <i class="fas fa-redo mr-2"></i>Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <!-- Bulk Actions Form -->
    <form id="bulkActionForm" action="{{ route('admin.orders.bulkUpdate') }}" method="POST">
        @csrf
        
        <!-- Bulk Actions Bar (Initially Hidden) -->
        <div id="bulkActionsBar" class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4 hidden">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <div class="flex items-center">
                    <i class="fas fa-check-square text-blue-600 mr-2"></i>
                    <span class="font-medium text-gray-800">
                        <span id="selectedCount">0</span> commande(s) sélectionnée(s)
                    </span>
                </div>
                <div class="flex gap-2">
                    <select name="status" class="rounded-lg border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-sm" required>
                        <option value="">Changer le statut...</option>
                        <option value="confirmed">Confirmée</option>
                        <option value="processing">En cours</option>
                        <option value="shipped">Expédiée</option>
                        <option value="delivered">Livrée</option>
                        <option value="cancelled">Annulée</option>
                    </select>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                        <i class="fas fa-save mr-1"></i>Appliquer
                    </button>
                    <button type="button" onclick="clearSelection()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors text-sm">
                        <i class="fas fa-times mr-1"></i>Annuler
                    </button>
                </div>
            </div>
        </div>

            <!-- Orders Table/Cards -->
    <!-- Orders List -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-list mr-2"></i>
                Liste des Commandes
            </h2>
            <button class="hidden md:inline-flex items-center px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm" onclick="window.print()">
                <i class="fas fa-print mr-2"></i>
                Imprimer
            </button>
        </div>

        @if($orders->count() > 0)
            <!-- Desktop Table View -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left">
                                <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" onchange="toggleAllCheckboxes(this)">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">N° Commande</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Articles</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Paiement</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($orders as $order)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox" name="order_ids[]" value="{{ $order->id }}" class="order-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500" onchange="updateBulkActionsBar()">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-800 text-white">
                                        {{ $order->order_number }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                                            {{ strtoupper(substr($order->user->name ?? 'G', 0, 1)) }}
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $order->user->name ?? 'Guest' }}</div>
                                            <div class="text-sm text-gray-500">{{ $order->shipping_phone }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $order->items->count() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="text-sm font-semibold text-gray-900">{{ number_format($order->total_amount, 0, ',', ' ') }} FCFA</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    {!! $order->status_badge !!}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    {!! $order->payment_status_badge !!}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><i class="far fa-calendar-alt mr-1 text-gray-400"></i>{{ $order->created_at->format('d/m/Y') }}</div>
                                    <div class="text-sm text-gray-500"><i class="far fa-clock mr-1 text-gray-400"></i>{{ $order->created_at->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- Quick Status Update -->
                                        @if(!in_array($order->status, ['cancelled', 'delivered']))
                                            <div class="relative inline-block text-left">
                                                <button type="button" onclick="toggleDropdown({{ $order->id }})" class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md text-xs font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                    <i class="fas fa-bolt mr-1 text-blue-600"></i>
                                                    Action
                                                    <i class="fas fa-chevron-down ml-1 text-gray-400"></i>
                                                </button>
                                                <div id="dropdown-{{ $order->id }}" class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10">
                                                    <div class="py-1">
                                                        @if($order->status !== 'confirmed')
                                                            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="inline w-full">
                                                                @csrf
                                                                @method('PATCH')
                                                                <input type="hidden" name="status" value="confirmed">
                                                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                                    <i class="fas fa-check text-green-600 mr-2"></i>Confirmer
                                                                </button>
                                                            </form>
                                                        @endif
                                                        @if($order->status !== 'processing')
                                                            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="inline w-full">
                                                                @csrf
                                                                @method('PATCH')
                                                                <input type="hidden" name="status" value="processing">
                                                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                                    <i class="fas fa-box text-blue-600 mr-2"></i>En cours
                                                                </button>
                                                            </form>
                                                        @endif
                                                        @if($order->status !== 'shipped')
                                                            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="inline w-full">
                                                                @csrf
                                                                @method('PATCH')
                                                                <input type="hidden" name="status" value="shipped">
                                                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                                    <i class="fas fa-truck text-purple-600 mr-2"></i>Prête
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="text-blue-600 hover:text-blue-800 p-2" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.orders.invoice', $order->id) }}" class="text-green-600 hover:text-green-800 p-2" title="Facture" target="_blank">
                                            <i class="fas fa-file-invoice"></i>
                                        </a>
                                        @if(in_array($order->status, ['cancelled', 'delivered']))
                                            <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette commande?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 p-2" title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile/Tablet Card View -->
            <div class="lg:hidden p-4 space-y-4">
                @foreach($orders as $order)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <!-- Order Header -->
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-800 text-white mb-2">
                                    {{ $order->order_number }}
                                </span>
                                <div class="text-sm text-gray-500">
                                    <i class="far fa-calendar-alt mr-1"></i>{{ $order->created_at->format('d/m/Y H:i') }}
                                </div>
                            </div>
                            <div>
                                {!! $order->status_badge !!}
                            </div>
                        </div>

                        <!-- Customer Info -->
                        <div class="flex items-center mb-3 pb-3 border-b border-gray-200">
                            <div class="flex-shrink-0 h-8 w-8 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                {{ strtoupper(substr($order->user->name ?? 'G', 0, 1)) }}
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900">{{ $order->user->name ?? 'Guest' }}</div>
                                <div class="text-sm text-gray-500">{{ $order->shipping_phone }}</div>
                            </div>
                        </div>

                        <!-- Order Details -->
                        <div class="grid grid-cols-2 gap-3 mb-3">
                            <div>
                                <div class="text-xs text-gray-500 mb-1">Articles</div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $order->items->count() }}
                                </span>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 mb-1">Paiement</div>
                                {!! $order->payment_status_badge !!}
                            </div>
                        </div>

                        <!-- Total Amount -->
                        <div class="mb-3">
                            <div class="text-xs text-gray-500 mb-1">Montant Total</div>
                            <div class="text-lg font-bold text-blue-600">{{ number_format($order->total_amount, 0, ',', ' ') }} FCFA</div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-2">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="flex-1 px-3 py-2 bg-blue-600 text-white text-center rounded-lg hover:bg-blue-700 transition-colors text-sm">
                                <i class="fas fa-eye mr-1"></i>Voir
                            </a>
                            <a href="{{ route('admin.orders.invoice', $order->id) }}" class="px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors" target="_blank" title="Facture">
                                <i class="fas fa-file-invoice"></i>
                            </a>
                            @if(in_array($order->status, ['cancelled', 'delivered']))
                                <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer cette commande?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
                        <div class="text-center py-12">
                <i class="fas fa-shopping-bag text-gray-300 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune commande trouvée</h3>
                <p class="text-gray-500">Les commandes apparaîtront ici une fois passées par les clients.</p>
            </div>
        @endif

        <!-- Pagination -->
        @if($orders->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="flex justify-center">
                    {{ $orders->links() }}
                </div>
            </div>
        @endif
    </div>
    </form>
</div>
<script>
    // Toggle individual dropdown
    function toggleDropdown(orderId) {
        const dropdown = document.getElementById(`dropdown-${orderId}`);
        // Close all other dropdowns
        document.querySelectorAll('[id^="dropdown-"]').forEach(d => {
            if (d.id !== `dropdown-${orderId}`) {
                d.classList.add('hidden');
            }
        });
        dropdown.classList.toggle('hidden');
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('[onclick^="toggleDropdown"]') && !event.target.closest('[id^="dropdown-"]')) {
            document.querySelectorAll('[id^="dropdown-"]').forEach(d => {
                d.classList.add('hidden');
            });
        }
    });

    // Toggle all checkboxes
    function toggleAllCheckboxes(source) {
        const checkboxes = document.querySelectorAll('.order-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = source.checked;
        });
        updateBulkActionsBar();
    }

    // Update bulk actions bar visibility
    function updateBulkActionsBar() {
        const checkboxes = document.querySelectorAll('.order-checkbox:checked');
        const count = checkboxes.length;
        const bulkBar = document.getElementById('bulkActionsBar');
        const countSpan = document.getElementById('selectedCount');
        
        if (count > 0) {
            bulkBar.classList.remove('hidden');
            countSpan.textContent = count;
        } else {
            bulkBar.classList.add('hidden');
            document.getElementById('selectAll').checked = false;
        }
    }

    // Clear selection
    function clearSelection() {
        document.querySelectorAll('.order-checkbox').forEach(checkbox => {
            checkbox.checked = false;
        });
        document.getElementById('selectAll').checked = false;
        updateBulkActionsBar();
    }
</script>
<style>
    @media print {
        .sidebar, .card-header button, .btn-group, form button, nav, .alert {
            display: none !important;
        }
    }

    @media (max-width: 576px) {
        .h3 {
            font-size: 1.3rem;
        }
        .card-body h4 {
            font-size: 1.1rem;
        }
        .card-body h6 {
            font-size: 0.75rem;
        }
    }
</style>
@endsection