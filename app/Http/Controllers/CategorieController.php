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
            'name' => "required"
        ]);
        Categorie::create([
            "name" => $request->name
        ]);
        return redirect("/categories")->with('success', 'Category created successfully');
    }

 public function update(Request $request, $id){
        $request->validate([
            'name' => "required"
        ]);
        Categorie::findOrFail($id)->update([
            "name" => $request->name
        ]);
        return redirect("/categories")->with('success', 'Category updated successfully');
    }

     public function updateF($id)
    {
        $category = Categorie::findOrFail($id);
        return view('category.update', compact('category'));

    }

    public function delete($id){
        Categorie::findOrFail($id)->delete();
        return redirect("/categories")->with('success', 'Category deleted successfully');
    }

}
