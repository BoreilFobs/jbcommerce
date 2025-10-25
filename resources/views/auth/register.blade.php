@extends('layouts.web')
@section('content')
<div class="register-page-container flex items-center justify-center min-h-screen bg-gray-100 p-6" 
     style="background-image: url('{{asset("img/authBG.png")}}'); background-size: cover; background-position: center; background-attachment: fixed;">
    
    <!-- Glass Card -->
    <div style="position: relative; top: -50px" class="register-card w-full max-w-lg p-8 space-y-6 bg-white/10 backdrop-blur-lg rounded-2xl shadow-2xl border border-white/20 transform transition-all duration-500 hover:shadow-orange-400/50">
        
        <!-- Logo / Title -->
        <div class="text-center">
            <h1 class="text-4xl font-extrabold tracking-wide" style="font-family: 'Poppins', sans-serif; color: #ff7e00;">
                JB Commerce
            </h1>
            <p class="mt-2 text-gray-200 text-sm">
                Créez votre compte pour commencer
            </p>
        </div>

        <!-- Register Form -->
        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-white">Nom Complet</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                       class="mt-1 block w-full px-4 py-3 border border-gray-300/40 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400 bg-white/70 backdrop-blur-md" />
                <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-500 text-sm" />
            </div>

            <!-- Email -->
            {{-- <div>
                <label for="email" class="block text-sm font-medium text-white">Adresse E-mail</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                       class="mt-1 block w-full px-4 py-3 border border-gray-300/40 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400 bg-white/70 backdrop-blur-md" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-sm" />
            </div> --}}

            <!-- Phone -->
            <div>
                <label for="phone" class="block text-sm font-medium text-white">Téléphone</label>
                <input id="phone" type="tel" name="phone" value="{{ old('phone') }}"
                       class="mt-1 block w-full px-4 py-3 border border-gray-300/40 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400 bg-white/70 backdrop-blur-md" />
                <x-input-error :messages="$errors->get('phone')" class="mt-2 text-red-500 text-sm" />
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-white">Mot de Passe</label>
                <div class="relative">
                    <input id="password" type="password" name="password" required
                           class="mt-1 block w-full px-4 py-3 pr-12 border border-gray-300/40 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400 bg-white/70 backdrop-blur-md" />
                    <button type="button" 
                            onclick="togglePasswordVisibility('password', 'togglePasswordIcon')"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-orange-500 focus:outline-none mt-0.5">
                        <i id="togglePasswordIcon" class="fas fa-eye"></i>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-sm" />
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-white">Confirmer le Mot de Passe</label>
                <div class="relative">
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                           class="mt-1 block w-full px-4 py-3 pr-12 border border-gray-300/40 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400 bg-white/70 backdrop-blur-md" />
                    <button type="button" 
                            onclick="togglePasswordVisibility('password_confirmation', 'togglePasswordConfirmIcon')"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-orange-500 focus:outline-none mt-0.5">
                        <i id="togglePasswordConfirmIcon" class="fas fa-eye"></i>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-500 text-sm" />
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" 
                        class="w-full flex justify-center py-3 px-4 rounded-lg text-sm font-semibold text-white shadow-lg transform transition hover:scale-105"
                        style="background: linear-gradient(90deg, #ff7e00, #ff5400);">
                    🚀 S’inscrire
                </button>
            </div>
            
            <!-- Already Registered -->
            <div class="text-center text-sm mt-4 text-gray-200">
                <p>Déjà un compte ? 
                    <a href="{{ route('login') }}" class="font-semibold hover:underline" style="color: #ffae42;">
                        Connectez-vous ici
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>
<script src="https://cdn.tailwindcss.com"></script>
<script>
function togglePasswordVisibility(inputId, iconId) {
    const passwordInput = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
@endsection
