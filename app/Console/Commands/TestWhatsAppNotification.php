<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\WhatsAppService;

class TestWhatsAppNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whatsapp:test {phone?} {--check-status} {--send-test} {--send-otp}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test WhatsApp integration with Evolution API';

    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        parent::__construct();
        $this->whatsappService = $whatsappService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Testing WhatsApp Integration...');
        $this->newLine();

        // Option 1: Check API Status
        if ($this->option('check-status')) {
            return $this->checkStatus();
        }

        // Option 2: Send test message
        if ($this->option('send-test')) {
            return $this->sendTestMessage();
        }

        // Option 3: Send OTP
        if ($this->option('send-otp')) {
            return $this->sendTestOtp();
        }

        // Default: Show menu
        $this->showMenu();
    }

    protected function showMenu()
    {
        $this->info('📱 WhatsApp Test Menu');
        $this->newLine();

        $choice = $this->choice(
            'Que voulez-vous tester ?',
            [
                '1' => 'Vérifier le statut de l\'API',
                '2' => 'Envoyer un message test',
                '3' => 'Envoyer un OTP test',
                '4' => 'Tout tester',
            ],
            '1'
        );

        switch ($choice) {
            case '1':
                $this->checkStatus();
                break;
            case '2':
                $this->sendTestMessage();
                break;
            case '3':
                $this->sendTestOtp();
                break;
            case '4':
                $this->checkStatus();
                $this->newLine(2);
                $this->sendTestMessage();
                $this->newLine(2);
                $this->sendTestOtp();
                break;
        }
    }

    protected function checkStatus()
    {
        $this->info('🔌 Vérification du statut de l\'instance Evolution API...');
        
        try {
            $result = $this->whatsappService->checkInstanceStatus();

            if ($result['success']) {
                $this->info('✅ Instance connectée avec succès !');
                $this->newLine();
                
                if (isset($result['data'])) {
                    $this->table(
                        ['Propriété', 'Valeur'],
                        collect($result['data'])->map(function ($value, $key) {
                            return [$key, is_array($value) ? json_encode($value) : $value];
                        })->toArray()
                    );
                }
            } else {
                $this->error('❌ Erreur de connexion à l\'instance');
                $this->line('Détails: ' . ($result['error'] ?? 'Erreur inconnue'));
            }
        } catch (\Exception $e) {
            $this->error('❌ Exception: ' . $e->getMessage());
        }

        return 0;
    }

    protected function sendTestMessage()
    {
        $phone = $this->argument('phone') ?? $this->ask('Entrez le numéro de téléphone (format: +237657528859)');

        if (!$phone) {
            $this->error('❌ Numéro de téléphone requis');
            return 1;
        }

        $this->info("📤 Envoi d'un message test à {$phone}...");

        $message = "🧪 *Message Test - JB Shop*\n\n"
                 . "Ceci est un message de test du système de notifications WhatsApp.\n\n"
                 . "✅ Si vous recevez ce message, l'intégration fonctionne correctement !\n\n"
                 . "📅 Date: " . now()->format('d/m/Y à H:i') . "\n\n"
                 . "L'équipe JB Shop 🛍️";

        try {
            $result = $this->whatsappService->sendTextMessage($phone, $message);

            if ($result['success']) {
                $this->info('✅ Message envoyé avec succès !');
                $this->newLine();
                $this->line('Vérifiez votre WhatsApp pour confirmer la réception.');
            } else {
                $this->error('❌ Échec de l\'envoi du message');
                $this->line('Détails: ' . ($result['error'] ?? 'Erreur inconnue'));
            }
        } catch (\Exception $e) {
            $this->error('❌ Exception: ' . $e->getMessage());
        }

        return 0;
    }

    protected function sendTestOtp()
    {
        $phone = $this->argument('phone') ?? $this->ask('Entrez le numéro de téléphone (format: +237657528859)');

        if (!$phone) {
            $this->error('❌ Numéro de téléphone requis');
            return 1;
        }

        $name = $this->ask('Entrez le nom du destinataire', 'Utilisateur Test');

        $this->info("📤 Envoi d'un OTP test à {$phone}...");

        try {
            $result = $this->whatsappService->sendOTP($phone, $name);

            if ($result['success']) {
                $this->info('✅ OTP envoyé avec succès !');
                $this->newLine();
                
                if (isset($result['otp'])) {
                    $this->warn('🔐 Code OTP: ' . $result['otp']);
                    $this->line('(À des fins de test uniquement - ne pas afficher en production)');
                }
                
                $this->newLine();
                $this->line('Vérifiez votre WhatsApp pour le code de vérification.');
            } else {
                $this->error('❌ Échec de l\'envoi de l\'OTP');
                $this->line('Détails: ' . ($result['error'] ?? 'Erreur inconnue'));
            }
        } catch (\Exception $e) {
            $this->error('❌ Exception: ' . $e->getMessage());
        }

        return 0;
    }
}

