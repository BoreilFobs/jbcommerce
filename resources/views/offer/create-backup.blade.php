@extends('layouts.app')
@section('title', 'Nouveau Produit')
@section('content')
<div class="main-content p-4 sm:p-6 w-full h-full">
    <div class="card bg-white rounded-lg shadow-xl p-4 sm:p-8">
        <div class="flex flex-col sm:flex-row items-center justify-between mb-6 sm:mb-8 border-b border-gray-200 pb-4 gap-4">
            <h4 class="text-xl sm:text-2xl font-bold text-gray-800">Nouveau Produit</h4>
            <a href="{{ route('offer.index') }}" class="w-full sm:w-auto btn bg-gray-500 text-white font-bold py-2 px-4 rounded-full hover:bg-gray-600 transition-colors duration-200 text-center">
                <i class="fas fa-arrow-left mr-2"></i> Retour aux Produits
            </a>
        </div>
        
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-6" role="alert">
                <strong class="font-bold">Erreur!</strong>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ url('/offers/create') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <!-- Basic Information -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h5 class="text-lg font-semibold text-gray-700 mb-4">Informations de base</h5>
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom du Produit <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                               placeholder="Ex: iPhone 15 Pro Max" 
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 sm:text-sm" 
                               required>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Catégorie <span class="text-red-500">*</span></label>
                            <select name="category" id="category" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 sm:text-sm" required>
                                <option value="" disabled selected>Sélectionnez une catégorie</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->name }}" {{ old('category') == $category->name ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="brand" class="block text-sm font-medium text-gray-700 mb-1">Marque</label>
                            <input type="text" name="brand" id="brand" value="{{ old('brand') }}"
                                   placeholder="Ex: Apple, Samsung, etc." 
                                   class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 sm:text-sm">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing & Stock -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h5 class="text-lg font-semibold text-gray-700 mb-4">Prix et Stock</h5>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Prix (FCFA) <span class="text-red-500">*</span></label>
                        <input type="number" name="price" id="price" value="{{ old('price') }}"
                               placeholder="0" min="0" step="1"
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 sm:text-sm" 
                               required>
                    </div>
                    <div>
                        <label for="discount_percentage" class="block text-sm font-medium text-gray-700 mb-1">Réduction (%)</label>
                        <input type="number" name="discount_percentage" id="discount_percentage" value="{{ old('discount_percentage', 0) }}"
                               placeholder="0" min="0" max="100"
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 sm:text-sm">
                    </div>
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantité <span class="text-red-500">*</span></label>
                        <input type="number" name="quantity" id="quantity" value="{{ old('quantity') }}"
                               placeholder="0" min="0"
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 sm:text-sm" 
                               required>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h5 class="text-lg font-semibold text-gray-700 mb-4">Description</h5>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description du produit <span class="text-red-500">*</span></label>
                    <textarea name="description" id="description" rows="5" 
                              placeholder="Décrivez le produit en détail..." 
                              class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 sm:text-sm" 
                              required>{{ old('description') }}</textarea>
                </div>
            </div>

            <!-- Specifications -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h5 class="text-lg font-semibold text-gray-700 mb-4">Spécifications techniques</h5>
                <div id="specifications-container" class="space-y-3">
                    <div class="flex gap-2 specification-row">
                        <input type="text" name="specifications[0][key]" placeholder="Ex: Écran" 
                               class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 sm:text-sm">
                        <input type="text" name="specifications[0][value]" placeholder="Ex: 6.7 pouces OLED" 
                               class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 sm:text-sm">
                        <button type="button" onclick="removeSpecification(this)" class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <button type="button" onclick="addSpecification()" class="mt-3 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                    <i class="fas fa-plus mr-2"></i> Ajouter une spécification
                </button>
            </div>

            <!-- Images -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h5 class="text-lg font-semibold text-gray-700 mb-4">Images du Produit</h5>
                <div>
                    <label for="images" class="block text-sm font-medium text-gray-700 mb-1">Images (max 5) <span class="text-red-500">*</span></label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors cursor-pointer">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600 justify-center">
                                <label for="images" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 px-2">
                                    <span>Télécharger des fichiers</span>
                                    <input type="file" name="images[]" id="images" class="sr-only" multiple accept="image/*" required>
                                </label>
                                <p>ou glisser-déposer</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, WEBP jusqu'à 2MB chacune</p>
                        </div>
                    </div>
                </div>

                <div id="image-preview-container" class="hidden mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Aperçu des Images</label>
                    <div id="image-preview-list" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4"></div>
                </div>
            </div>

            <!-- Status & Visibility -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h5 class="text-lg font-semibold text-gray-700 mb-4">Statut et Visibilité</h5>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                        <select name="status" id="status" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 sm:text-sm">
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Actif</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactif</option>
                        </select>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="featured" id="featured" value="1" {{ old('featured') ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="featured" class="ml-2 block text-sm text-gray-700">
                            <i class="fas fa-star text-yellow-500"></i> Marquer comme produit vedette
                        </label>
                    </div>
                </div>
            </div>

            <!-- SEO (Optional) -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h5 class="text-lg font-semibold text-gray-700 mb-4">SEO (Optionnel)</h5>
                <div class="space-y-4">
                    <div>
                        <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-1">Titre Meta</label>
                        <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title') }}"
                               placeholder="Laissez vide pour utiliser le nom du produit" 
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 sm:text-sm">
                    </div>
                    <div>
                        <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                        <textarea name="meta_description" id="meta_description" rows="2" 
                                  placeholder="Description pour les moteurs de recherche" 
                                  class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 sm:text-sm">{{ old('meta_description') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end gap-4 pt-6">
                <a href="{{ route('offer.index') }}" class="btn bg-gray-500 text-white font-bold py-3 px-8 rounded-full hover:bg-gray-600 transition-colors duration-200">
                    Annuler
                </a>
                <button type="submit" class="btn bg-blue-600 text-white font-bold py-3 px-8 rounded-full hover:bg-blue-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-save mr-2"></i> Créer le Produit
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let specificationCount = 1;

    function addSpecification() {
        const container = document.getElementById('specifications-container');
        const newRow = document.createElement('div');
        newRow.className = 'flex gap-2 specification-row';
        newRow.innerHTML = `
            <input type="text" name="specifications[${specificationCount}][key]" placeholder="Ex: RAM" 
                   class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 sm:text-sm">
            <input type="text" name="specifications[${specificationCount}][value]" placeholder="Ex: 8GB" 
                   class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 sm:text-sm">
            <button type="button" onclick="removeSpecification(this)" class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                <i class="fas fa-trash"></i>
            </button>
        `;
        container.appendChild(newRow);
        specificationCount++;
    }

    function removeSpecification(button) {
        const row = button.closest('.specification-row');
        if (document.querySelectorAll('.specification-row').length > 1) {
            row.remove();
        } else {
            alert('Vous devez avoir au moins une spécification.');
        }
    }

    // Image preview handling
    const dropZone = document.querySelector('.border-dashed').parentElement;
    const fileInput = document.getElementById('images');
    const previewContainer = document.getElementById('image-preview-container');
    const previewList = document.getElementById('image-preview-list');
    const maxFiles = 5;
    
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, e => {
            e.preventDefault();
            e.stopPropagation();
        });
    });

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => {
            dropZone.querySelector('.border-dashed').classList.add('border-blue-500', 'bg-blue-50');
        });
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => {
            dropZone.querySelector('.border-dashed').classList.remove('border-blue-500', 'bg-blue-50');
        });
    });

    dropZone.addEventListener('drop', e => {
        const files = e.dataTransfer.files;
        if (files.length > maxFiles) {
            alert(`Vous ne pouvez sélectionner que ${maxFiles} images maximum.`);
            return;
        }
        fileInput.files = files;
        handleFiles(files);
    });

    function handleFiles(files) {
        if (files.length > maxFiles) {
            alert(`Vous ne pouvez sélectionner que ${maxFiles} images maximum.`);
            return;
        }

        previewList.innerHTML = '';
        
        if (files.length > 0) {
            previewContainer.classList.remove('hidden');
            
            Array.from(files).forEach((file, index) => {
                if (!file.type.startsWith('image/')) {
                    alert(`Le fichier "${file.name}" n'est pas une image valide.`);
                    return;
                }
                
                const wrapper = document.createElement('div');
                wrapper.className = 'relative group';
                wrapper.dataset.index = index;
                
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.className = 'w-full aspect-square object-cover rounded-lg border-2 border-gray-300 shadow-sm';
                
                const overlay = document.createElement('div');
                overlay.className = 'absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded-lg flex items-center justify-center';
                overlay.innerHTML = `
                    <span class="text-white text-sm font-medium">Image ${index + 1}</span>
                `;
                
                wrapper.appendChild(img);
                wrapper.appendChild(overlay);
                previewList.appendChild(wrapper);
            });
        } else {
            previewContainer.classList.add('hidden');
        }
    }

    fileInput.addEventListener('change', function() {
        handleFiles(this.files);
    });
</script>
@endsection
