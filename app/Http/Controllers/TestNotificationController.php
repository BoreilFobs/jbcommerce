<?php

namespace App\Http\Controllers;

use App\Services\OrderNotificationService;
use App\Services\FirebaseService;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TestNotificationController extends Controller
{
    protected $orderNotificationService;
    protected $firebaseService;

    public function __construct(
        OrderNotificationService $orderNotificationService,
        FirebaseService $firebaseService
    ) {
        $this->orderNotificationService = $orderNotificationService;
        $this->firebaseService = $firebaseService;
    }

    /**
     * Display the test notification page
     */
    public function index()
    {
        return view('test-notification');
    }

    /**
     * Send test notification
     */
    public function send(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Check if user has FCM token
            if (!$user->fcm_token) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucun token FCM enregistré. Veuillez activer les notifications dans votre navigateur.'
                ], 400);
            }

            $type = $request->input('type', 'simple');
            
            switch ($type) {
                case 'simple':
                    $result = $this->sendSimpleNotification($user);
                    break;
                    
                case 'order':
                    $result = $this->sendOrderNotification($user);
                    break;
                    
                case 'status':
                    $status = $request->input('status');
                    if (!$status) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Veuillez sélectionner un statut.'
                        ], 400);
                    }
                    $result = $this->sendStatusNotification($user, $status);
                    break;
                    
                case 'promotion':
                    $title = $request->input('promo_title', '🎁 Offre Spéciale!');
                    $body = $request->input('promo_body', 'Profitez de nos promotions exclusives!');
                    $result = $this->sendPromotionNotification($user, $title, $body);
                    break;
                    
                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'Type de notification invalide.'
                    ], 400);
            }

            if ($result) {
                Log::info("Test notification sent successfully", [
                    'user_id' => $user->id,
                    'type' => $type,
                    'fcm_token' => substr($user->fcm_token, 0, 20) . '...'
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Notification envoyée avec succès! Vérifiez votre navigateur.'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Échec de l\'envoi de la notification. Vérifiez les logs.'
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error("Error sending test notification: " . $e->getMessage(), [
                'user_id' => Auth::id(),
                'type' => $request->input('type'),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send simple test notification
     */
    private function sendSimpleNotification($user)
    {
        return $this->firebaseService->sendToDevice(
            $user->fcm_token,
            '✅ Test Notification',
            'Ceci est une notification de test. Si vous voyez ce message, le système fonctionne correctement!',
            [
                'type' => 'test',
                'timestamp' => now()->toISOString(),
                'click_action' => url('/test-notif')
            ]
        );
    }

    /**
     * Send order test notification
     */
    private function sendOrderNotification($user)
    {
        // Get user's last order or create mock data
        $order = Order::where('user_id', $user->id)->latest()->first();
        
        if ($order) {
            return $this->orderNotificationService->notifyOrderPlaced($order);
        } else {
            // Send mock order notification
            return $this->firebaseService->sendToDevice(
                $user->fcm_token,
                '🎉 Nouvelle Commande #TEST-' . rand(1000, 9999),
                'Votre commande de ' . number_format(99.99, 2) . ' DH a été enregistrée avec succès.',
                [
                    'type' => 'order_placed',
                    'order_id' => 'TEST-' . rand(1000, 9999),
                    'order_total' => '99.99',
                    'click_action' => url('/orders')
                ]
            );
        }
    }

    /**
     * Send status change test notification
     */
    private function sendStatusNotification($user, $status)
    {
        $statusMessages = [
            'pending' => [
                'emoji' => '⏳',
                'title' => 'Commande en Attente',
                'body' => 'Votre commande est en attente de traitement.'
            ],
            'processing' => [
                'emoji' => '📦',
                'title' => 'Commande en Traitement',
                'body' => 'Votre commande est en cours de préparation.'
            ],
            'shipped' => [
                'emoji' => '🚚',
                'title' => 'Commande Expédiée',
                'body' => 'Votre commande a été expédiée et est en route!'
            ],
            'delivered' => [
                'emoji' => '✅',
                'title' => 'Commande Livrée',
                'body' => 'Votre commande a été livrée avec succès. Merci pour votre achat!'
            ]
        ];

        $message = $statusMessages[$status] ?? $statusMessages['pending'];
        
        return $this->firebaseService->sendToDevice(
            $user->fcm_token,
            $message['emoji'] . ' ' . $message['title'] . ' #TEST-' . rand(1000, 9999),
            $message['body'],
            [
                'type' => 'order_status_changed',
                'order_id' => 'TEST-' . rand(1000, 9999),
                'status' => $status,
                'old_status' => 'pending',
                'click_action' => url('/orders')
            ]
        );
    }

    /**
     * Send promotion test notification
     */
    private function sendPromotionNotification($user, $title, $body)
    {
        return $this->orderNotificationService->notifyPromotion(
            $user,
            $title,
            $body,
            [
                'type' => 'promotion',
                'promo_code' => 'TEST' . rand(100, 999),
                'discount' => '20',
                'click_action' => url('/shop')
            ]
        );
    }
}
