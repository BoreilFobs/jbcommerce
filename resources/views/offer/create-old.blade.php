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
            
            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom du Produit</label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           placeholder="Entrez le nom du produit" 
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 sm:text-sm" 
                           required>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Catégorie</label>
                        <select name="category" 
                                id="category" 
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 sm:text-sm" 
                                required>
                            <option value="" disabled selected>Sélectionnez une catégorie</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->name }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Prix</label>
                        <div class="relative mt-1">
                            <input type="number" 
                                   name="price" 
                                   id="price" 
                                   placeholder="Entrez le prix" 
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 sm:text-sm pl-3 pr-12" 
                                   required>
                            <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">FCFA</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantité</label>
                    <input type="number" 
                           name="quantity" 
                           id="quantity" 
                           placeholder="Entrez la quantité" 
                           min="0"
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 sm:text-sm" 
                           required>
                </div>
                
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" 
                              id="description" 
                              rows="4" 
                              placeholder="Entrez la description du produit" 
                              class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 sm:text-sm" 
                              required></textarea>
                </div>

                <div>
                    <label for="images" class="block text-sm font-medium text-gray-700 mb-1">Images du Produit (maximum 5)</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="images" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                    <span>Télécharger des fichiers</span>
                                    <input type="file" name="images[]" id="images" class="sr-only" multiple accept="image/*" required>
                                </label>
                                <p class="pl-1">ou glisser-déposer</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF jusqu'à 10MB</p>
                        </div>
                    </div>
                </div>

                <div id="image-preview-container" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Aperçu des Images</label>
                    <div id="image-preview-list" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4"></div>
                </div>
            </div>

            <div class="flex justify-end pt-6">
                <button type="submit" class="w-full sm:w-auto btn bg-blue-600 text-white font-bold py-3 px-8 rounded-full hover:bg-blue-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Créer le Produit
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const dropZone = document.querySelector('label[for="images"]').parentElement.parentElement;
    const fileInput = document.getElementById('images');
    const previewContainer = document.getElementById('image-preview-container');
    const previewList = document.getElementById('image-preview-list');
    const maxFiles = 5;
    
    // Prevent default drag behaviors
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });

    // Highlight drop zone when dragging over it
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });

    // Handle dropped files
    dropZone.addEventListener('drop', handleDrop, false);

    function preventDefaults (e) {
        e.preventDefault();
        e.stopPropagation();
    }

    function highlight(e) {
        dropZone.classList.add('border-blue-500', 'border-opacity-50', 'bg-blue-50');
    }

    function unhighlight(e) {
        dropZone.classList.remove('border-blue-500', 'border-opacity-50', 'bg-blue-50');
    }

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        
        if (files.length > maxFiles) {
            alert(`Vous ne pouvez sélectionner que ${maxFiles} images maximum.`);
            return;
        }
        
        handleFiles(files);
    }

    function handleFiles(files) {
        if (files.length > maxFiles) {
            alert(`Vous ne pouvez sélectionner que ${maxFiles} images maximum.`);
            return;
        }

        previewList.innerHTML = '';
        
        if (files.length > 0) {
            previewContainer.classList.remove('hidden');
            
            Array.from(files).forEach(file => {
                if (!file.type.startsWith('image/')) {
                    alert(`Le fichier "${file.name}" n'est pas une image valide.`);
                    return;
                }
                
                if (file.size > 10 * 1024 * 1024) {
                    alert(`Le fichier "${file.name}" dépasse la limite de 10MB.`);
                    return;
                }
                
                const wrapper = document.createElement('div');
                wrapper.className = 'relative group';
                
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.className = 'w-full aspect-square object-cover rounded-lg border border-gray-300 shadow-sm';
                
                const overlay = document.createElement('div');
                overlay.className = 'absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded-lg flex items-center justify-center';
                overlay.innerHTML = `
                    <button type="button" class="text-white hover:text-red-500 transition-colors duration-200">
                        <i class="fas fa-trash"></i>
                    </button>
                `;
                
                wrapper.appendChild(img);
                wrapper.appendChild(overlay);
                previewList.appendChild(wrapper);
                
                // Add remove functionality
                const removeBtn = overlay.querySelector('button');
                removeBtn.onclick = () => wrapper.remove();
            });
        } else {
            previewContainer.classList.add('hidden');
        }
    }

    fileInput.addEventListener('change', function(e) {
        handleFiles(this.files);
    });
</script>
@endsection