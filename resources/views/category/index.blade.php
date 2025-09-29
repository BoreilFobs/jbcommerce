@extends('layouts.app')
@section('title', 'Categories')
@section('content')
<div class="main-content p-6">

    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md" role="alert">
            <p class="font-bold">Success!</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">
            <p class="font-bold">Error!</p>
            <p>{{ session('error') }}</p>
        </div>
    @endif
    
    <div class="flex items-center justify-between p-6 bg-white rounded-t-lg shadow-md mb-6">
        <h4 class="text-xl font-semibold text-gray-800">Categories</h4>
        <a href="{{ route('categories.create') }}" class="btn bg-blue-600 text-white font-bold py-2 px-4 rounded-full hover:bg-blue-700 transition-colors duration-200">
            <i class="fas fa-plus mr-2"></i> Add New Category
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach ($categories as $category)
        <div class="bg-white rounded-lg shadow-md overflow-hidden transform hover:scale-105 transition-transform duration-200">
            <img src="{{ 'storage/' . $category->image_path }}" alt="{{ $category->name }}" class="w-full h-48 object-cover">
            <div class="p-4 text-center">
                <h5 class="text-lg font-bold text-gray-800 mb-2">{{ $category->name }}</h5>
                <div class="flex justify-center space-x-2">
                    <a href="{{ route('categories.update', ['id' => $category->id]) }}" class="btn bg-yellow-500 text-white font-bold py-1 px-3 rounded-full hover:bg-yellow-600 transition-colors duration-200">
                        Edit
                    </a>
                    <form action="{{ route('categories.delete', ['id' => $category->id]) }}" method="GET" onsubmit="return confirm('Are you sure you want to delete this category?');">
                        @csrf
                        <button type="submit" class="btn bg-red-500 text-white font-bold py-1 px-3 rounded-full hover:bg-red-600 transition-colors duration-200">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection