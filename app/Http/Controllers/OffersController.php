<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\offers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OffersController extends Controller
{
    /**
     * Display a listing of offers with search and filters
     */
    public function index(Request $request)
    {
        $query = offers::query();

        // Search functionality
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sort options
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Paginate results
        $offers = $query->paginate(12)->withQueryString();
        
        // Get categories for filter
        $categories = Categorie::all();

        return view("offer.index", compact('offers', 'categories'));
    }

    /**
     * Show the form for creating a new offer
     */
    public function createF()
    {
        $categories = Categorie::all();
        return view('offer.create', compact('categories'));
    }

    /**
     * Store a newly created offer
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => "required|string|max:255",
            'price' => "required|numeric|min:0",
            'category' => "required|string",
            'brand' => "nullable|string|max:255",
            'quantity' => "required|integer|min:0",
            'discount_percentage' => "nullable|integer|min:0|max:100",
            'description' => "required|string",
            'specifications' => "nullable|array",
            'specifications.*' => "nullable|string",
            'featured' => "nullable|boolean",
            'status' => "nullable|in:active,inactive,out_of_stock",
            'images' => 'required|array|min:1|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        $offer = new offers();
        $offer->name = $validated['name'];
        $offer->category = $validated['category'];
        $offer->brand = $validated['brand'] ?? null;
        $offer->sku = $this->generateSKU($validated['name']);
        $offer->price = $validated['price'];
        $offer->discount_percentage = $validated['discount_percentage'] ?? 0;
        $offer->quantity = $validated['quantity'];
        $offer->description = $validated['description'];
        $offer->specifications = $validated['specifications'] ?? null;
        $offer->featured = $request->has('featured') ? true : false;
        $offer->status = $validated['status'] ?? 'active';
        $offer->meta_title = $validated['meta_title'] ?? $validated['name'];
        $offer->meta_description = $validated['meta_description'] ?? null;
        $offer->save();

        // Handle image uploads
        $img_urls = [];
        if ($request->hasFile('images')) {
            $productDir = 'storage/offer_img/product' . $offer->id;
            
            // Create directory if it doesn't exist
            if (!file_exists(public_path($productDir))) {
                mkdir(public_path($productDir), 0755, true);
            }

            foreach ($request->file('images') as $image) {
                $img_name = uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path($productDir), $img_name);
                $img_urls[] = $img_name;
            }
            $offer->images = $img_urls; // Will be automatically cast to JSON
            $offer->save();
        }

        return redirect()->route('offer.index')->with('success', 'Produit créé avec succès!');
    }

    /**
     * Show the form for editing the specified offer
     */
    public function updateF($id)
    {
        $offer = offers::findOrFail($id);
        $categories = Categorie::all();
        return view('offer.update', compact('offer', 'categories'));
    }

    /**
     * Update the specified offer
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => "required|string|max:255",
            'price' => "required|numeric|min:0",
            'category' => "required|string",
            'brand' => "nullable|string|max:255",
            'quantity' => "required|integer|min:0",
            'discount_percentage' => "nullable|integer|min:0|max:100",
            'description' => "required|string",
            'specifications' => "nullable|array",
            'specifications.*' => "nullable|string",
            'featured' => "nullable|boolean",
            'status' => "nullable|in:active,inactive,out_of_stock",
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:3048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        $offer = offers::findOrFail($id);
        $offer->name = $validated['name'];
        $offer->category = $validated['category'];
        $offer->brand = $validated['brand'] ?? null;
        $offer->price = $validated['price'];
        $offer->discount_percentage = $validated['discount_percentage'] ?? 0;
        $offer->quantity = $validated['quantity'];
        $offer->description = $validated['description'];
        $offer->specifications = $validated['specifications'] ?? null;
        $offer->featured = $request->has('featured') ? true : false;
        $offer->status = $validated['status'] ?? 'active';
        $offer->meta_title = $validated['meta_title'] ?? $validated['name'];
        $offer->meta_description = $validated['meta_description'] ?? null;

        // Handle image uploads if new images are provided
        if ($request->hasFile('images')) {
            $productDir = 'storage/offer_img/product' . $offer->id;
            
            // Remove old images
            if ($offer->images) {
                $oldImages = is_array($offer->images) ? $offer->images : json_decode($offer->images, true);
                foreach ($oldImages as $img) {
                    $imgPath = public_path($productDir . '/' . $img);
                    if (file_exists($imgPath)) {
                        unlink($imgPath);
                    }
                }
            }

            // Create directory if it doesn't exist
            if (!file_exists(public_path($productDir))) {
                mkdir(public_path($productDir), 0755, true);
            }

            // Upload new images
            $img_urls = [];
            foreach ($request->file('images') as $image) {
                $img_name = uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path($productDir), $img_name);
                $img_urls[] = $img_name;
            }
            $offer->images = $img_urls;
        }

        $offer->save();
        
        return redirect()->route('offer.index')->with('success', 'Produit mis à jour avec succès!');
    }

    /**
     * Display the specified product (for customer view)
     */
    public function show(Request $request, $id)
    {
        $offer = offers::findOrFail($id);
        $offer->incrementViews(); // Track product views
        
        $offers = offers::active()->inStock()->limit(8)->get();
        $categories = Categorie::all();
        
        return view("single", compact('offer', 'categories', 'offers'));
    }

    /**
     * Remove the specified offer
     */
    public function delete($id)
    {
        $offer = offers::findOrFail($id);

        // Delete all images for this product
        if ($offer->images) {
            $productDir = public_path('storage/offer_img/product' . $offer->id);
            $imgs = is_array($offer->images) ? $offer->images : json_decode($offer->images, true);
            
            if (is_array($imgs)) {
                foreach ($imgs as $img) {
                    $imgPath = $productDir . '/' . $img;
                    if (file_exists($imgPath)) {
                        unlink($imgPath);
                    }
                }
            }
            
            // Optionally remove the directory if empty
            if (file_exists($productDir) && count(scandir($productDir)) == 2) {
                @rmdir($productDir);
            }
        }

        $offer->delete();
        
        return redirect()->route('offer.index')->with('success', 'Produit supprimé avec succès!');
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured($id)
    {
        $offer = offers::findOrFail($id);
        $offer->featured = !$offer->featured;
        $offer->save();

        return back()->with('success', 'Statut vedette mis à jour!');
    }

    /**
     * Bulk delete offers
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:offers,id'
        ]);

        foreach ($request->ids as $id) {
            $this->delete($id);
        }

        return redirect()->route('offer.index')->with('success', 'Produits supprimés avec succès!');
    }

    /**
     * Generate unique SKU for product
     */
    private function generateSKU($name)
    {
        $base = strtoupper(Str::substr(Str::slug($name, ''), 0, 6));
        $unique = strtoupper(Str::random(4));
        return $base . '-' . $unique;
    }
}
