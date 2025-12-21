<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Exception;

class WhatsAppService
{
    protected $apiUrl;
    protected $instanceName;
    protected $apiKey;

    public function __construct()
    {
        $this->apiUrl = config('services.evolution.api_url');
        $this->instanceName = config('services.evolution.instance_name');
        $this->apiKey = config('services.evolution.api_key');
    }

    /**
     * Envoyer un message texte via WhatsApp
     */
    public function sendTextMessage($phoneNumber, $message)
    {
        try {
            // Formater le numéro de téléphone (format international)
            $formattedNumber = $this->formatPhoneNumber($phoneNumber);

            $response = Http::withHeaders([
                'apikey' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post("{$this->apiUrl}/message/sendText/{$this->instanceName}", [
                'number' => $formattedNumber,
                'text' => $message,
                'delay' => 1200,
            ]);

            if ($response->successful()) {
                Log::info('WhatsApp message sent successfully', [
                    'phone' => $phoneNumber,
                    'response' => $response->json()
                ]);
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            Log::error('WhatsApp message failed', [
                'phone' => $phoneNumber,
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            return [
                'success' => false,
                'error' => 'Failed to send WhatsApp message',
                'details' => $response->body()
            ];

        } catch (Exception $e) {
            Log::error('WhatsApp service error', [
                'phone' => $phoneNumber,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Envoyer un code OTP (le code doit être fourni)
     */
    public function sendOTP($phoneNumber, $otpCode, $name = '')
    {
        try {
            // Utiliser le code OTP fourni
            $otp = $otpCode;

            // Préparer le message court et direct
            $message = "🔐 *JB Shop*\n\n"
                     . "Code de vérification :\n\n"
                     . "*{$otp}*\n\n"
                     . "Valide 10 minutes";

            // Envoyer le message
            $result = $this->sendTextMessage($phoneNumber, $message);

            if ($result['success']) {
                Log::info('OTP sent successfully', [
                    'phone' => $phoneNumber
                ]);

                return [
                    'success' => true,
                    'message' => 'Code OTP envoyé avec succès'
                ];
            }

            return $result;

        } catch (Exception $e) {
            Log::error('OTP generation error', [
                'phone' => $phoneNumber,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Vérifier un code OTP
     */
    public function verifyOTP($phoneNumber, $otp)
    {
        try {
            $cacheKey = "otp_{$phoneNumber}";
            $storedOTP = Cache::get($cacheKey);

            if (!$storedOTP) {
                return [
                    'success' => false,
                    'error' => 'Code expiré ou invalide'
                ];
            }

            if ($storedOTP !== $otp) {
                return [
                    'success' => false,
                    'error' => 'Code incorrect'
                ];
            }

            // Supprimer l'OTP du cache après vérification
            Cache::forget($cacheKey);

            Log::info('OTP verified successfully', ['phone' => $phoneNumber]);

            return [
                'success' => true,
                'message' => 'Code vérifié avec succès'
            ];

        } catch (Exception $e) {
            Log::error('OTP verification error', [
                'phone' => $phoneNumber,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Envoyer une notification de nouvelle commande
     */
    public function sendOrderNotification($order, $user)
    {
        try {
            $phoneNumber = $user->phone;
            
            // Calculer le nombre d'articles
            $totalItems = $order->items->sum('quantity');
            
            // Préparer le message de commande court
            $message = "🎉 *Commande Confirmée*\n\n"
                     . "Bonjour {$user->name},\n\n"
                     . "📦 Commande : *{$order->order_number}*\n"
                     . "🛍️ Articles : {$totalItems}\n"
                     . "💰 Total : *" . number_format($order->total_amount, 0, ',', ' ') . " FCFA*\n\n";

            // Ajouter les articles (max 3 pour garder le message court)
            $itemCount = min($order->items->count(), 3);
            foreach ($order->items->take($itemCount) as $index => $item) {
                $message .= "• {$item->product_name} (×{$item->quantity})\n";
            }
            
            if ($order->items->count() > 3) {
                $remaining = $order->items->count() - 3;
                $message .= "• ... et {$remaining} autre(s) article(s)\n";
            }

            $message .= "\n📍 Livraison : {$order->shipping_address}\n"
                     . "📞 Contact : {$order->shipping_phone}\n"
                     . "⏱️ Délai : 2-5 jours\n\n"
                     . "Merci pour votre confiance ! 🙏";

            return $this->sendTextMessage($phoneNumber, $message);

        } catch (Exception $e) {
            Log::error('Order notification error', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Envoyer une notification de changement de statut
     */
    public function sendOrderStatusUpdate($order, $user, $newStatus)
    {
        try {
            $phoneNumber = $user->phone;
            
            $statusMessages = [
                'pending' => '⏳ En attente',
                'confirmed' => '✅ Confirmée',
                'processing' => '📦 En préparation',
                'shipped' => '🚚 Expédiée',
                'delivered' => '✅ Livrée',
                'cancelled' => '❌ Annulée',
            ];

            $statusEmoji = [
                'pending' => '⏳',
                'confirmed' => '✅',
                'processing' => '📦',
                'shipped' => '🚚',
                'delivered' => '🎉',
                'cancelled' => '❌',
            ];

            $emoji = $statusEmoji[$newStatus] ?? '📋';
            $statusText = $statusMessages[$newStatus] ?? $newStatus;

            $message = "{$emoji} *JB Shop*\n\n"
                     . "Bonjour {$user->name},\n\n";

            // Messages personnalisés selon le statut
            if ($newStatus === 'confirmed') {
                $message .= "Votre commande *{$order->order_number}* a été confirmée !\n\n"
                         . "Nous préparons vos articles. ⏱️";
            } elseif ($newStatus === 'processing') {
                $message .= "Votre commande *{$order->order_number}* est en cours de préparation.\n\n"
                         . "Elle sera bientôt expédiée ! 📦";
            } elseif ($newStatus === 'shipped') {
                $message .= "📦 *Votre colis a été expédié !*\n\n"
                         . "Commande : *{$order->order_number}*\n";
                if ($order->tracking_number) {
                    $message .= "Suivi : {$order->tracking_number}\n";
                }
                $message .= "\nLivraison dans 2-3 jours. 🚚";
            } elseif ($newStatus === 'delivered') {
                $message .= "🎉 *Votre colis a été livré !*\n\n"
                         . "Commande : *{$order->order_number}*\n\n"
                         . "Merci pour votre confiance !\n"
                         . "Laissez-nous un avis ⭐";
            } elseif ($newStatus === 'cancelled') {
                $message .= "Votre commande *{$order->order_number}* a été annulée.\n\n";
                if ($order->cancelled_reason) {
                    $message .= "Raison : {$order->cancelled_reason}\n\n";
                }
                $message .= "Questions ? 📞 +237-682-252-932";
            } else {
                $message .= "Statut de votre commande *{$order->order_number}* :\n\n"
                         . "*{$statusText}*";
            }

            return $this->sendTextMessage($phoneNumber, $message);

        } catch (Exception $e) {
            Log::error('Status update notification error', [
                'order_id' => $order->id,
                'status' => $newStatus,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Envoyer un message de bienvenue
     */
    public function sendWelcomeMessage($user)
    {
        try {
            $phoneNumber = $user->phone;

            $message = "🎉 *Bienvenue {$user->name} !*\n\n"
                     . "Votre compte JB Shop est créé.\n\n"
                     . "🛍️ Boutique : " . config('app.url') . "/shop\n"
                     . "📞 Support : +237-657-528-859\n\n"
                     . "Bon shopping ! 🛒";

            return $this->sendTextMessage($phoneNumber, $message);

        } catch (Exception $e) {
            Log::error('Welcome message error', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Formater le numéro de téléphone au format international
     */
    protected function formatPhoneNumber($phoneNumber)
    {
        // Supprimer tous les caractères non numériques
        $cleaned = preg_replace('/[^0-9]/', '', $phoneNumber);

        // Si le numéro commence par 237 (code Cameroun), ajouter +
        if (substr($cleaned, 0, 3) === '237') {
            return '+' . $cleaned;
        }

        // Si le numéro commence par 6 (format local camerounais)
        if (substr($cleaned, 0, 1) === '6' && strlen($cleaned) === 9) {
            return '+237' . $cleaned;
        }

        // Si le numéro commence déjà par +
        if (substr($phoneNumber, 0, 1) === '+') {
            return $phoneNumber;
        }

        // Par défaut, ajouter le code pays Cameroun
        return '+237' . $cleaned;
    }

    /**
     * Vérifier le statut de l'instance Evolution
     */
    public function checkInstanceStatus()
    {
        try {
            $response = Http::withHeaders([
                'apikey' => $this->apiKey,
            ])->get("{$this->apiUrl}/instance/connectionState/{$this->instanceName}");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            return [
                'success' => false,
                'error' => 'Instance not reachable'
            ];

        } catch (Exception $e) {
            Log::error('Instance status check error', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
