<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\offers;
use Illuminate\Container\Attributes\Storage;
use Illuminate\Http\Request;

class OffersController extends Controller
{
    //
    public function index()
    {
        $offers = offers::all();
        return view("offer.index", compact('offers'));
    }
    public function createF()
    {
        $categories = Categorie::all();
        return view('offer.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => "required",
            'price' => "required|numeric",
            'category' => "required",
            'quantity' => "required",
        ]);

        if ($request->hasFile('image')) { // Ensure file exists
            $image = $request->file('image'); // Get file

            // Store file in 'public/offers_image' (storage/app/public/offers_image)
            $img_path = $image->store('offers_image', 'public');

            // Get the correct public URL
            $img_url = asset('storage/' . $img_path);
        }

        offers::create([
            'name' => $request->name,
            'category' => $request->category,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'image_path' => $img_url,
        ]);
        return redirect('/offers')->with('success', 'Offer created successfully');
    }
    public function updateF($id)
    {
        $offer = offers::findOrFail($id);
        $categories = Categorie::all();
        return view('offer.update', compact('offer', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => "required",
            'price' => "required|numeric",
            'category' => "required",
            'quantity' => "required",
        ]);

        if ($request->hasFile('image')) { // Ensure file exists
            $image = $request->file('image'); // Get file

            // Store file in 'public/offers_image' (storage/app/public/offers_image)
            $img_path = $image->store('offers_image', 'public');

            // Get the correct public URL
            $img_url = asset('storage/' . $img_path);
        }
        if ($request->file('emp_pic')) {
            offers::findOrFail($id)->update([
                'name' => $request->name,
                'category' => $request->category,
                'price' => $request->price,
                'quantity' => $request->quantity,
                'image_path' => $img_url,
            ]);
        } else {
            offers::findOrFail($id)->update([
                'name' => $request->name,
                'category' => $request->category,
                'price' => $request->price,
                'instock' => $request->instock,
                // 'image_path' => $img_url,
            ]);
        }


        return redirect('/offers')->with('success', 'Offer updated successfully');
    }

    public function delete($id)
    {
        $offer = offers::findOrFail($id);

        if (file_exists($offer->image_path)) {
            unlink($offer->image_path);
        }
        $offer->delete();
        return redirect('/offers')->with('success', 'Offer deleted successfully');
    }
}
