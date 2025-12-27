@extends('layouts.app')
@section('title', 'Nouvelle Catégorie')
@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-folder-plus mr-3 text-blue-600"></i>
                Nouvelle Catégorie
            </h1>
            <p class="text-gray-600 mt-1">Créez une nouvelle catégorie de produits</p>
        </div>
        <a href="{{ url('/categories') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Retour
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

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
            <h2 class="text-lg font-semibold text-white flex items-center">
                <i class="fas fa-info-circle mr-2"></i>
                Informations de la catégorie
            </h2>
        </div>
        
        <form action="{{ url('/categories/create') }}" method="POST" enctype="multipart/form-data" id="categoryForm" class="p-6 space-y-6">
            @csrf
            
            <!-- Category Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Nom de la catégorie <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                       placeholder="Ex: Électronique, Smartphones, Accessoires..." 
                       class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Le nom doit être unique et descriptif</p>
            </div>
            
            <!-- Category Image -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Image de la catégorie <span class="text-red-500">*</span>
                </label>
                <div id="dropZone" class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-blue-400 hover:bg-blue-50 transition-all cursor-pointer">
                    <div class="space-y-3">
                        <div class="w-16 h-16 mx-auto bg-gray-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-cloud-upload-alt text-3xl text-gray-400"></i>
                        </div>
                        <div>
                            <label for="image" class="cursor-pointer">
                                <span class="text-blue-600 hover:text-blue-700 font-medium">Cliquez pour télécharger</span>
                                <span class="text-gray-500"> ou glissez-déposez</span>
                            </label>
                            <input type="file" name="image" id="image" class="hidden" accept="image/*">
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG jusqu'à 5Mo</p>
                    </div>
                </div>
                @error('image')
                    <p class="mt-2 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Image Preview -->
            <div id="imagePreviewContainer" class="hidden">
                <label class="block text-sm font-medium text-gray-700 mb-2">Aperçu de l'image</label>
                <div class="relative w-full max-w-sm">
                    <img id="imagePreview" src="#" alt="Aperçu" class="w-full aspect-video object-cover rounded-lg border-2 border-gray-200">
                    <button type="button" id="removeImage" class="absolute top-2 right-2 w-8 h-8 bg-red-500 text-white rounded-full hover:bg-red-600 transition-colors flex items-center justify-center">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex flex-col sm:flex-row justify-end gap-4 pt-4 border-t border-gray-200">
                <a href="{{ url('/categories') }}" 
                   class="w-full sm:w-auto px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors text-center">
                    Annuler
                </a>
                <button type="submit" id="submitBtn"
                        class="w-full sm:w-auto px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center">
                    <i class="fas fa-save mr-2"></i>
                    Créer la catégorie
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('image');
    const previewContainer = document.getElementById('imagePreviewContainer');
    const previewImage = document.getElementById('imagePreview');
    const removeBtn = document.getElementById('removeImage');

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
        const file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            fileInput.files = e.dataTransfer.files;
            showPreview(file);
        }
    });

    fileInput.addEventListener('change', function() {
        if (this.files[0]) {
            showPreview(this.files[0]);
        }
    });

    function showPreview(file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            previewContainer.classList.remove('hidden');
            dropZone.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    }

    removeBtn.addEventListener('click', function() {
        fileInput.value = '';
        previewContainer.classList.add('hidden');
        dropZone.classList.remove('hidden');
    });

    // Form submission loading state
    document.getElementById('categoryForm').addEventListener('submit', function() {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Création...';
    });
</script>
@endsection
