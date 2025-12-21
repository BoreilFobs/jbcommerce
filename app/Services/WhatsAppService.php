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
     * Générer et envoyer un code OTP
     */
    public function sendOTP($phoneNumber, $name = '')
    {
        try {
            // Générer un code OTP à 6 chiffres
            $otp = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
            
            // Stocker l'OTP dans le cache pour 10 minutes
            $cacheKey = "otp_{$phoneNumber}";
            Cache::put($cacheKey, $otp, now()->addMinutes(10));

            // Préparer le message
            $greeting = $name ? "Bonjour {$name}," : "Bonjour,";
            $message = "🔐 *JB Shop - Code de Vérification*\n\n"
                     . "{$greeting}\n\n"
                     . "Votre code de vérification est :\n\n"
                     . "*{$otp}*\n\n"
                     . "⏱️ Ce code est valide pendant 10 minutes.\n\n"
                     . "⚠️ Ne partagez jamais ce code avec qui que ce soit.\n\n"
                     . "Si vous n'avez pas demandé ce code, ignorez ce message.\n\n"
                     . "Merci,\n"
                     . "L'équipe JB Shop 🛍️";

            // Envoyer le message
            $result = $this->sendTextMessage($phoneNumber, $message);

            if ($result['success']) {
                Log::info('OTP sent successfully', [
                    'phone' => $phoneNumber,
                    'otp' => $otp // À retirer en production
                ]);

                return [
                    'success' => true,
                    'otp' => $otp, // Retourné pour tests, à retirer en production
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
            
            // Préparer le message de commande
            $message = "🎉 *Commande Confirmée - JB Shop*\n\n"
                     . "Bonjour {$user->name},\n\n"
                     . "Votre commande a été confirmée avec succès !\n\n"
                     . "📦 *Détails de la Commande*\n"
                     . "━━━━━━━━━━━━━━━━━━━━\n"
                     . "🔖 Numéro : *{$order->order_number}*\n"
                     . "📅 Date : " . $order->created_at->format('d/m/Y à H:i') . "\n"
                     . "💰 Montant : *" . number_format($order->total_amount, 0, ',', ' ') . " FCFA*\n"
                     . "📍 Adresse : {$order->shipping_address}\n\n"
                     . "📋 *Articles Commandés*\n"
                     . "━━━━━━━━━━━━━━━━━━━━\n";

            // Ajouter les articles
            foreach ($order->items as $index => $item) {
                $message .= ($index + 1) . ". {$item->product_name}\n"
                         . "   × {$item->quantity} - " . number_format($item->price * $item->quantity, 0, ',', ' ') . " FCFA\n";
            }

            $message .= "\n🚚 *Livraison*\n"
                     . "━━━━━━━━━━━━━━━━━━━━\n"
                     . "📞 Contact : {$order->phone}\n"
                     . "⏱️ Délai estimé : 2-5 jours ouvrables\n\n"
                     . "📱 Suivez votre commande :\n"
                     . config('app.url') . "/orders/{$order->id}\n\n"
                     . "Merci pour votre confiance ! 🙏\n"
                     . "L'équipe JB Shop";

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
                'pending' => '⏳ En attente de confirmation',
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

            $message = "{$emoji} *Mise à Jour Commande - JB Shop*\n\n"
                     . "Bonjour {$user->name},\n\n"
                     . "Le statut de votre commande a été mis à jour :\n\n"
                     . "🔖 Numéro : *{$order->order_number}*\n"
                     . "📊 Nouveau statut : *{$statusText}*\n\n";

            // Messages personnalisés selon le statut
            if ($newStatus === 'shipped') {
                $trackingNumber = $order->tracking_number ?? 'N/A';
                $message .= "📦 Numéro de suivi : *{$trackingNumber}*\n\n"
                         . "Votre commande est en route ! 🚚\n"
                         . "Vous devriez la recevoir dans 2-3 jours.\n\n";
            } elseif ($newStatus === 'delivered') {
                $message .= "🎉 Votre commande a été livrée !\n\n"
                         . "Nous espérons que vous êtes satisfait(e) de vos achats.\n\n"
                         . "N'hésitez pas à nous laisser un avis ! ⭐\n\n";
            } elseif ($newStatus === 'cancelled') {
                $message .= "Votre commande a été annulée.\n\n"
                         . "Si vous avez des questions, contactez-nous :\n"
                         . "📞 +237-657-528-859\n\n";
            }

            $message .= "📱 Voir les détails :\n"
                     . config('app.url') . "/orders/{$order->id}\n\n"
                     . "Merci,\n"
                     . "L'équipe JB Shop 🛍️";

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

            $message = "🎉 *Bienvenue sur JB Shop !*\n\n"
                     . "Bonjour {$user->name},\n\n"
                     . "Merci de nous avoir rejoint ! 🙏\n\n"
                     . "Nous sommes ravis de vous compter parmi nous.\n\n"
                     . "🛍️ *Découvrez nos produits :*\n"
                     . config('app.url') . "/shop\n\n"
                     . "💡 *Astuce :* Installez notre application pour :\n"
                     . "• Un accès plus rapide ⚡\n"
                     . "• Des notifications de commandes 🔔\n"
                     . "• Mode hors ligne 📱\n\n"
                     . "Besoin d'aide ? Contactez-nous :\n"
                     . "📞 +237-657-528-859\n"
                     . "📧 brayeljunior8@gmail.com\n\n"
                     . "Bon shopping ! 🛒\n"
                     . "L'équipe JB Shop";

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
