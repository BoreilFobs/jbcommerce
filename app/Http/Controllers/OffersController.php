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
            'description' => "required",
            'images' => 'required|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $offer = new offers();
        $offer->name = $request->name;
        $offer->category = $request->category;
        $offer->price = $request->price;
        $offer->quantity = $request->quantity;
        $offer->description = $request->description;
        $offer->save();

        $img_urls = [];
        if ($request->hasFile('images')) {
            $productDir = 'storage/offer_img/product' . $offer->id;
            foreach ($request->file('images') as $image) {
                $img_name = uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path($productDir), $img_name);
                $img_urls[] = $img_name;
            }
            $offer->images = json_encode($img_urls);
            $offer->save();
        }
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
            'description' => "required",
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:3048',
        ]);

        $offer = offers::findOrFail($id);
        $offer->name = $request->name;
        $offer->category = $request->category;
        $offer->price = $request->price;
        $offer->quantity = $request->quantity;
        $offer->description = $request->description;

        if ($request->hasFile('images')) {
            $productDir = 'storage/offer_img/product' . $offer->id;
            // Remove old images
            if ($offer->images) {
                $oldImages = json_decode($offer->images, true);
                foreach ($oldImages as $img) {
                    $imgPath = public_path($productDir . '/' . $img);
                    if (file_exists($imgPath)) unlink($imgPath);
                }
            }
            $img_urls = [];
            foreach ($request->file('images') as $image) {
                $img_name = uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path($productDir), $img_name);
                $img_urls[] = $img_name;
            }
            $offer->images = json_encode($img_urls);
        }
        $offer->save();
        return redirect('/offers')->with('success', 'Offer updated successfully');
    }

    public function show(Request $request, $id){
        $offer = offers::findOrFail($id);
        $offers = offers::all();
        $categories = Categorie::all();
        return view("single", compact('offer', 'categories', 'offers'));
    }

    public function delete($id)
    {
        $offer = offers::findOrFail($id);

        // Delete all images for this product
        if ($offer->images) {
            $productDir = public_path('storage/offer_img/product' . $offer->id);
            $imgs = json_decode($offer->images, true);
            foreach ($imgs as $img) {
                $imgPath = $productDir . '/' . $img;
                if (file_exists($imgPath)) unlink($imgPath);
            }
            // Optionally remove the directory if empty
            @rmdir($productDir);
        }
        $offer->delete();
        return redirect('/offers')->with('success', 'Offer deleted successfully');
    }
}
