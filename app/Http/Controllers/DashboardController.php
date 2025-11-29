<?php

namespace App\Http\Controllers;

use App\Models\offers;
use App\Models\Cart;
use App\Models\wishes;
use App\Models\Categorie;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with analytics
     */
    public function index()
    {
        // Basic Statistics
        $totalProducts = offers::count();
        $activeProducts = offers::where('status', 'active')->count();
        $totalUsers = User::where('name', '!=', 'admin')->count();
        $totalCategories = Categorie::count();
        
        // Stock Alerts
        $lowStockProducts = offers::where('quantity', '<=', 5)
            ->where('quantity', '>', 0)
            ->orderBy('quantity', 'asc')
            ->limit(5)
            ->get();
        
        $outOfStockProducts = offers::where('quantity', 0)
            ->orWhere('status', 'out_of_stock')
            ->count();
        
        // Total inventory value
        $totalInventoryValue = offers::sum(DB::raw('price * quantity'));
        
        // Most viewed products
        $mostViewedProducts = offers::orderBy('views', 'desc')
            ->limit(5)
            ->get();
        
        // Products by category
        $productsByCategory = offers::select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->get();
        
        // Recent products
        $recentProducts = offers::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Featured products count
        $featuredProductsCount = offers::where('featured', true)->count();
        
        // Cart statistics
        $totalCartItems = Cart::count();
        $activeCartsCount = Cart::distinct('user_id')->count();
        
        // Wishlist statistics
        $totalWishlistItems = wishes::count();
        
        // Category statistics with product counts
        $categoryStats = Categorie::withCount('offers')->get();
        
        // Products added this month
        $productsThisMonth = offers::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        // Products added this week
        $productsThisWeek = offers::whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->count();
        
        // Average product price
        $averagePrice = offers::avg('price');
        
        // Products with discounts
        $discountedProducts = offers::where('discount_percentage', '>', 0)->count();
        
        // Status distribution
        $statusDistribution = offers::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();
        
        // Latest 5 Orders
        $latestOrders = Order::with(['user', 'items'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Order statistics
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $processingOrders = Order::where('status', 'processing')->count();
        $completedOrders = Order::where('status', 'delivered')->count();
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');
        $pendingRevenue = Order::where('payment_status', 'pending')->sum('total_amount');

        return view('dashboard', compact(
            'totalProducts',
            'activeProducts',
            'totalUsers',
            'totalCategories',
            'lowStockProducts',
            'outOfStockProducts',
            'totalInventoryValue',
            'mostViewedProducts',
            'productsByCategory',
            'recentProducts',
            'featuredProductsCount',
            'totalCartItems',
            'activeCartsCount',
            'totalWishlistItems',
            'categoryStats',
            'productsThisMonth',
            'productsThisWeek',
            'averagePrice',
            'discountedProducts',
            'statusDistribution',
            'latestOrders',
            'totalOrders',
            'pendingOrders',
            'processingOrders',
            'completedOrders',
            'totalRevenue',
            'pendingRevenue'
        ));
    }
}
