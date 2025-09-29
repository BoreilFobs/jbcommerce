@extends('layouts.app')
@section('title', 'Products')

@section('content')

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    
    <div class="main-content p-6">
        <div class="flex items-center justify-between p-6 bg-white rounded-t-lg shadow-md mb-6">
            <h4 class="text-xl font-semibold text-gray-800">Products</h4>
            <a href="{{ route('offer.create') }}" class="btn bg-blue-600 text-white font-bold py-2 px-4 rounded-full hover:bg-blue-700 transition-colors duration-200">
                <i class="fas fa-plus mr-2"></i> Add New Product
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach ($offers as $offer)
                <div class="bg-white rounded-lg shadow-md overflow-hidden transform hover:scale-105 transition-transform duration-200">
                    <figure class="relative h-64 overflow-hidden">
                        <img src="{{ $offer->image_path }}" alt="Product image" class="w-full h-full object-cover">
                        
                        <!-- Actions overlay -->
                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center space-x-4 opacity-0 hover:opacity-100 transition-opacity duration-300">
                            <a href="{{ url('/offers/delete/' . $offer->id) }}" class="btn bg-red-500 text-white font-bold py-2 px-4 rounded-full hover:bg-red-600 transition-colors duration-200" title="Delete product">
                                Delete
                            </a>
                            <a href="{{ url('/offers/update/' . $offer->id) }}" class="btn bg-yellow-500 text-white font-bold py-2 px-4 rounded-full hover:bg-yellow-600 transition-colors duration-200" title="Update product">
                                Update
                            </a>
                        </div>
                    </figure>

                    <div class="p-4">
                        <div class="text-xs text-gray-500 font-semibold uppercase tracking-wide mb-1">
                            {{ $offer->category }}
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">
                            <a href="#">{{ $offer->name }}</a>
                        </h3>
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold text-gray-800">{{ $offer->price }}FCFA</span>
                                <span class="text-sm font-semibold text-green-600">{{ $offer->quantity}} units</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection