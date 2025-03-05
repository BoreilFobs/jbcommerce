<?php

namespace App\Http\Controllers;

use App\Models\wishes;
use Illuminate\Http\Request;

class WishesController extends Controller
{
    //
    public function index($id)
    {
        $wishes = wishes::where('user_id', $id)->get();
        return view('wishlist', compact("wishes"));
    }
    public function store($Oid, $Uid)
    {
        wishes::create([
            'user_id' => $Uid,
            'offer_id' => $Oid,
        ]);
        return redirect('/wish-list/' . $Uid);
    }

    public function delete($id)
    {
        $wish = wishes::findOrFail($id);
        $wish->delete();
        return redirect()->back();
    }
}
