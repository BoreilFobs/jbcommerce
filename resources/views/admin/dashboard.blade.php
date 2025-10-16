@extends('layouts.app')
@section('title', 'Tableau de Bord')

@section('content')
<div class="main-content p-4 sm:p-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Tableau de Bord</h1>
        <p class="text-gray-600 mt-1">Aperçu de votre boutique en ligne</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6">
        <!-- Total Products -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Produits</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $totalProducts }}</h3>
                    <p class="text-blue-100 text-xs mt-2">
                        <i class="fas fa-check-circle mr-1"></i>{{ $activeProducts }} actifs
                    </p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <i class="fas fa-box text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- Inventory Value -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Valeur Stock</p>
                    <h3 class="text-2xl sm:text-3xl font-bold mt-2">{{ number_format($totalInventoryValue, 0, ',', ' ') }}</h3>
                    <p class="text-green-100 text-xs mt-2">FCFA</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <i class="fas fa-coins text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- Users -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Utilisateurs</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $totalUsers }}</h3>
                    <p class="text-purple-100 text-xs mt-2">
                        <i class="fas fa-shopping-cart mr-1"></i>{{ $activeCartsCount }} paniers actifs
                    </p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <i class="fas fa-users text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- Categories -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">Catégories</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $totalCategories }}</h3>
                    <p class="text-orange-100 text-xs mt-2">
                        <i class="fas fa-star mr-1"></i>{{ $featuredProductsCount }} vedettes
                    </p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <i class="fas fa-tags text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Row -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
            <p class="text-xs text-gray-500 uppercase">Cette Semaine</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ $productsThisWeek }}</p>
            <p class="text-xs text-gray-500 mt-1">nouveaux produits</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
            <p class="text-xs text-gray-500 uppercase">Ce Mois</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ $productsThisMonth }}</p>
            <p class="text-xs text-gray-500 mt-1">nouveaux produits</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-red-500">
            <p class="text-xs text-gray-500 uppercase">Rupture Stock</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ $outOfStockProducts }}</p>
            <p class="text-xs text-gray-500 mt-1">produits épuisés</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-yellow-500">
            <p class="text-xs text-gray-500 uppercase">Prix Moyen</p>
            <p class="text-xl sm:text-2xl font-bold text-gray-800 mt-1">{{ number_format($averagePrice, 0) }}</p>
            <p class="text-xs text-gray-500 mt-1">FCFA</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Most Viewed Products -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-4">
                <h2 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-eye mr-2"></i>
                    Produits les Plus Vus
                </h2>
            </div>
            <div class="p-4">
                @if($mostViewedProducts->count() > 0)
                    <div class="space-y-3">
                        @foreach($mostViewedProducts as $product)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="flex items-center flex-1 min-w-0">
                                    @php
                                        $images = is_string($product->images) ? json_decode($product->images, true) : $product->images;
                                        $firstImage = $images && is_array($images) && count($images) > 0 
                                            ? 'storage/offer_img/product' . $product->id . '/' . $images[0]
                                            : 'img/default-product.jpg';
                                    @endphp
                                    <img src="{{ asset($firstImage) }}" alt="{{ $product->name }}" class="w-12 h-12 rounded-lg object-cover mr-3 flex-shrink-0">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-800 truncate">{{ $product->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $product->category }}</p>
                                    </div>
                                </div>
                                <div class="ml-3 flex items-center bg-indigo-100 px-3 py-1 rounded-full">
                                    <i class="fas fa-eye text-indigo-600 mr-1 text-xs"></i>
                                    <span class="text-sm font-bold text-indigo-600">{{ $product->views }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">Aucune donnée disponible</p>
                @endif
            </div>
        </div>

        <!-- Low Stock Alerts -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-red-500 to-pink-600 px-6 py-4">
                <h2 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Alertes Stock Faible
                </h2>
            </div>
            <div class="p-4">
                @if($lowStockProducts->count() > 0)
                    <div class="space-y-3">
                        @foreach($lowStockProducts as $product)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="flex items-center flex-1 min-w-0">
                                    @php
                                        $images = is_string($product->images) ? json_decode($product->images, true) : $product->images;
                                        $firstImage = $images && is_array($images) && count($images) > 0 
                                            ? 'storage/offer_img/product' . $product->id . '/' . $images[0]
                                            : 'img/default-product.jpg';
                                    @endphp
                                    <img src="{{ asset($firstImage) }}" alt="{{ $product->name }}" class="w-12 h-12 rounded-lg object-cover mr-3 flex-shrink-0">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-800 truncate">{{ $product->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $product->category }}</p>
                                    </div>
                                </div>
                                <div class="ml-3 flex items-center bg-red-100 px-3 py-1 rounded-full">
                                    <i class="fas fa-box text-red-600 mr-1 text-xs"></i>
                                    <span class="text-sm font-bold text-red-600">{{ $product->quantity }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4 text-center">
                        <a href="{{ route('offer.index') }}?status=low_stock" class="text-red-600 hover:text-red-700 text-sm font-semibold">
                            Voir tous les produits en stock faible →
                        </a>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-check-circle text-green-500 text-4xl mb-2"></i>
                        <p class="text-gray-500">Tous les stocks sont bons!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Products by Category Chart -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="lg:col-span-2 bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-cyan-500 to-blue-600 px-6 py-4">
                <h2 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-chart-pie mr-2"></i>
                    Distribution par Catégorie
                </h2>
            </div>
            <div class="p-6">
                @if($productsByCategory->count() > 0)
                    <div class="space-y-4">
                        @foreach($productsByCategory as $category)
                            @php
                                $percentage = $totalProducts > 0 ? ($category->total / $totalProducts) * 100 : 0;
                            @endphp
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-semibold text-gray-700">{{ $category->category }}</span>
                                    <span class="text-sm font-bold text-gray-900">{{ $category->total }} ({{ number_format($percentage, 1) }}%)</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                                    <div class="bg-gradient-to-r from-cyan-500 to-blue-600 h-3 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">Aucune catégorie disponible</p>
                @endif
            </div>
        </div>

        <!-- Status Distribution -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-green-500 to-teal-600 px-6 py-4">
                <h2 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-chart-bar mr-2"></i>
                    Statut Produits
                </h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($statusDistribution as $status)
                        @php
                            $statusColors = [
                                'active' => ['bg' => 'bg-green-500', 'text' => 'text-green-700', 'label' => 'Actifs'],
                                'inactive' => ['bg' => 'bg-gray-500', 'text' => 'text-gray-700', 'label' => 'Inactifs'],
                                'out_of_stock' => ['bg' => 'bg-red-500', 'text' => 'text-red-700', 'label' => 'Épuisés']
                            ];
                            $color = $statusColors[$status->status] ?? ['bg' => 'bg-blue-500', 'text' => 'text-blue-700', 'label' => $status->status];
                        @endphp
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-4 h-4 {{ $color['bg'] }} rounded-full mr-3"></div>
                                <span class="text-sm font-semibold {{ $color['text'] }}">{{ $color['label'] }}</span>
                            </div>
                            <span class="text-lg font-bold text-gray-800">{{ $status->count }}</span>
                        </div>
                    @endforeach
                    
                    <!-- Additional stats -->
                    <div class="flex items-center justify-between p-4 bg-yellow-50 rounded-lg border-l-4 border-yellow-500">
                        <div class="flex items-center">
                            <i class="fas fa-tag text-yellow-600 mr-3"></i>
                            <span class="text-sm font-semibold text-yellow-700">En Promotion</span>
                        </div>
                        <span class="text-lg font-bold text-yellow-700">{{ $discountedProducts }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Products -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-pink-500 to-rose-600 px-6 py-4 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-white flex items-center">
                <i class="fas fa-clock mr-2"></i>
                Produits Récents
            </h2>
            <a href="{{ route('offer.index') }}" class="text-white hover:text-pink-100 text-sm font-semibold">
                Voir tous →
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Catégorie</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Stock</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Statut</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($recentProducts as $product)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @php
                                        $images = is_string($product->images) ? json_decode($product->images, true) : $product->images;
                                        $firstImage = $images && is_array($images) && count($images) > 0 
                                            ? 'storage/offer_img/product' . $product->id . '/' . $images[0]
                                            : 'img/default-product.jpg';
                                    @endphp
                                    <img src="{{ asset($firstImage) }}" alt="{{ $product->name }}" class="w-10 h-10 rounded-lg object-cover mr-3">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ Str::limit($product->name, 30) }}</p>
                                        @if($product->brand)
                                            <p class="text-xs text-gray-500">{{ $product->brand }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap hidden sm:table-cell">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $product->category }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm">
                                    @if($product->discount_percentage > 0)
                                        <span class="line-through text-gray-400 text-xs">{{ number_format($product->price, 0) }}</span><br>
                                        <span class="font-bold text-red-600">{{ number_format($product->discounted_price, 0) }} F</span>
                                    @else
                                        <span class="font-semibold text-gray-900">{{ number_format($product->price, 0) }} FCFA</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">
                                <span class="text-sm {{ $product->quantity <= 5 ? 'text-red-600 font-bold' : 'text-gray-700' }}">
                                    {{ $product->quantity }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap hidden lg:table-cell">
                                @php
                                    $statusBadges = [
                                        'active' => 'bg-green-100 text-green-800',
                                        'inactive' => 'bg-gray-100 text-gray-800',
                                        'out_of_stock' => 'bg-red-100 text-red-800'
                                    ];
                                    $badgeClass = $statusBadges[$product->status] ?? 'bg-blue-100 text-blue-800';
                                @endphp
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $badgeClass }}">
                                    {{ ucfirst($product->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('offer.updateF', $product->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ url('/shop/' . $product->id) }}" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="{{ route('offer.create') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow border-l-4 border-blue-500 flex items-center">
            <div class="bg-blue-100 rounded-full p-3 mr-4">
                <i class="fas fa-plus text-blue-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Ajouter</p>
                <p class="text-lg font-semibold text-gray-800">Nouveau Produit</p>
            </div>
        </a>
        
        <a href="{{ route('offer.index') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow border-l-4 border-green-500 flex items-center">
            <div class="bg-green-100 rounded-full p-3 mr-4">
                <i class="fas fa-list text-green-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Gérer</p>
                <p class="text-lg font-semibold text-gray-800">Tous Produits</p>
            </div>
        </a>
        
        <a href="{{ url('/') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow border-l-4 border-purple-500 flex items-center">
            <div class="bg-purple-100 rounded-full p-3 mr-4">
                <i class="fas fa-globe text-purple-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Voir</p>
                <p class="text-lg font-semibold text-gray-800">Site Client</p>
            </div>
        </a>
        
        <a href="#" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow border-l-4 border-orange-500 flex items-center">
            <div class="bg-orange-100 rounded-full p-3 mr-4">
                <i class="fas fa-cog text-orange-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Configurer</p>
                <p class="text-lg font-semibold text-gray-800">Paramètres</p>
            </div>
        </a>
    </div>
</div>
@endsection
