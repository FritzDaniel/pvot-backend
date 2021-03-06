<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Design;
use App\Models\Shops;
use App\Models\UserToko;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TokoController extends Controller
{
    public function detail($id)
    {
        $data = UserToko::where('id','=',$id)->first();
        $design = Design::where('supplier_id','=',$data->Shop->supplier_id)
            ->where('shop_id','=',null)
            ->orderBy('created_at','ASC')
            ->get();
        return view('admin.toko.detail',compact('data','design'));
    }

    public function edit($id)
    {
        $data = UserToko::where('shop_id','=',$id)->first();
        return view('admin.toko.edit',compact('data'));
    }

    public function editData($id)
    {
        $data = Shops::where('id','=',$id)->first();
        return view('admin.toko.editData',compact('data'));
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

    public function updateDataToko(Request $request,$id)
    {
        $this->validate($request,[
            'handphoneToko' => 'required',
            'namaToko' => "required",
            'alamatToko' => "required",
        ]);

        if ($request->hasFile('fotoToko')){
            if ($request->file('fotoToko')->isValid()){
                $name_fotoToko = Carbon::now()->timestamp.'.'.$request->file('fotoToko')->getClientOriginalExtension();
                $store_path = 'public/fotoToko';
                $request->file('fotoToko')->storeAs($store_path,$name_fotoToko);
            }
        }

        if ($request->hasFile('fotoHeaderToko')){
            if ($request->file('fotoHeaderToko')->isValid()){
                $name_fotoHeaderToko = Carbon::now()->timestamp.'.'.$request->file('fotoHeaderToko')->getClientOriginalExtension();
                $store_path = 'public/fotoHeaderToko';
                $request->file('fotoHeaderToko')->storeAs($store_path,$name_fotoHeaderToko);
            }
        }

        $update = Shops::where('id','=',$id)->first();
        $update->handphoneToko = $request['handphoneToko'];
        $update->namaToko = $request['namaToko'];
        $update->alamatToko = $request['alamatToko'];
        $update->description = $request['description'];
        if ($request->hasFile('fotoToko')) {

            if($update->fotoToko !== "/storage/img/dummy.jpg")
            {
                $images_path = public_path().$update->fotoToko;
                if (is_file($images_path)) {
                    unlink($images_path);
                }
            }
            $update->fotoToko = isset($name_fotoToko) ? "/storage/fotoToko/" . $name_fotoToko : '/storage/img/dummy.jpg';
        }
        if ($request->hasFile('fotoHeaderToko')) {

            if($update->fotoHeaderToko !== "/storage/img/dummy.jpg")
            {
                $images_path = public_path().$update->fotoHeaderToko;
                if (is_file($images_path)) {
                    unlink($images_path);
                }
            }
            $update->fotoHeaderToko = isset($name_fotoHeaderToko) ? "/storage/fotoHeaderToko/" . $name_fotoHeaderToko : '/storage/img/dummy.jpg';
        }
        $update->update();

        return redirect()->route('admin.toko')->with('message','Successfully update the Toko');
    }

    public function updateDesignToko(Request $request,$id)
    {
        $this->validate($request,[
            'design' => 'required',
        ]);

        $designUpdate = Design::where('shop_id','=',$id)->first();
        if($designUpdate !== null)
        {
            $designUpdate->shop_id = null;
            $designUpdate->update();
        }

        $data = Shops::find($id);
        $data->design_id = $request['design'];
        $data->update();

        $designUpdate = Design::find($request['design']);
        $designUpdate->shop_id = $data->id;
        $designUpdate->update();

        return redirect()->back()->with('message','Data Design Successfully Updated');
    }
}
