<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Messaging\WebPushConfig;
use Illuminate\Support\Facades\Log;

class FirebaseService
{
    protected $messaging;

    public function __construct()
    {
        try {
            $factory = (new Factory)->withServiceAccount(storage_path('app/firebase_credentials.json'));
            $this->messaging = $factory->createMessaging();
        } catch (\Exception $e) {
            Log::error('Firebase initialization error: ' . $e->getMessage());
        }
    }

    /**
     * Send notification to a single device
     */
    public function sendToDevice($token, $title, $body, $data = [])
    {
        if (!$token || !$this->messaging) {
            return false;
        }

        try {
            $notification = Notification::create($title, $body);
            
            $message = CloudMessage::withTarget('token', $token)
                ->withNotification($notification)
                ->withData($data)
                ->withAndroidConfig($this->getAndroidConfig())
                ->withWebPushConfig($this->getWebPushConfig());

            $this->messaging->send($message);
            
            Log::info('FCM notification sent successfully', [
                'token' => substr($token, 0, 20) . '...',
                'title' => $title
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('FCM send error: ' . $e->getMessage(), [
                'token' => substr($token, 0, 20) . '...',
                'title' => $title
            ]);
            return false;
        }
    }

    /**
     * Send notification to multiple devices
     */
    public function sendToMultipleDevices($tokens, $title, $body, $data = [])
    {
        if (!$tokens || empty($tokens) || !$this->messaging) {
            return false;
        }

        $successCount = 0;
        $notification = Notification::create($title, $body);

        foreach ($tokens as $token) {
            try {
                $message = CloudMessage::withTarget('token', $token)
                    ->withNotification($notification)
                    ->withData($data)
                    ->withAndroidConfig($this->getAndroidConfig())
                    ->withWebPushConfig($this->getWebPushConfig());

                $this->messaging->send($message);
                $successCount++;
            } catch (\Exception $e) {
                Log::error('FCM multicast error for token: ' . substr($token, 0, 20) . '...', [
                    'error' => $e->getMessage()
                ]);
            }
        }

        Log::info("FCM notifications sent: $successCount/" . count($tokens));
        return $successCount > 0;
    }

    /**
     * Android specific configuration
     */
    private function getAndroidConfig()
    {
        return AndroidConfig::fromArray([
            'priority' => 'high',
            'notification' => [
                'sound' => 'default',
                'color' => '#f28b00',
                'icon' => 'ic_notification',
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            ],
        ]);
    }

    /**
     * Web Push specific configuration
     */
    private function getWebPushConfig()
    {
        return WebPushConfig::fromArray([
            'notification' => [
                'icon' => '/img/logo.svg',
                'badge' => '/img/logo.svg',
                'vibrate' => [200, 100, 200],
                'requireInteraction' => true,
            ],
            'fcm_options' => [
                'link' => url('/orders'),
            ],
        ]);
    }

    /**
     * Validate FCM token
     */
    public function validateToken($token)
    {
        if (!$token || !$this->messaging) {
            return false;
        }

        try {
            // Try to send a test message
            $message = CloudMessage::withTarget('token', $token)
                ->withData(['test' => 'validation']);
            
            $this->messaging->validate($message);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
