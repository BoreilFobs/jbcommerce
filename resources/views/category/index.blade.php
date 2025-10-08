@extends('layouts.app')
@section('title', 'Catégories')
@section('content')
<div class="main-content p-4 sm:p-6 max-w-7xl mx-auto">

    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md" role="alert">
            <p class="font-bold">Succès !</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">
            <p class="font-bold">Erreur !</p>
            <p>{{ session('error') }}</p>
        </div>
    @endif
    
    <div class="flex flex-col sm:flex-row items-center justify-between p-4 sm:p-6 bg-white rounded-lg shadow-sm mb-6 gap-4">
        <h4 class="text-xl font-bold text-gray-800">Catégories</h4>
        <a href="{{ route('categories.create') }}" class="w-full sm:w-auto btn bg-blue-600 text-white font-medium py-2.5 px-4 rounded-lg hover:bg-blue-700 transition-colors duration-200 text-center flex items-center justify-center space-x-2">
            <i class="fas fa-plus"></i>
            <span>Nouvelle Catégorie</span>
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
        @foreach ($categories as $category)
        <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden">
            <div class="aspect-w-16 aspect-h-9">
                <img src="{{ 'storage/' . $category->image_path }}" alt="{{ $category->name }}" class="w-full h-full object-cover">
            </div>
            <div class="p-4">
                <h5 class="text-lg font-bold text-gray-800 mb-4">{{ $category->name }}</h5>
                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                    <a href="{{ route('categories.update', ['id' => $category->id]) }}" class="w-full btn bg-yellow-500 text-white font-medium py-2 px-4 rounded-lg hover:bg-yellow-600 transition-colors duration-200 text-center flex items-center justify-center">
                        <i class="fas fa-edit mr-2"></i>
                        <span>Modifier</span>
                    </a>
                    <form action="{{ route('categories.delete', ['id' => $category->id]) }}" method="GET" class="w-full" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?');">
                        @csrf
                        <button type="submit" class="w-full btn bg-red-500 text-white font-medium py-2 px-4 rounded-lg hover:bg-red-600 transition-colors duration-200 flex items-center justify-center">
                            <i class="fas fa-trash-alt mr-2"></i>
                            <span>Supprimer</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if($categories->isEmpty())
        <div class="text-center py-12">
            <div class="text-gray-400 mb-4">
                <i class="fas fa-folder-open text-6xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900">Aucune catégorie</h3>
            <p class="mt-1 text-gray-500">Commencez par créer une nouvelle catégorie</p>
            <div class="mt-6">
                <a href="{{ route('categories.create') }}" class="btn bg-blue-600 text-white font-medium py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                    <i class="fas fa-plus mr-2"></i>
                    Créer une catégorie
                </a>
            </div>
        </div>
    @endif
</div>
@endsection