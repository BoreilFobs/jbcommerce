<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\User;
use App\Services\WhatsAppService;
use Illuminate\Console\Command;

class TestOrderNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:test-notifications {phone} {--status=shipped}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test order notifications via WhatsApp';

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
        $phone = $this->argument('phone');
        $status = $this->option('status');

        $this->info("🧪 Test des notifications WhatsApp");
        $this->info("📞 Numéro : {$phone}");
        $this->info("📊 Statut : {$status}");
        $this->newLine();

        // Créer un utilisateur de test
        $user = new User([
            'name' => 'Test User',
            'phone' => $phone,
            'email' => 'test@jbshop.com'
        ]);

        // Créer une commande de test
        $order = new Order([
            'order_number' => 'TEST-' . rand(1000, 9999),
            'total_amount' => 25000,
            'status' => $status,
            'shipping_address' => 'Douala, Cameroun',
            'shipping_phone' => $phone,
            'tracking_number' => 'TRK-' . rand(10000, 99999),
            'cancelled_reason' => 'Test de notification'
        ]);

        // Simuler des articles
        $order->setRelation('items', collect([
            (object)[
                'product_name' => 'iPhone 13 Pro',
                'quantity' => 1,
                'price' => 15000
            ],
            (object)[
                'product_name' => 'AirPods Pro',
                'quantity' => 2,
                'price' => 5000
            ]
        ]));

        $this->info("📝 Test 1: Notification de nouvelle commande");
        $result = $this->whatsappService->sendOrderNotification($order, $user);
        
        if ($result['success']) {
            $this->info("✅ Notification de commande envoyée avec succès");
        } else {
            $this->error("❌ Erreur : " . ($result['error'] ?? 'Inconnue'));
        }

        $this->newLine();
        
        $this->info("📝 Test 2: Notification de changement de statut ({$status})");
        $result = $this->whatsappService->sendOrderStatusUpdate($order, $user, $status);
        
        if ($result['success']) {
            $this->info("✅ Notification de statut envoyée avec succès");
        } else {
            $this->error("❌ Erreur : " . ($result['error'] ?? 'Inconnue'));
        }

        $this->newLine();
        $this->info("🎉 Tests terminés ! Vérifiez WhatsApp au {$phone}");
        
        return Command::SUCCESS;
    }
}
