<?php

namespace App\Http\Controllers;

use App\Models\cart;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;

class CartController extends Controller
{
    //
    public function index($id)
    {
        $carts = cart::where("user_id", $id)->get();
        return view("cart", compact('carts'));
    }
    public function store($Uid, $Oid)
    {
        cart::create([
            'offer_id' => $Uid,
            'user_id' => $Oid,
        ]);
        return redirect('/cart/' . $Oid);
    }
    public function delete($id)
    {
        $wish = cart::findOrFail($id);
        $wish->delete();
        return redirect()->back();
    }
    public function redirect(){
        return redirect()->route("login");
    }
}
