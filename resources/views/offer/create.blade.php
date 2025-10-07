@extends('layouts.app')
@section('title', 'Create Product')
@section('content')
<div class="main-content p-6 w-full h-full">
    <div class="card bg-white rounded-lg shadow-xl p-8">
        <div class="flex items-center justify-between mb-8 border-b border-gray-200 pb-4">
            <h4 class="text-2xl font-bold text-gray-800">Create New Product</h4>
            <a href="{{ route('offer.index') }}" class="btn bg-gray-500 text-white font-bold py-2 px-4 rounded-full hover:bg-gray-600 transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i> Back to Products
            </a>
        </div>
        
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                <strong class="font-bold">Error!</strong>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ url('/offers/create') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
                <input type="text" name="name" id="name" placeholder="Enter product name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select name="category" id="category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                        <option value="" disabled selected>Select category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->name }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                    <input type="number" name="price" id="price" placeholder="Enter price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                </div>
            </div>

            <div class="">
                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                <input type="number" name="quantity" id="quantity" placeholder="Enter quantity" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
            </div>
            
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" id="description" rows="3" placeholder="Enter product description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required></textarea>
            </div>
            <div>
                <label for="images" class="block text-sm font-medium text-gray-700 mb-1">Product Images (up to 5)</label>
                <input type="file" name="images[]" id="images" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" multiple accept="image/*" required>
            </div>
            <div id="image-preview-container" class="mt-4 hidden">
                <label class="block text-sm font-medium text-gray-700 mb-2">Image Previews</label>
                <div id="image-preview-list" class="flex gap-4 flex-wrap"></div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="btn bg-blue-600 text-white font-bold py-2 px-6 rounded-full hover:bg-blue-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Create Product
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('images').addEventListener('change', function(event) {
        const files = event.target.files;
        const previewContainer = document.getElementById('image-preview-container');
        const previewList = document.getElementById('image-preview-list');
        previewList.innerHTML = '';
        if (files.length > 0) {
            previewContainer.classList.remove('hidden');
            Array.from(files).slice(0, 5).forEach(file => {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.className = 'w-32 h-32 object-cover rounded-lg border border-gray-300 shadow-sm';
                previewList.appendChild(img);
            });
        } else {
            previewContainer.classList.add('hidden');
        }
    });
</script>
@endsection