@extends('layouts.app')
@section('title', 'Modifier la Catégorie')
@section('content')
<div class="main-content p-4 sm:p-6 max-w-3xl mx-auto">
    <div class="card bg-white rounded-lg shadow-sm p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 border-b border-gray-200 pb-4 gap-4">
            <h4 class="text-xl font-bold text-gray-800">Modifier la Catégorie</h4>
            <a href="{{ url('/categories') }}" class="w-full sm:w-auto btn bg-gray-500 text-white font-medium py-2 px-4 rounded-lg hover:bg-gray-600 transition-colors duration-200 flex items-center justify-center">
                <i class="fas fa-arrow-left mr-2"></i>
                <span>Retour aux Catégories</span>
            </a>
        </div>
        
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
                <div class="flex items-center mb-2">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <strong class="font-medium">Erreur !</strong>
                </div>
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ url('/categories/' . $category->id . '/update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('put')
            
            <div class="space-y-2">
                <label for="name" class="block text-sm font-medium text-gray-700">Nom de la catégorie</label>
                <input type="text" 
                       name="name" 
                       id="name" 
                       value="{{ $category->name }}" 
                       placeholder="Ex: Électronique" 
                       class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" 
                       required>
                <p class="text-xs text-gray-500">Le nom doit être unique et descriptif</p>
            </div>

            <div class="space-y-2">
                <label for="image_path" class="block text-sm font-medium text-gray-700">Image de la catégorie</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors duration-200">
                    <div class="space-y-1 text-center">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                        <div class="flex text-sm text-gray-600">
                            <label for="image_path" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                <span>Changer l'image</span>
                                <input type="file" 
                                       name="image" 
                                       id="image_path" 
                                       accept="image/*"
                                       class="sr-only">
                            </label>
                            <p class="pl-1">ou glisser-déposer</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG jusqu'à 5MB</p>
                    </div>
                </div>
            </div>
            
            <div id="image-preview-container" class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Image actuelle</label>
                <div class="relative w-full max-w-sm mx-auto">
                    <img id="image-preview" src="{{ 'storage/' . $category->image_path}}" alt="Aperçu" class="w-full aspect-video object-cover rounded-lg border border-gray-300 shadow-sm">
                    <button type="button" id="remove-image" class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-2 hover:bg-red-600 transition-colors hidden">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4 border-t border-gray-200">
                <a href="{{ url('/categories') }}" class="w-full sm:w-auto btn bg-gray-100 text-gray-700 font-medium py-2.5 px-6 rounded-lg hover:bg-gray-200 transition-colors duration-200 text-center">
                    Annuler
                </a>
                <button type="submit" class="w-full sm:w-auto btn bg-blue-600 text-white font-medium py-2.5 px-6 rounded-lg hover:bg-blue-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 flex items-center justify-center">
                    <i class="fas fa-save mr-2"></i>
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const fileInput = document.getElementById('image_path');
    const previewContainer = document.getElementById('image-preview-container');
    const previewImage = document.getElementById('image-preview');
    const removeButton = document.getElementById('remove-image');
    const dropZone = fileInput.closest('div.border-dashed');
    const originalImageSrc = previewImage.src;

    // File drag and drop
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults (e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
        dropZone.classList.add('border-blue-500', 'bg-blue-50');
    }

    function unhighlight(e) {
        dropZone.classList.remove('border-blue-500', 'bg-blue-50');
    }

    dropZone.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const file = dt.files[0];
        if (file && file.type.startsWith('image/')) {
            fileInput.files = dt.files;
            handleFiles(file);
        }
    }

    function handleFiles(file) {
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                removeButton.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    }

    // File input change
    fileInput.addEventListener('change', function(event) {
        const [file] = event.target.files;
        if (file) {
            handleFiles(file);
        }
    });

    // Remove image (revert to original)
    removeButton.addEventListener('click', function() {
        fileInput.value = '';
        previewImage.src = originalImageSrc;
        removeButton.classList.add('hidden');
    });
</script>
@endsection