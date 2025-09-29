@extends('layouts.app')
@section('title', 'Create Category')
@section('content')
<div class="main-content p-6 w-full h-full">
    <div class="card bg-white rounded-lg shadow-md p-8">
        <div class="flex items-center justify-between mb-6 border-b border-gray-200 pb-4">
            <h4 class="text-xl font-semibold text-gray-800">Create New Category</h4>
            <a href="{{ url('/categories') }}" class="btn bg-gray-500 text-white font-bold py-2 px-4 rounded-full hover:bg-gray-600 transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i> Back to Categories
            </a>
        </div>
        
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Error!</strong>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ url('/categories/create') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Category Name</label>
                <input type="text" name="name" id="name" placeholder="Category Name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
            </div>
            
            <div>
                <label for="image_path" class="block text-sm font-medium text-gray-700 mb-1">Category Image</label>
                <input type="file" name="image" id="image_path" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
            </div>
            
            <div id="image-preview-container" class="mt-4 hidden">
                <label class="block text-sm font-medium text-gray-700 mb-2">Image Preview</label>
                <img id="image-preview" src="#" alt="Image Preview" class="w-48 h-48 object-cover rounded-lg border border-gray-300 shadow-sm">
            </div>

            <div class="flex justify-end">
                <button type="submit" class="btn bg-blue-600 text-white font-bold py-2 px-6 rounded-full hover:bg-blue-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Submit
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('image_path').addEventListener('change', function(event) {
        const [file] = event.target.files;
        if (file) {
            const previewContainer = document.getElementById('image-preview-container');
            const previewImage = document.getElementById('image-preview');
            previewImage.src = URL.createObjectURL(file);
            previewContainer.classList.remove('hidden');
        }
    });
</script>
@endsection