<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\OtpVerification;
use App\Services\WhatsAppService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Carbon\Carbon;

class RegisteredUserController extends Controller
{
    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request - Step 1: Send OTP
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:12', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Générer un code OTP
        $otpCode = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        // Invalider les anciens OTPs pour ce numéro
        OtpVerification::where('phone', $request->phone)
            ->where('type', 'registration')
            ->where('verified', false)
            ->delete();

        // Enregistrer l'OTP dans la base de données
        OtpVerification::create([
            'phone' => $request->phone,
            'code' => $otpCode,
            'type' => 'registration',
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        // Envoyer l'OTP via WhatsApp avec le code généré
        $this->whatsappService->sendOTP($request->phone, $otpCode, $request->name);

        // Stocker les données dans la session pour l'étape suivante
        Session::put('registration_data', [
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => $request->password,
        ]);

        // Rediriger vers la page de vérification OTP
        return redirect()->route('verify.otp.form')
            ->with('success', 'Un code de vérification a été envoyé à votre numéro WhatsApp.');
    }

    /**
     * Afficher le formulaire de vérification OTP
     */
    public function showVerifyForm()
    {
        if (!Session::has('registration_data')) {
            return redirect()->route('register')->with('error', 'Session expirée. Veuillez recommencer.');
        }

        return view('auth.verify-otp');
    }

    /**
     * Vérifier l'OTP et créer le compte - Step 2: Verify OTP
     */
    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $registrationData = Session::get('registration_data');

        if (!$registrationData) {
            return redirect()->route('register')
                ->with('error', 'Session expirée. Veuillez recommencer l\'inscription.');
        }

        // Vérifier l'OTP
        $otpVerification = OtpVerification::where('phone', $registrationData['phone'])
            ->where('code', $request->otp)
            ->where('type', 'registration')
            ->valid()
            ->first();

        if (!$otpVerification) {
            return back()->withErrors(['otp' => 'Code OTP invalide ou expiré.']);
        }

        // Incrémenter les tentatives
        $otpVerification->incrementAttempts();

        // Vérifier le nombre de tentatives (max 5)
        if ($otpVerification->attempts > 5) {
            return back()->withErrors(['otp' => 'Trop de tentatives. Veuillez recommencer l\'inscription.']);
        }

        // Marquer l'OTP comme vérifié
        $otpVerification->markAsVerified();

        // Créer l'utilisateur
        $user = User::create([
            'name' => $registrationData['name'],
            'phone' => $registrationData['phone'],
            'password' => Hash::make($registrationData['password']),
            'phone_verified_at' => Carbon::now(),
        ]);

        // Envoyer le message de bienvenue
        $this->whatsappService->sendWelcomeMessage($user);

        event(new Registered($user));

        // Connecter l'utilisateur
        Auth::login($user);

        // Supprimer les données de session
        Session::forget('registration_data');

        return redirect('/')->with('success', 'Compte créé avec succès ! Bienvenue sur JB Shop.');
    }

    /**
     * Renvoyer un nouveau code OTP
     */
    public function resendOtp(Request $request): RedirectResponse
    {
        $registrationData = Session::get('registration_data');

        if (!$registrationData) {
            return redirect()->route('register')
                ->with('error', 'Session expirée. Veuillez recommencer l\'inscription.');
        }

        // Supprimer les anciens OTPs non vérifiés
        OtpVerification::where('phone', $registrationData['phone'])
            ->where('type', 'registration')
            ->where('verified', false)
            ->delete();

        // Générer un nouveau code OTP
        $otpCode = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        // Enregistrer le nouveau OTP
        OtpVerification::create([
            'phone' => $registrationData['phone'],
            'code' => $otpCode,
            'type' => 'registration',
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        // Envoyer le nouveau OTP via WhatsApp avec le nouveau code
        $this->whatsappService->sendOTP($registrationData['phone'], $otpCode, $registrationData['name']);

        return back()->with('success', 'Un nouveau code de vérification a été envoyé.');
    }
}
