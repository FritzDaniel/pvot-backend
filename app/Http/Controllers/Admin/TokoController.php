<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shops;
use Illuminate\Http\Request;

class TokoController extends Controller
{
    public function detail($id)
    {
        $data = Shops::where('id','=',$id)->first();
        return view('admin.toko.detail',compact('data'));
    }

    public function edit($id)
    {
        $data = Shops::where('id','=',$id)->first();
        return view('admin.toko.edit',compact('data'));
    }

    public function updateToko(Request $request,$id)
    {
        $this->validate($request,[
            'url_tokopedia' => 'required',
            'url_shopee' => 'required'
        ]);

        $update = Shops::where('id','=',$id)->first();
        $update->url_tokopedia = $request['url_tokopedia'];
        $update->url_shopee = $request['url_shopee'];
        $update->status = "Active";
        $update->update();

        return redirect()->route('admin.toko')->with('message','Successfully update the Toko');
    }
}
