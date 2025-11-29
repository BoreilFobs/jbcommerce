<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of all orders (Admin)
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search by order number or customer name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        // Sort
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $orders = $query->paginate(20);

        // Get statistics
        $stats = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'confirmed' => Order::where('status', 'confirmed')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total_amount'),
            'pending_payment' => Order::where('payment_status', 'pending')->sum('total_amount'),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    /**
     * Display the specified order (Admin)
     */
    public function show($id)
    {
        $order = Order::with(['user', 'items.offer'])
            ->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $order = Order::findOrFail($id);
        
        $oldStatus = $order->status;
        $newStatus = $request->status;

        $order->update([
            'status' => $newStatus,
            'admin_notes' => $request->admin_notes,
        ]);

        // Update timestamps based on status
        if ($newStatus === 'shipped' && !$order->shipped_at) {
            $order->update(['shipped_at' => now()]);
        }
        
        if ($newStatus === 'delivered' && !$order->delivered_at) {
            $order->update(['delivered_at' => now()]);
        }
        
        if ($newStatus === 'cancelled' && !$order->cancelled_at) {
            $order->update([
                'cancelled_at' => now(),
                'cancelled_reason' => $request->admin_notes ?? 'Annulé par l\'administrateur',
            ]);
            
            // Restore product quantities
            foreach ($order->items as $item) {
                if ($item->offer) {
                    $item->offer->increment('quantity', $item->quantity);
                }
            }
        }

        return redirect()
            ->route('admin.orders.show', $id)
            ->with('success', 'Le statut de la commande a été mis à jour avec succès.');
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus(Request $request, $id)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'payment_reference' => 'nullable|string|max:255',
        ]);

        $order = Order::findOrFail($id);
        
        $updateData = [
            'payment_status' => $request->payment_status,
        ];

        if ($request->filled('payment_reference')) {
            $updateData['payment_reference'] = $request->payment_reference;
        }

        if ($request->payment_status === 'paid' && !$order->paid_at) {
            $updateData['paid_at'] = now();
        }

        $order->update($updateData);

        return redirect()
            ->route('admin.orders.show', $id)
            ->with('success', 'Le statut de paiement a été mis à jour avec succès.');
    }

    /**
     * Update tracking information
     */
    public function updateTracking(Request $request, $id)
    {
        $request->validate([
            'tracking_number' => 'required|string|max:255',
        ]);

        $order = Order::findOrFail($id);
        
        $order->update([
            'tracking_number' => $request->tracking_number,
        ]);

        // If order is not shipped yet, mark as shipped
        if ($order->status !== 'shipped' && $order->status !== 'delivered') {
            $order->update([
                'status' => 'shipped',
                'shipped_at' => now(),
            ]);
        }

        return redirect()
            ->route('admin.orders.show', $id)
            ->with('success', 'Le numéro de suivi a été mis à jour avec succès.');
    }

    /**
     * Delete/Cancel an order (Admin only)
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);

        // Only allow deletion of cancelled or very old orders
        if (!in_array($order->status, ['cancelled', 'delivered'])) {
            return redirect()
                ->route('admin.orders.index')
                ->with('error', 'Seules les commandes annulées ou livrées peuvent être supprimées.');
        }

        // Restore quantities if not already done
        if ($order->status === 'cancelled') {
            foreach ($order->items as $item) {
                if ($item->offer) {
                    $item->offer->increment('quantity', $item->quantity);
                }
            }
        }

        $order->delete();

        return redirect()
            ->route('admin.orders.index')
            ->with('success', 'La commande a été supprimée avec succès.');
    }

    /**
     * Generate invoice (PDF or print view)
     */
    public function invoice($id)
    {
        $order = Order::with(['user', 'items.offer'])
            ->findOrFail($id);

        return view('admin.orders.invoice', compact('order'));
    }
    
    /**
     * Bulk update order status
     */
    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id',
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
        ]);

        $orderIds = $request->order_ids;
        $newStatus = $request->status;
        $count = 0;

        foreach ($orderIds as $orderId) {
            $order = Order::find($orderId);
            if ($order) {
                $order->update(['status' => $newStatus]);
                
                // Update timestamps based on status
                if ($newStatus === 'shipped' && !$order->shipped_at) {
                    $order->update(['shipped_at' => now()]);
                }
                
                if ($newStatus === 'delivered' && !$order->delivered_at) {
                    $order->update(['delivered_at' => now()]);
                }
                
                if ($newStatus === 'cancelled' && !$order->cancelled_at) {
                    $order->update([
                        'cancelled_at' => now(),
                        'cancelled_reason' => 'Annulé en masse par l\'administrateur',
                    ]);
                    
                    // Restore product quantities
                    foreach ($order->items as $item) {
                        if ($item->offer) {
                            $item->offer->increment('quantity', $item->quantity);
                        }
                    }
                }
                
                $count++;
            }
        }

        return redirect()
            ->route('admin.orders.index')
            ->with('success', "{$count} commande(s) mise(s) à jour avec succès.");
    }
}
