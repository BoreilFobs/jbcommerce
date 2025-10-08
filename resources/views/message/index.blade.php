@extends('layouts.app')
@section('title', 'Messages')
@section('content')
<div class="main-content p-4 sm:p-6">
    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-4" role="alert">
            <strong class="font-bold">Succès!</strong>
            <span class="block sm:inline ml-2">{{ session('success') }}</span>
            <button type="button" class="absolute top-0 right-0 p-4" onclick="this.parentElement.remove()">
                <span class="sr-only">Fermer</span>
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-4 sm:p-6 bg-gray-50 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <h2 class="text-xl font-bold text-gray-800">Messages</h2>
                <div class="w-full sm:w-64">
                    <div class="relative">
                        <input type="text" 
                               id="searchInput" 
                               placeholder="Rechercher..." 
                               class="w-full pl-10 pr-4 py-2 rounded-full border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Desktop View -->
        <div class="hidden sm:block overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Téléphone</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Objet</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Message</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($messages as $message)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $message->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $message->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $message->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $message->phone }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $message->object }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="max-w-xs truncate">{{ $message->message }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <button onclick="confirmDelete('{{ $message->id }}')" 
                                        class="text-red-600 hover:text-red-900" 
                                        title="Supprimer le message">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile View -->
        <div class="sm:hidden divide-y divide-gray-200">
            @foreach ($messages as $message)
                <div class="p-4 hover:bg-gray-50 message-card">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h3 class="font-medium text-gray-900">{{ $message->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $message->email }}</p>
                        </div>
                        <button onclick="confirmDelete('{{ $message->id }}')" 
                                class="text-red-600 hover:text-red-900 p-2" 
                                title="Supprimer le message">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <div class="space-y-2 text-sm">
                        <p><span class="font-medium">Téléphone:</span> {{ $message->phone }}</p>
                        <p><span class="font-medium">Objet:</span> {{ $message->object }}</p>
                        <p class="text-gray-600">{{ $message->message }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Confirmer la suppression</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Êtes-vous sûr de vouloir supprimer ce message ? Cette action ne peut pas être annulée.
                </p>
            </div>
            <div class="flex justify-center gap-4 mt-4">
                <button id="cancelDelete" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-full shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Annuler
                </button>
                <button id="confirmDelete" class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-full shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                    Supprimer
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const messageCards = document.querySelectorAll('.message-card');
    const tableRows = document.querySelectorAll('tbody tr');

    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();

        // Search in mobile view
        messageCards.forEach(card => {
            const text = card.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });

        // Search in desktop view
        tableRows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Delete confirmation
    function confirmDelete(messageId) {
        const modal = document.getElementById('deleteModal');
        const confirmBtn = document.getElementById('confirmDelete');
        const cancelBtn = document.getElementById('cancelDelete');
        
        modal.classList.remove('hidden');
        
        confirmBtn.onclick = function() {
            window.location.href = "{{ url('/message/delete') }}/" + messageId;
        }
        
        cancelBtn.onclick = function() {
            modal.classList.add('hidden');
        }
        
        // Close modal when clicking outside
        modal.onclick = function(e) {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        }
    }
</script>
@endsection
