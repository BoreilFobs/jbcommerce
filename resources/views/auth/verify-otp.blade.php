<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Vérification OTP - JB Shop">
    <title>Vérification OTP - JB Shop</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('img/fabs.ico') }}">
    
    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/performance.css') }}">
    
    <style>
        .otp-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
            padding: 20px;
        }
        
        .otp-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            width: 100%;
            padding: 40px;
            animation: slideUp 0.5s ease;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .otp-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .otp-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }
        
        .otp-icon i {
            font-size: 40px;
            color: white;
        }
        
        .otp-title {
            font-size: 28px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 10px;
        }
        
        .otp-description {
            color: #718096;
            font-size: 15px;
            line-height: 1.6;
        }
        
        .otp-inputs {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin: 30px 0;
        }
        
        .otp-input {
            width: 60px;
            height: 60px;
            text-align: center;
            font-size: 24px;
            font-weight: 700;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            transition: all 0.3s;
            outline: none;
        }
        
        .otp-input:focus {
            border-color: #ff6b35;
            box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
            transform: scale(1.05);
        }
        
        .otp-input.filled {
            border-color: #ff6b35;
            background: #fff5f2;
        }
        
        .btn-verify {
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
            color: white;
            border: none;
            padding: 15px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 12px;
            width: 100%;
            transition: all 0.3s;
            margin-top: 20px;
        }
        
        .btn-verify:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(255, 107, 53, 0.3);
        }
        
        .btn-verify:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        .resend-section {
            text-align: center;
            margin-top: 30px;
            padding-top: 30px;
            border-top: 1px solid #e2e8f0;
        }
        
        .resend-text {
            color: #718096;
            font-size: 14px;
            margin-bottom: 10px;
        }
        
        .btn-resend {
            background: none;
            border: 2px solid #ff6b35;
            color: #ff6b35;
            padding: 10px 30px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-resend:hover {
            background: #ff6b35;
            color: white;
        }
        
        .timer {
            font-size: 14px;
            color: #ff6b35;
            font-weight: 600;
            margin-top: 10px;
        }
        
        .alert {
            border-radius: 12px;
            margin-bottom: 20px;
        }
        
        @media (max-width: 576px) {
            .otp-card {
                padding: 30px 20px;
            }
            
            .otp-input {
                width: 50px;
                height: 50px;
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="otp-container">
        <div class="otp-card">
            <div class="otp-header">
                <div class="otp-icon">
                    <i class="fab fa-whatsapp"></i>
                </div>
                <h1 class="otp-title">Vérification</h1>
                <p class="otp-description">
                    Entrez le code reçu sur WhatsApp
                </p>
            </div>
            
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                </div>
            @endif
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    @foreach ($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif
            
            <form method="POST" action="{{ route('verify.otp') }}" id="otpForm">
                @csrf
                
                <div class="otp-inputs">
                    <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric" id="otp1" autocomplete="off">
                    <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric" id="otp2" autocomplete="off">
                    <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric" id="otp3" autocomplete="off">
                    <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric" id="otp4" autocomplete="off">
                    <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric" id="otp5" autocomplete="off">
                    <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric" id="otp6" autocomplete="off">
                </div>
                
                <input type="hidden" name="otp" id="otpValue">
                
                <button type="submit" class="btn btn-verify" id="verifyBtn" disabled>
                    <i class="fas fa-check-circle me-2"></i>Vérifier le Code
                </button>
            </form>
            
            <div class="resend-section">
                <p class="resend-text">Vous n'avez pas reçu le code ?</p>
                <div id="timerSection">
                    <p class="timer">Renvoyer dans <span id="countdown">60</span> secondes</p>
                </div>
                <form method="POST" action="{{ route('resend.otp') }}" id="resendForm" style="display: none;">
                    @csrf
                    <button type="submit" class="btn btn-resend">
                        <i class="fas fa-redo me-2"></i>Renvoyer le Code
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        // Gestion des inputs OTP
        const otpInputs = document.querySelectorAll('.otp-input');
        const otpValue = document.getElementById('otpValue');
        const verifyBtn = document.getElementById('verifyBtn');
        
        otpInputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                const value = e.target.value;
                
                // Autoriser seulement les chiffres
                if (!/^\d*$/.test(value)) {
                    e.target.value = '';
                    return;
                }
                
                // Ajouter la classe filled
                if (value) {
                    e.target.classList.add('filled');
                    
                    // Passer au champ suivant
                    if (index < otpInputs.length - 1) {
                        otpInputs[index + 1].focus();
                    }
                } else {
                    e.target.classList.remove('filled');
                }
                
                // Vérifier si tous les champs sont remplis
                updateOtpValue();
            });
            
            input.addEventListener('keydown', (e) => {
                // Retour arrière
                if (e.key === 'Backspace' && !e.target.value && index > 0) {
                    otpInputs[index - 1].focus();
                }
                
                // Flèches gauche/droite
                if (e.key === 'ArrowLeft' && index > 0) {
                    otpInputs[index - 1].focus();
                }
                if (e.key === 'ArrowRight' && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
            });
            
            // Gérer le collage
            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const pastedData = e.clipboardData.getData('text').trim();
                
                if (/^\d{6}$/.test(pastedData)) {
                    pastedData.split('').forEach((char, i) => {
                        if (otpInputs[i]) {
                            otpInputs[i].value = char;
                            otpInputs[i].classList.add('filled');
                        }
                    });
                    updateOtpValue();
                    otpInputs[5].focus();
                }
            });
        });
        
        function updateOtpValue() {
            const otp = Array.from(otpInputs).map(input => input.value).join('');
            otpValue.value = otp;
            
            // Activer/désactiver le bouton
            if (otp.length === 6) {
                verifyBtn.disabled = false;
            } else {
                verifyBtn.disabled = true;
            }
        }
        
        // Focus automatique sur le premier champ
        otpInputs[0].focus();
        
        // Compte à rebours
        let countdown = 60;
        const countdownEl = document.getElementById('countdown');
        const timerSection = document.getElementById('timerSection');
        const resendForm = document.getElementById('resendForm');
        
        const timer = setInterval(() => {
            countdown--;
            countdownEl.textContent = countdown;
            
            if (countdown <= 0) {
                clearInterval(timer);
                timerSection.style.display = 'none';
                resendForm.style.display = 'block';
            }
        }, 1000);
    </script>
</body>
</html>
