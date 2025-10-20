<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Show checkout page with cart items
     */
    public function index()
    {
        // Get user's cart items
        $cartItems = Cart::where('user_id', Auth::id())
            ->with('offer')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Votre panier est vide.');
        }

        // Calculate totals
        $subtotal = 0;
        foreach ($cartItems as $item) {
            if ($item->offer) {
                $price = $item->offer->discount_percentage > 0 
                    ? $item->offer->discounted_price 
                    : $item->offer->price;
                $subtotal += $price * $item->quantity;
            }
        }

        // Shipping cost (can be calculated based on region/weight)
        $shippingCost = 2000; // Default 2000 FCFA

        $total = $subtotal + $shippingCost;

        return view('checkout.index', compact('cartItems', 'subtotal', 'shippingCost', 'total'));
    }

    /**
     * Process checkout and create order
     */
    public function process(Request $request)
    {
        // Validate checkout form
        $validated = $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:20',
            'shipping_email' => 'required|email|max:255',
            'shipping_address' => 'required|string|max:500',
            'shipping_city' => 'required|string|max:100',
            'shipping_region' => 'required|string|max:100',
            'shipping_postal_code' => 'nullable|string|max:20',
            'payment_method' => 'required|in:mobile_money_mtn,mobile_money_orange,cash_on_delivery,bank_transfer',
            'payment_phone' => 'nullable|string|max:20',
            'customer_notes' => 'nullable|string|max:1000',
            'terms' => 'accepted',
        ]);

        // Get user's cart items
        $cartItems = Cart::where('user_id', Auth::id())
            ->with('offer')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Votre panier est vide.');
        }

        // Check stock availability
        foreach ($cartItems as $item) {
            if (!$item->offer || $item->offer->quantity < $item->quantity) {
                return redirect()->route('checkout.index')
                    ->with('error', 'Stock insuffisant pour: ' . ($item->offer->name ?? 'Produit'));
            }
        }

        DB::beginTransaction();
        
        try {
            // Calculate totals
            $subtotal = 0;
            $totalDiscount = 0;
            
            foreach ($cartItems as $item) {
                if ($item->offer) {
                    $price = $item->offer->discount_percentage > 0 
                        ? $item->offer->discounted_price 
                        : $item->offer->price;
                    
                    $itemTotal = $price * $item->quantity;
                    $subtotal += $itemTotal;
                    
                    if ($item->offer->discount_percentage > 0) {
                        $originalPrice = $item->offer->price * $item->quantity;
                        $totalDiscount += ($originalPrice - $itemTotal);
                    }
                }
            }

            // Shipping cost
            $shippingCost = 2000; // Can be dynamic based on region
            $totalAmount = $subtotal + $shippingCost;

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => Order::generateOrderNumber(),
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'discount_amount' => $totalDiscount,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'payment_method' => $validated['payment_method'],
                'payment_status' => $validated['payment_method'] === 'cash_on_delivery' ? 'pending' : 'pending',
                'payment_phone' => $validated['payment_phone'] ?? null,
                'shipping_name' => $validated['shipping_name'],
                'shipping_phone' => $validated['shipping_phone'],
                'shipping_email' => $validated['shipping_email'],
                'shipping_address' => $validated['shipping_address'],
                'shipping_city' => $validated['shipping_city'],
                'shipping_region' => $validated['shipping_region'],
                'shipping_postal_code' => $validated['shipping_postal_code'] ?? null,
                'customer_notes' => $validated['customer_notes'] ?? null,
            ]);

            // Create order items and update stock
            foreach ($cartItems as $item) {
                if ($item->offer) {
                    $unitPrice = $item->offer->discount_percentage > 0 
                        ? $item->offer->discounted_price 
                        : $item->offer->price;
                    
                    $itemSubtotal = $unitPrice * $item->quantity;
                    
                    OrderItem::create([
                        'order_id' => $order->id,
                        'offer_id' => $item->offer_id,
                        'product_name' => $item->offer->name,
                        'quantity' => $item->quantity,
                        'unit_price' => $unitPrice,
                        'discount_percentage' => $item->offer->discount_percentage ?? 0,
                        'discount_amount' => $item->offer->discount_percentage > 0 
                            ? ($item->offer->price - $unitPrice) * $item->quantity 
                            : 0,
                        'subtotal' => $itemSubtotal,
                    ]);

                    // Update product stock
                    $item->offer->decrement('quantity', $item->quantity);
                }
            }

            // Clear cart
            Cart::where('user_id', Auth::id())->delete();

            DB::commit();

            // TODO: Send confirmation email here
            // Mail::to($order->shipping_email)->send(new OrderConfirmation($order));

            return redirect()
                ->route('orders.confirmation', $order->id)
                ->with('success', 'Votre commande a été passée avec succès!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->route('checkout.index')
                ->with('error', 'Une erreur est survenue lors du traitement de votre commande. Veuillez réessayer.')
                ->withInput();
        }
    }
}
