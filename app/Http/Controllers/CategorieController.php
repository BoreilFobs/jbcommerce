<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    public function index(){
        $categories = Categorie::all();
        return view('category.index', compact("categories"));
    }

    public function createF()
    {
        return view('category.create');
    }

    public function store(Request $request){
        $request->validate([
            'name' => "required|string|max:255|unique:categories,name",
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
        ], [
            'name.required' => 'Le nom de la catégorie est obligatoire.',
            'name.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'name.unique' => 'Ce nom de catégorie existe déjà.',
            'image.required' => 'L\'image de la catégorie est obligatoire.',
            'image.image' => 'Le fichier doit être une image.',
            'image.mimes' => 'Formats acceptés: jpeg, png, jpg, gif, webp.',
            'image.max' => 'L\'image ne peut pas dépasser 5Mo.',
        ]);

        $imagePath = $request->file('image')->store('category_image', 'public');
        
        Categorie::create([
            "name" => $request->name,
            "image_path" => $imagePath
        ]);
        
        return redirect("/categories")->with('success', 'Catégorie créée avec succès!');
    }

    public function update(Request $request, $id){
        $category = Categorie::findOrFail($id);
        
        $request->validate([
            'name' => "required|string|max:255|unique:categories,name," . $id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
        ], [
            'name.required' => 'Le nom de la catégorie est obligatoire.',
            'name.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'name.unique' => 'Ce nom de catégorie existe déjà.',
            'image.image' => 'Le fichier doit être une image.',
            'image.mimes' => 'Formats acceptés: jpeg, png, jpg, gif, webp.',
            'image.max' => 'L\'image ne peut pas dépasser 5Mo.',
        ]);

        $updateData = ['name' => $request->name];

        // Only update image if a new one is provided
        if ($request->hasFile('image')) {
            // Delete old image
            if ($category->image_path && \Storage::disk('public')->exists($category->image_path)) {
                \Storage::disk('public')->delete($category->image_path);
            }
            $updateData['image_path'] = $request->file('image')->store('category_image', 'public');
        }

        $category->update($updateData);
        
        return redirect("/categories")->with('success', 'Catégorie mise à jour avec succès!');
    }

    public function updateF($id)
    {
        $category = Categorie::findOrFail($id);
        return view('category.update', compact('category'));
    }

    public function delete($id){
        $category = Categorie::findOrFail($id);
        
        // Delete image
        if ($category->image_path && \Storage::disk('public')->exists($category->image_path)) {
            \Storage::disk('public')->delete($category->image_path);
        }
        
        $category->delete();
        return redirect("/categories")->with('success', 'Catégorie supprimée avec succès!');
    }
}
