<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the user's orders
     */
    public function index()
    {
        $orders = Auth::user()->orders()
            ->with('items.offer')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Display the specified order
     */
    public function show($id)
    {
        $order = Auth::user()->orders()
            ->with(['items.offer', 'user'])
            ->findOrFail($id);

        return view('orders.show', compact('order'));
    }

    /**
     * Display order confirmation page
     */
    public function confirmation($id)
    {
        $order = Auth::user()->orders()
            ->with(['items.offer', 'user'])
            ->findOrFail($id);

        return view('orders.confirmation', compact('order'));
    }

    /**
     * Cancel an order (if allowed)
     */
    public function cancel(Request $request, $id)
    {
        $order = Auth::user()->orders()->findOrFail($id);

        // Check if order can be cancelled
        if (!$order->canBeCancelled()) {
            return redirect()
                ->route('orders.show', $id)
                ->with('error', 'Cette commande ne peut pas être annulée.');
        }

        $order->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancelled_reason' => $request->input('reason', 'Annulé par le client'),
        ]);

        // Restore product quantities
        foreach ($order->items as $item) {
            if ($item->offer) {
                $item->offer->increment('quantity', $item->quantity);
            }
        }

        return redirect()
            ->route('orders.show', $id)
            ->with('success', 'Votre commande a été annulée avec succès.');
    }

    /**
     * Track order status (public view with order number)
     */
    public function track(Request $request)
    {
        $orderNumber = $request->input('order_number');
        
        if (!$orderNumber) {
            return view('orders.track');
        }

        $order = Order::where('order_number', $orderNumber)
            ->with(['items.offer', 'user'])
            ->first();

        if (!$order) {
            return view('orders.track')
                ->with('error', 'Commande introuvable. Veuillez vérifier le numéro de commande.');
        }

        // Check if user is authorized to view this order
        if (!Auth::check() || Auth::id() !== $order->user_id) {
            // Only show limited information for non-authenticated users
            return view('orders.track-public', compact('order'));
        }

        return view('orders.track', compact('order'));
    }
}
