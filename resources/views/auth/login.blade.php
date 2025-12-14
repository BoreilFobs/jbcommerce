@extends('layouts.web')
@section('content')
<div class="login-page-container flex items-center justify-center min-h-screen bg-gray-100 p-6" 
     data-bg="{{asset('img/authBG.png')}}" 
     style="background-size: cover; background-position: center; background-attachment: fixed;">
    
    <!-- Glass Card -->
    <div style="position: relative; top: -50px" class="login-card w-full max-w-md p-8 space-y-6 bg-white/10 backdrop-blur-lg rounded-2xl shadow-2xl border border-white/20 transform transition-all duration-500 hover:shadow-orange-400/50">
        
        <!-- Logo / Title -->
        <div class="text-center">
            <h1 class="text-4xl font-extrabold tracking-wide" style="font-family: 'Poppins', sans-serif; color: #ff7e00;">
                JB Commerce
            </h1>
            <p class="mt-2 text-gray-200 text-sm">
                Connectez-vous à votre compte pour continuer
            </p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4 text-center p-2 rounded" 
            :status="session('status')" 
            style="background-color: rgba(255, 243, 205, 0.8); color: #856404; border: 1px solid #ffeeba;" />

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Email -->
            <div>
                <label for="phone" class="block text-sm font-medium text-white">
                    Telephone
                </label>
                <input id="phone" 
                       class="mt-1 block w-full px-4 py-3 border border-gray-300/40 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400 bg-white/70 backdrop-blur-md" 
                       type="phone" 
                       name="phone" 
                       value="{{ old('phone') }}" 
                       required 
                       autofocus 
                       autocomplete="username" />
                <x-input-error :messages="$errors->get('phone')" class="mt-2 text-red-500 text-sm" />
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-white">
                    Mot de Passe
                </label>
                <div class="relative">
                    <input id="password" 
                           class="mt-1 block w-full px-4 py-3 pr-12 border border-gray-300/40 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400 bg-white/70 backdrop-blur-md" 
                           type="password" 
                           name="password" 
                           required 
                           autocomplete="current-password" />
                    <button type="button" 
                            onclick="togglePasswordVisibility('password', 'togglePasswordIcon')"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-orange-500 focus:outline-none mt-0.5">
                        <i id="togglePasswordIcon" class="fas fa-eye"></i>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-sm" />
            </div>

            <!-- Remember & Forgot -->
            <div class="flex items-center justify-between text-sm">
                <div class="flex items-center">
                    <input id="remember_me" type="checkbox" name="remember" 
                           class="h-4 w-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500">
                    <label for="remember_me" class="ml-2 text-white">Se souvenir de moi</label>
                </div>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" 
                       class="font-medium hover:underline" 
                       style="color: #ff7e00;">
                        Mot de passe oublié ?
                    </a>
                @endif
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" 
                        class="w-full flex justify-center py-3 px-4 rounded-lg text-sm font-semibold text-white shadow-lg transform transition hover:scale-105"
                        style="background: linear-gradient(90deg, #ff7e00, #ff5400);">
                    🚀 Se Connecter
                </button>
            </div>
            
            <!-- Register Link -->
            <div class="text-center text-sm mt-4 text-gray-200">
                <p>Pas encore de compte ? 
                    <a href="{{ route('register') }}" class="font-semibold hover:underline" style="color: #ffae42;">
                        Inscrivez-vous ici
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
