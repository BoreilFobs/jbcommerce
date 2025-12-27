@extends('layouts.app')
@section('title', 'Nouveau Produit')
@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-plus-circle mr-3 text-blue-600"></i>
                Nouveau Produit
            </h1>
            <p class="text-gray-600 mt-1">Ajoutez un nouveau produit au catalogue</p>
        </div>
        <a href="{{ route('offer.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Retour aux Produits
        </a>
    </div>

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-4 mb-6" role="alert">
            <div class="flex items-center mb-2">
                <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                <h3 class="text-red-800 font-semibold">Erreurs de validation</h3>
            </div>
            <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('offer.store') }}" method="POST" enctype="multipart/form-data" id="productForm" class="space-y-6">
        @csrf
        
        <!-- Basic Information Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                <h2 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-info-circle mr-2"></i>
                    Informations de base
                </h2>
            </div>
            <div class="p-6 space-y-5">
                <!-- Product Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nom du Produit <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                           placeholder="Ex: iPhone 15 Pro Max 256GB" 
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Category & Brand -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                            Catégorie <span class="text-red-500">*</span>
                        </label>
                        <select name="category" id="category" 
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors @error('category') border-red-500 @enderror">
                            <option value="">Sélectionnez une catégorie</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->name }}" {{ old('category') == $category->name ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="brand" class="block text-sm font-medium text-gray-700 mb-2">
                            Marque
                        </label>
                        <input type="text" name="brand" id="brand" value="{{ old('brand') }}"
                               placeholder="Ex: Apple, Samsung, Xiaomi..." 
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors">
                    </div>
                </div>
            </div>
        </div>

        <!-- Pricing & Stock Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
                <h2 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-tags mr-2"></i>
                    Prix et Stock
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <!-- Price -->
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                            Prix (FCFA) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="price" id="price" value="{{ old('price') }}"
                                   placeholder="0" min="0" step="1"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors @error('price') border-red-500 @enderror">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">FCFA</span>
                        </div>
                        @error('price')
                            <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Discount -->
                    <div>
                        <label for="discount_percentage" class="block text-sm font-medium text-gray-700 mb-2">
                            Réduction (%)
                        </label>
                        <div class="relative">
                            <input type="number" name="discount_percentage" id="discount_percentage" 
                                   value="{{ old('discount_percentage') }}"
                                   placeholder="0" min="0" max="100" step="1"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors @error('discount_percentage') border-red-500 @enderror">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">%</span>
                        </div>
                        @error('discount_percentage')
                            <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Entrez un nombre entre 0 et 100</p>
                    </div>
                    
                    <!-- Quantity -->
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                            Quantité en stock <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="quantity" id="quantity" value="{{ old('quantity') }}"
                               placeholder="0" min="0"
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors @error('quantity') border-red-500 @enderror">
                        @error('quantity')
                            <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Price Preview -->
                <div id="pricePreview" class="mt-4 p-4 bg-gray-50 rounded-lg hidden">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Aperçu du prix:</span>
                        <div class="text-right">
                            <span id="originalPrice" class="text-gray-400 line-through text-sm mr-2"></span>
                            <span id="finalPrice" class="text-xl font-bold text-green-600"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Description Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-6 py-4">
                <h2 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-align-left mr-2"></i>
                    Description
                </h2>
            </div>
            <div class="p-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Description du produit <span class="text-red-500">*</span>
                </label>
                <textarea name="description" id="description" rows="5" 
                          placeholder="Décrivez le produit en détail: caractéristiques, avantages, contenu du package..." 
                          class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors resize-none @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Minimum 20 caractères recommandés</p>
            </div>
        </div>

        <!-- Specifications Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4">
                <h2 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-list-ul mr-2"></i>
                    Spécifications techniques
                </h2>
            </div>
            <div class="p-6">
                <div id="specifications-container" class="space-y-3">
                    <div class="flex flex-col sm:flex-row gap-3 specification-row">
                        <input type="text" name="specifications[0][key]" placeholder="Caractéristique (Ex: Écran)" 
                               class="flex-1 px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors">
                        <input type="text" name="specifications[0][value]" placeholder="Valeur (Ex: 6.7 pouces OLED)" 
                               class="flex-1 px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors">
                        <button type="button" onclick="removeSpecification(this)" 
                                class="px-4 py-3 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-colors">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <button type="button" onclick="addSpecification()" 
                        class="mt-4 inline-flex items-center px-4 py-2 bg-orange-100 text-orange-700 rounded-lg hover:bg-orange-200 transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Ajouter une spécification
                </button>
            </div>
        </div>

        <!-- Images Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-pink-500 to-pink-600 px-6 py-4">
                <h2 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-images mr-2"></i>
                    Images du Produit
                </h2>
            </div>
            <div class="p-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Images (max 5) <span class="text-red-500">*</span>
                </label>
                <div id="dropZone" class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-blue-400 hover:bg-blue-50 transition-all cursor-pointer">
                    <div class="space-y-3">
                        <div class="w-16 h-16 mx-auto bg-gray-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-cloud-upload-alt text-3xl text-gray-400"></i>
                        </div>
                        <div>
                            <label for="images" class="cursor-pointer">
                                <span class="text-blue-600 hover:text-blue-700 font-medium">Cliquez pour télécharger</span>
                                <span class="text-gray-500"> ou glissez-déposez</span>
                            </label>
                            <input type="file" name="images[]" id="images" class="hidden" multiple accept="image/*">
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, WEBP jusqu'à 2Mo chacune</p>
                    </div>
                </div>
                @error('images')
                    <p class="mt-2 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                @enderror
                @error('images.*')
                    <p class="mt-2 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                @enderror

                <div id="imagePreviewContainer" class="mt-6 hidden">
                    <h4 class="text-sm font-medium text-gray-700 mb-3">Aperçu des images</h4>
                    <div id="imagePreviewList" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4"></div>
                </div>
            </div>
        </div>

        <!-- Status & Options Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-4">
                <h2 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-cog mr-2"></i>
                    Statut et Options
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Statut du produit
                        </label>
                        <select name="status" id="status" 
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors">
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>
                                🟢 Actif - Visible et disponible
                            </option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                🔴 Inactif - Masqué
                            </option>
                            <option value="out_of_stock" {{ old('status') == 'out_of_stock' ? 'selected' : '' }}>
                                🟡 Rupture de stock
                            </option>
                        </select>
                    </div>
                    
                    <!-- Featured -->
                    <div class="flex items-center">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="featured" id="featured" value="1" {{ old('featured') ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-yellow-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-yellow-500"></div>
                            <span class="ml-3 text-sm font-medium text-gray-700">
                                <i class="fas fa-star text-yellow-500 mr-1"></i>
                                Produit vedette
                            </span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- SEO Card (Collapsible) -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <button type="button" onclick="toggleSEO()" class="w-full bg-gradient-to-r from-gray-500 to-gray-600 px-6 py-4 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-search mr-2"></i>
                    SEO (Optionnel)
                </h2>
                <i id="seoIcon" class="fas fa-chevron-down text-white transition-transform"></i>
            </button>
            <div id="seoContent" class="p-6 hidden">
                <div class="space-y-5">
                    <div>
                        <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">
                            Titre Meta
                        </label>
                        <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title') }}"
                               placeholder="Laissez vide pour utiliser le nom du produit" 
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors">
                        <p class="mt-1 text-xs text-gray-500">Recommandé: 50-60 caractères</p>
                    </div>
                    <div>
                        <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">
                            Meta Description
                        </label>
                        <textarea name="meta_description" id="meta_description" rows="2" 
                                  placeholder="Description pour les moteurs de recherche" 
                                  class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors resize-none">{{ old('meta_description') }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Recommandé: 150-160 caractères</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex flex-col sm:flex-row justify-end gap-4 pt-4">
            <a href="{{ route('offer.index') }}" 
               class="w-full sm:w-auto px-8 py-3 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors text-center">
                Annuler
            </a>
            <button type="submit" id="submitBtn"
                    class="w-full sm:w-auto px-8 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center">
                <i class="fas fa-save mr-2"></i>
                Créer le Produit
            </button>
        </div>
    </form>
</div>

<script>
    let specificationCount = 1;

    function addSpecification() {
        const container = document.getElementById('specifications-container');
        const newRow = document.createElement('div');
        newRow.className = 'flex flex-col sm:flex-row gap-3 specification-row';
        newRow.innerHTML = `
            <input type="text" name="specifications[${specificationCount}][key]" placeholder="Caractéristique" 
                   class="flex-1 px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors">
            <input type="text" name="specifications[${specificationCount}][value]" placeholder="Valeur" 
                   class="flex-1 px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors">
            <button type="button" onclick="removeSpecification(this)" 
                    class="px-4 py-3 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-colors">
                <i class="fas fa-trash"></i>
            </button>
        `;
        container.appendChild(newRow);
        specificationCount++;
    }

    function removeSpecification(button) {
        const rows = document.querySelectorAll('.specification-row');
        if (rows.length > 1) {
            button.closest('.specification-row').remove();
        }
    }

    function toggleSEO() {
        const content = document.getElementById('seoContent');
        const icon = document.getElementById('seoIcon');
        content.classList.toggle('hidden');
        icon.classList.toggle('rotate-180');
    }

    // Price preview calculation
    const priceInput = document.getElementById('price');
    const discountInput = document.getElementById('discount_percentage');
    const pricePreview = document.getElementById('pricePreview');
    const originalPrice = document.getElementById('originalPrice');
    const finalPrice = document.getElementById('finalPrice');

    function updatePricePreview() {
        const price = parseFloat(priceInput.value) || 0;
        const discount = parseInt(discountInput.value) || 0;
        
        if (price > 0) {
            pricePreview.classList.remove('hidden');
            const discountedPrice = price - (price * discount / 100);
            
            if (discount > 0) {
                originalPrice.textContent = price.toLocaleString('fr-FR') + ' FCFA';
                originalPrice.classList.remove('hidden');
            } else {
                originalPrice.classList.add('hidden');
            }
            finalPrice.textContent = Math.round(discountedPrice).toLocaleString('fr-FR') + ' FCFA';
        } else {
            pricePreview.classList.add('hidden');
        }
    }

    priceInput.addEventListener('input', updatePricePreview);
    discountInput.addEventListener('input', updatePricePreview);

    // Clean discount input on change (remove leading zeros)
    discountInput.addEventListener('change', function() {
        this.value = parseInt(this.value) || 0;
    });

    // Image handling
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('images');
    const previewContainer = document.getElementById('imagePreviewContainer');
    const previewList = document.getElementById('imagePreviewList');
    const maxFiles = 5;

    dropZone.addEventListener('click', () => fileInput.click());

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, e => {
            e.preventDefault();
            e.stopPropagation();
        });
    });

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => {
            dropZone.classList.add('border-blue-500', 'bg-blue-50');
        });
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => {
            dropZone.classList.remove('border-blue-500', 'bg-blue-50');
        });
    });

    dropZone.addEventListener('drop', e => {
        const files = e.dataTransfer.files;
        if (files.length > maxFiles) {
            alert(`Maximum ${maxFiles} images autorisées.`);
            return;
        }
        fileInput.files = files;
        handleFiles(files);
    });

    fileInput.addEventListener('change', function() {
        handleFiles(this.files);
    });

    function handleFiles(files) {
        if (files.length > maxFiles) {
            alert(`Maximum ${maxFiles} images autorisées.`);
            return;
        }

        previewList.innerHTML = '';
        
        if (files.length > 0) {
            previewContainer.classList.remove('hidden');
            
            Array.from(files).forEach((file, index) => {
                if (!file.type.startsWith('image/')) return;
                
                const wrapper = document.createElement('div');
                wrapper.className = 'relative group aspect-square';
                
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.className = 'w-full h-full object-cover rounded-lg border-2 border-gray-200';
                img.onload = () => URL.revokeObjectURL(img.src);
                
                const badge = document.createElement('div');
                badge.className = 'absolute top-2 left-2 px-2 py-1 bg-blue-600 text-white text-xs rounded-full';
                badge.textContent = index === 0 ? 'Principal' : `Image ${index + 1}`;
                
                wrapper.appendChild(img);
                wrapper.appendChild(badge);
                previewList.appendChild(wrapper);
            });
        } else {
            previewContainer.classList.add('hidden');
        }
    }

    // Form submission loading state
    document.getElementById('productForm').addEventListener('submit', function() {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Création en cours...';
    });
</script>
@endsection
