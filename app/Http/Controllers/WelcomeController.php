<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\offers;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index(Request $request)
    {
        $categories = Categorie::all();
        $query = offers::query()->where('status', 'active');

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('category', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('brand', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Brand filter
        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }

        // Price range filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Featured filter
        if ($request->filled('featured') && $request->featured == '1') {
            $query->where('featured', true);
        }

        // New arrivals filter
        if ($request->filled('new_arrivals') && $request->new_arrivals == '1') {
            $query->where('created_at', '>=', now()->subMonth());
        }

        // On sale filter
        if ($request->filled('on_sale') && $request->on_sale == '1') {
            $query->where('discount_percentage', '>', 0);
        }

        // In stock filter
        if ($request->filled('in_stock') && $request->in_stock == '1') {
            $query->where('quantity', '>', 0);
        }

        // Sorting
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        
        switch ($sortBy) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'popularity':
                $query->orderBy('views', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        // Pagination
        $offers = $query->paginate(9)->withQueryString();

        // Get all brands for filter
        $brands = offers::whereNotNull('brand')
            ->where('brand', '!=', '')
            ->distinct()
            ->pluck('brand');

        // Get price range
        $minPrice = offers::min('price');
        $maxPrice = offers::max('price');

        // Count products by category
        $categoryCounts = [];
        foreach ($categories as $category) {
            $categoryCounts[$category->name] = offers::where('category', $category->name)
                ->where('status', 'active')
                ->count();
        }

        if ($request->path() === '/') {
            return view("welcome", compact("offers", 'categories', 'brands', 'minPrice', 'maxPrice', 'categoryCounts'));
        } else {
            return view("store", compact("offers", 'categories', 'brands', 'minPrice', 'maxPrice', 'categoryCounts'));
        }
    }

    public function search(Request $request)
    {
        return $this->index($request);
    }

    // New arrivals filter (offers < 1 month old)
    public function newArrivals()
    {
        $oneMonthAgo = now()->subMonth();
        $offers = offers::where('created_at', '>=', $oneMonthAgo)
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($offers);
    }
}
