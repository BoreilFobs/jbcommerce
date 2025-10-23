@extends('layouts.app')

@section('title', 'Commande #' . $order->order_number)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center px-3 py-1.5 mb-3 text-sm bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Retour
            </a>
            <h1 class="text-2xl lg:text-3xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-shopping-bag mr-3 text-blue-600"></i>
                Commande #{{ $order->order_number }}
            </h1>
            <p class="text-gray-600 mt-1">Détails et gestion de la commande</p>
        </div>
        <div class="mt-3 sm:mt-0 hidden md:block">
            <a href="{{ route('admin.orders.invoice', $order->id) }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors" target="_blank">
                <i class="fas fa-file-invoice mr-2"></i>Facture
            </a>
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Info Card -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h5 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                        Informations de la Commande
                    </h5>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <div class="text-xs text-gray-500 mb-1">N° Commande</div>
                            <div class="font-semibold text-gray-900">{{ $order->order_number }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 mb-1">Date</div>
                            <div class="font-semibold text-gray-900">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 mb-1">Statut</div>
                            {!! $order->status_badge !!}
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 mb-1">Paiement</div>
                            {!! $order->payment_status_badge !!}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Info Card -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h5 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-user mr-2 text-blue-600"></i>
                        Informations Client
                    </h5>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <div class="text-xs text-gray-500 mb-1">Nom complet</div>
                            <div class="font-semibold text-gray-900">{{ $order->shipping_name }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 mb-1">Téléphone</div>
                            <div class="font-semibold text-gray-900">
                                <i class="fas fa-phone mr-2 text-gray-400"></i>{{ $order->shipping_phone }}
                            </div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 mb-1">Email</div>
                            <div class="font-semibold text-gray-900">
                                <i class="fas fa-envelope mr-2 text-gray-400"></i>{{ $order->shipping_email }}
                            </div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 mb-1">Ville</div>
                            <div class="font-semibold text-gray-900">{{ $order->shipping_city }}, {{ $order->shipping_region }}</div>
                        </div>
                        <div class="md:col-span-2">
                            <div class="text-xs text-gray-500 mb-1">Adresse complète</div>
                            <div class="font-semibold text-gray-900">{{ $order->shipping_address }}</div>
                        </div>
                        @if($order->customer_notes)
                            <div class="md:col-span-2">
                                <div class="text-xs text-gray-500 mb-1">Notes du client</div>
                                <div class="mt-1 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                    <i class="fas fa-comment-dots mr-2 text-blue-600"></i>{{ $order->customer_notes }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Order Items Card -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h5 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-box mr-2 text-blue-600"></i>
                        Articles Commandés ({{ $order->items->count() }})
                    </h5>
                </div>
                
                <!-- Desktop View -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 80px;">Image</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produit</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Qté</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Prix Unit.</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Remise</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($order->items as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        @if($item->offer && $item->offer->image)
                                            <img src="{{ asset('storage/' . $item->offer->image) }}" alt="{{ $item->product_name }}" class="w-16 h-16 object-cover rounded">
                                        @else
                                            <div class="w-16 h-16 bg-gray-100 rounded flex items-center justify-center">
                                                <i class="fas fa-image text-gray-400"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-900">{{ $item->product_name }}</div>
                                        @if($item->discount_percentage > 0)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 mt-1">
                                                -{{ $item->discount_percentage }}%
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $item->quantity }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right text-gray-900">{{ number_format($item->unit_price, 0, ',', ' ') }} FCFA</td>
                                    <td class="px-6 py-4 text-right">
                                        @if($item->discount_amount > 0)
                                            <span class="text-red-600">-{{ number_format($item->discount_amount, 0, ',', ' ') }} FCFA</span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right font-semibold text-gray-900">{{ number_format($item->subtotal, 0, ',', ' ') }} FCFA</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile View -->
                <div class="md:hidden">
                    @foreach($order->items as $item)
                        <div class="p-4 border-b border-gray-200 last:border-b-0">
                            <div class="flex gap-3 mb-3">
                                @if($item->offer && $item->offer->image)
                                    <img src="{{ asset('storage/' . $item->offer->image) }}" alt="{{ $item->product_name }}" class="w-16 h-16 object-cover rounded flex-shrink-0">
                                @else
                                    <div class="w-16 h-16 bg-gray-100 rounded flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-image text-gray-400"></i>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <div class="font-semibold text-gray-900">{{ $item->product_name }}</div>
                                    @if($item->discount_percentage > 0)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 mt-1">
                                            -{{ $item->discount_percentage }}%
                                        </span>
                                    @endif
                                    <div class="mt-1">
                                        <span class="text-xs text-gray-500">Qté: </span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $item->quantity }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Prix unitaire:</span>
                                <span class="text-gray-900">{{ number_format($item->unit_price, 0, ',', ' ') }} FCFA</span>
                            </div>
                            @if($item->discount_amount > 0)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Remise:</span>
                                    <span class="text-red-600">-{{ number_format($item->discount_amount, 0, ',', ' ') }} FCFA</span>
                                </div>
                            @endif
                            <div class="flex justify-between mt-2 pt-2 border-t border-gray-200">
                                <span class="font-semibold text-gray-900">Total:</span>
                                <span class="font-semibold text-blue-600">{{ number_format($item->subtotal, 0, ',', ' ') }} FCFA</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Order Summary -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-blue-600 text-white">
                    <h5 class="text-lg font-semibold flex items-center">
                        <i class="fas fa-receipt mr-2"></i>
                        Résumé
                    </h5>
                </div>
                <div class="p-6 space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Sous-total</span>
                        <span class="font-semibold text-gray-900">{{ number_format($order->subtotal, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Livraison</span>
                        <span class="font-semibold text-gray-900">{{ number_format($order->shipping_cost, 0, ',', ' ') }} FCFA</span>
                    </div>
                    @if($order->discount_amount > 0)
                        <div class="flex justify-between text-red-600">
                            <span>Remise</span>
                            <span class="font-semibold">-{{ number_format($order->discount_amount, 0, ',', ' ') }} FCFA</span>
                        </div>
                    @endif
                    <hr class="border-gray-200">
                    <div class="flex justify-between text-lg">
                        <span class="font-bold text-gray-900">Total</span>
                        <span class="font-bold text-blue-600">{{ number_format($order->total_amount, 0, ',', ' ') }} FCFA</span>
                    </div>
                </div>
            </div>

            <!-- Payment Info -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h5 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-credit-card mr-2 text-blue-600"></i>
                        Paiement
                    </h5>
                </div>
                <div class="p-6 space-y-3">
                    <div>
                        <div class="text-xs text-gray-500 mb-1">Méthode</div>
                        <div class="font-semibold text-gray-900">{!! $order->payment_method_name !!}</div>
                    </div>
                    @if($order->payment_phone)
                        <div>
                            <div class="text-xs text-gray-500 mb-1">Téléphone Mobile Money</div>
                            <div class="font-semibold text-gray-900">{{ $order->payment_phone }}</div>
                        </div>
                    @endif
                    @if($order->payment_reference)
                        <div>
                            <div class="text-xs text-gray-500 mb-1">Référence</div>
                            <div class="font-semibold text-gray-900">{{ $order->payment_reference }}</div>
                        </div>
                    @endif
                    @if($order->tracking_number)
                        <div>
                            <div class="text-xs text-gray-500 mb-1">N° de Suivi</div>
                            <div class="font-semibold text-gray-900">{{ $order->tracking_number }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Update Status Form -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h5 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-edit mr-2 text-blue-600"></i>
                        Mettre à Jour
                    </h5>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Update Order Status -->
                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Statut de la Commande</label>
                        <div class="flex gap-2">
                            <select name="status" class="flex-1 rounded-lg border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-sm" required>
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>En attente</option>
                                <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmée</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>En cours</option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Expédiée</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Livrée</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                            </select>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-save"></i>
                            </button>
                        </div>
                    </form>

                    <!-- Update Payment Status -->
                    <form action="{{ route('admin.orders.updatePayment', $order->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Statut du Paiement</label>
                        <div class="flex gap-2 mb-2">
                            <select name="payment_status" class="flex-1 rounded-lg border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-sm" required>
                                <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>En attente</option>
                                <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Payé</option>
                                <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Échoué</option>
                                <option value="refunded" {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>Remboursé</option>
                            </select>
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                <i class="fas fa-save"></i>
                            </button>
                        </div>
                        <input type="text" name="payment_reference" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-sm" placeholder="Référence de paiement (optionnel)" value="{{ $order->payment_reference }}">
                    </form>

                    <!-- Update Tracking -->
                    <form action="{{ route('admin.orders.updateTracking', $order->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Numéro de Suivi</label>
                        <div class="flex gap-2">
                            <input type="text" name="tracking_number" class="flex-1 rounded-lg border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-sm" placeholder="Ex: DHL123456" value="{{ $order->tracking_number }}" required>
                            <button type="submit" class="px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 transition-colors">
                                <i class="fas fa-save"></i>
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Le statut sera automatiquement mis à "Expédiée"</p>
                    </form>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h5 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-tools mr-2 text-blue-600"></i>
                        Actions
                    </h5>
                </div>
                <div class="p-6 space-y-2">
                    <a href="{{ route('admin.orders.invoice', $order->id) }}" class="block w-full px-4 py-2 bg-green-600 text-white text-center rounded-lg hover:bg-green-700 transition-colors" target="_blank">
                        <i class="fas fa-file-invoice mr-2"></i>Voir la Facture
                    </a>
                    <a href="{{ route('orders.track') }}?order_number={{ $order->order_number }}" class="block w-full px-4 py-2 bg-cyan-600 text-white text-center rounded-lg hover:bg-cyan-700 transition-colors" target="_blank">
                        <i class="fas fa-map-marker-alt mr-2"></i>Suivi Public
                    </a>
                    @if(in_array($order->status, ['cancelled', 'delivered']))
                        <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette commande? Cette action est irréversible.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                <i class="fas fa-trash mr-2"></i>Supprimer la Commande
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Timestamps -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h5 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-clock mr-2 text-blue-600"></i>
                        Historique
                    </h5>
                </div>
                <div class="p-6">
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-start">
                            <i class="fas fa-plus-circle text-blue-600 mr-2 mt-0.5"></i>
                            <div>
                                <span class="font-semibold text-gray-900">Créée:</span>
                                <span class="text-gray-600">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </li>
                        @if($order->paid_at)
                            <li class="flex items-start">
                                <i class="fas fa-credit-card text-green-600 mr-2 mt-0.5"></i>
                                <div>
                                    <span class="font-semibold text-gray-900">Payée:</span>
                                    <span class="text-gray-600">{{ $order->paid_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </li>
                        @endif
                        @if($order->shipped_at)
                            <li class="flex items-start">
                                <i class="fas fa-shipping-fast text-cyan-600 mr-2 mt-0.5"></i>
                                <div>
                                    <span class="font-semibold text-gray-900">Expédiée:</span>
                                    <span class="text-gray-600">{{ $order->shipped_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </li>
                        @endif
                        @if($order->delivered_at)
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-600 mr-2 mt-0.5"></i>
                                <div>
                                    <span class="font-semibold text-gray-900">Livrée:</span>
                                    <span class="text-gray-600">{{ $order->delivered_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </li>
                        @endif
                        @if($order->cancelled_at)
                            <li class="flex items-start">
                                <i class="fas fa-times-circle text-red-600 mr-2 mt-0.5"></i>
                                <div>
                                    <span class="font-semibold text-gray-900">Annulée:</span>
                                    <span class="text-gray-600">{{ $order->cancelled_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
