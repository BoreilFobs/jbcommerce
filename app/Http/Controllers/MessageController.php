<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(){
        $messages = Message::all();
        return view("message.index", compact("messages"));
    }
    public function contact(){
        $messages = Message::all();
        return view("contact");
    }
    public function store(Request $request){
        $request->validate(([
            'name' => 'required',
            'email' => 'required',
            'message' => 'required',
        ]));

        Message::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'object' => $request->object,
            'message' => $request->message,
        ]);
        return redirect("/contact");
    }
}
