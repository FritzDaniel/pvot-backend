<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shops;
use App\Models\UserToko;
use Illuminate\Http\Request;

class TokoController extends Controller
{
    public function detail($id)
    {
        $data = UserToko::where('id','=',$id)->first();
        return view('admin.toko.detail',compact('data'));
    }

    public function edit($id)
    {
        $data = UserToko::where('id','=',$id)->first();
        return view('admin.toko.edit',compact('data'));
    }

    public function updateToko(Request $request,$id)
    {
        $update = Shops::where('id','=',$id)->first();
        $update->url_tokopedia = $request['url_tokopedia'];
        $update->url_shopee = $request['url_shopee'];
        $update->status = "Active";
        $update->update();

        return redirect()->route('admin.toko')->with('message','Successfully update the Toko');
    }
}
