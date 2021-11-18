<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function message()
    {
        $data = Message::orderBy('created_at','DESC')->get();
        return view('admin.message.index',compact('data'));
    }

    public function create()
    {
        return view('admin.message.create');
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'title' => 'required',
            'content' => 'required'
        ]);

        $store = new Message();
        $store->title = $request['title'];
        $store->content = $request['content'];
        $store->save();

        return redirect()->route('admin.message')->with('message','Message has created and sent successfully');
    }
}
