<div class="login-page-container flex items-center justify-center min-h-screen bg-gray-100 p-4" style="background-image: url('{{ asset('assets/images/background-login.jpg') }}'); background-size: cover; background-position: center; background-attachment: fixed;">
    
    <div class="login-card w-full max-w-md p-8 space-y-6 bg-white rounded-lg shadow-2xl transform transition-all duration-500 hover:shadow-orange-400/50" style="--tw-shadow-color: #f97316; box-shadow: 0 20px 25px -5px rgba(249, 115, 22, 0.3), 0 8px 10px -6px rgba(249, 115, 22, 0.3); border-top: 5px solid #ff7e00;">
        
        <div class="text-center">
            <h1 class="text-4xl font-extrabold" style="font-family: cursive; color: #ff7e00;">
                ElectroSphere
            </h1>
            <p class="mt-2 text-gray-600">
                Connectez-vous à votre compte.
            </p>
        </div>

        <x-auth-session-status class="mb-4 text-center p-2 rounded" :status="session('status')" style="background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba;" />

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">
                    Adresse E-mail
                </label>
                <input id="email" 
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-1 focus:border-orange-500"
                       style="border-color: #f97316;"
                       type="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       autofocus 
                       autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">
                    Mot de Passe
                </label>
                <input id="password" 
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-1 focus:border-orange-500"
                       style="border-color: #f97316;"
                       type="password"
                       name="password"
                       required 
                       autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="flex items-center justify-between">
                
                <div class="flex items-center">
                    <input id="remember_me" type="checkbox" name="remember" class="h-4 w-4 text-orange-600 border-gray-300 rounded" style="color: #ff7e00; border-color: #ff7e00; box-shadow: none !important;">
                    <label for="remember_me" class="ml-2 block text-sm text-gray-900">
                        Se souvenir de moi
                    </label>
                </div>

                @if (Route::has('password.request'))
                    <a class="text-sm font-medium hover:underline focus:outline-none focus:ring-2 focus:ring-offset-2 rounded-md" 
                       href="{{ route('password.request') }}" 
                       style="color: #ff7e00; focus-ring-color: #ff7e00;">
                        Mot de passe oublié ?
                    </a>
                @endif
            </div>

            <div>
                <button type="submit" 
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-500 hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2"
                        style="background-color: #ff7e00; border-color: #ff7e00; focus-ring-color: #ff7e00;">
                    Se Connecter
                </button>
            </div>
            
            <div class="text-center text-sm mt-4">
                <p>Pas encore de compte ? 
                    <a href="{{ route('register') }}" class="font-medium hover:underline" style="color: #ff7e00;">
                        Inscrivez-vous ici
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>