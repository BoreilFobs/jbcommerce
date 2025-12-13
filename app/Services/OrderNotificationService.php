<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class OrderNotificationService
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    /**
     * Notify user about order placement
     */
    public function notifyOrderPlaced(Order $order)
    {
        $user = $order->user;
        
        if (!$user || !$user->fcm_token) {
            return false;
        }

        $title = '🎉 Commande Confirmée!';
        $body = "Votre commande #{$order->id} a été enregistrée avec succès. Montant: {$order->total_amount} FCFA";
        
        $data = [
            'type' => 'order_placed',
            'order_id' => (string)$order->id,
            'order_status' => $order->status,
            'total' => (string)$order->total_amount,
            'click_action' => 'ORDER_DETAILS',
        ];

        return $this->firebaseService->sendToDevice($user->fcm_token, $title, $body, $data);
    }

    /**
     * Notify user about order status change
     */
    public function notifyOrderStatusChanged(Order $order, $oldStatus, $newStatus)
    {
        $user = $order->user;
        
        if (!$user || !$user->fcm_token) {
            return false;
        }

        $statusMessages = [
            'pending' => [
                'title' => '⏳ Commande en Attente',
                'body' => "Votre commande #{$order->id} est en attente de traitement."
            ],
            'processing' => [
                'title' => '🔄 Commande en Traitement',
                'body' => "Votre commande #{$order->id} est en cours de préparation."
            ],
            'shipped' => [
                'title' => '📦 Commande Expédiée',
                'body' => "Votre commande #{$order->id} a été expédiée et sera bientôt livrée."
            ],
            'delivered' => [
                'title' => '✅ Commande Livrée',
                'body' => "Votre commande #{$order->id} a été livrée avec succès. Merci de votre confiance!"
            ],
            'cancelled' => [
                'title' => '❌ Commande Annulée',
                'body' => "Votre commande #{$order->id} a été annulée."
            ],
        ];

        $message = $statusMessages[$newStatus] ?? [
            'title' => '📢 Mise à Jour de Commande',
            'body' => "Le statut de votre commande #{$order->id} a été mis à jour."
        ];

        $data = [
            'type' => 'order_status_changed',
            'order_id' => (string)$order->id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'total' => (string)$order->total_amount,
            'click_action' => 'ORDER_DETAILS',
        ];

        return $this->firebaseService->sendToDevice($user->fcm_token, $message['title'], $message['body'], $data);
    }

    /**
     * Notify user about order cancellation
     */
    public function notifyOrderCancelled(Order $order, $reason = null)
    {
        $user = $order->user;
        
        if (!$user || !$user->fcm_token) {
            return false;
        }

        $title = '❌ Commande Annulée';
        $body = $reason 
            ? "Votre commande #{$order->id} a été annulée. Raison: {$reason}"
            : "Votre commande #{$order->id} a été annulée.";
        
        $data = [
            'type' => 'order_cancelled',
            'order_id' => (string)$order->id,
            'reason' => $reason ?? '',
            'click_action' => 'ORDER_DETAILS',
        ];

        return $this->firebaseService->sendToDevice($user->fcm_token, $title, $body, $data);
    }

    /**
     * Notify user about delivery
     */
    public function notifyOrderDelivered(Order $order)
    {
        $user = $order->user;
        
        if (!$user || !$user->fcm_token) {
            return false;
        }

        $title = '✅ Livraison Confirmée';
        $body = "Votre commande #{$order->id} a été livrée. Merci d'avoir choisi JB Shop!";
        
        $data = [
            'type' => 'order_delivered',
            'order_id' => (string)$order->id,
            'click_action' => 'ORDER_DETAILS',
        ];

        return $this->firebaseService->sendToDevice($user->fcm_token, $title, $body, $data);
    }

    /**
     * Send promotional notification
     */
    public function notifyPromotion($user, $title, $body, $data = [])
    {
        if (!$user || !$user->fcm_token) {
            return false;
        }

        $data = array_merge($data, [
            'type' => 'promotion',
            'click_action' => 'STORE',
        ]);

        return $this->firebaseService->sendToDevice($user->fcm_token, $title, $body, $data);
    }

    /**
     * Notify all users (broadcast)
     */
    public function notifyAllUsers($title, $body, $data = [])
    {
        $users = User::whereNotNull('fcm_token')->get();
        $tokens = $users->pluck('fcm_token')->toArray();

        if (empty($tokens)) {
            return false;
        }

        return $this->firebaseService->sendToMultipleDevices($tokens, $title, $body, $data);
    }
}
