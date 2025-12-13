<?php

namespace App\Observers;

use App\Models\Order;
use App\Services\OrderNotificationService;
use Illuminate\Support\Facades\Log;

class OrderObserver
{
    protected $notificationService;

    public function __construct(OrderNotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        try {
            // Send notification when order is placed
            $this->notificationService->notifyOrderPlaced($order);
            Log::info('Order placement notification sent for order: ' . $order->id);
        } catch (\Exception $e) {
            Log::error('Error sending order placement notification: ' . $e->getMessage());
        }
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        try {
            // Check if status has changed
            if ($order->isDirty('status')) {
                $oldStatus = $order->getOriginal('status');
                $newStatus = $order->status;

                // Send notification about status change
                $this->notificationService->notifyOrderStatusChanged($order, $oldStatus, $newStatus);
                Log::info("Order status notification sent for order {$order->id}: {$oldStatus} -> {$newStatus}");

                // If delivered, send special delivery notification
                if ($newStatus === 'delivered') {
                    $this->notificationService->notifyOrderDelivered($order);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error sending order update notification: ' . $e->getMessage());
        }
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        try {
            // Send cancellation notification
            $this->notificationService->notifyOrderCancelled($order);
            Log::info('Order cancellation notification sent for order: ' . $order->id);
        } catch (\Exception $e) {
            Log::error('Error sending order cancellation notification: ' . $e->getMessage());
        }
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        //
    }
}
