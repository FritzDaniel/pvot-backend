<?php

namespace App\Http\Controllers\Admin;

use App\Models\Design;
use App\Models\DesignChild;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DesignController extends Controller
{
    public function createDesign()
    {
        $supplier = User::role('Supplier')->get();
        return view('admin.design.create',compact('supplier'));
    }

    public function editDesign($id)
    {
        $supplier = User::role('Supplier')->get();
        $data = Design::find($id);
        return view('admin.design.edit',compact('data','supplier'));
    }

    public function subDesign($id)
    {
        $master = Design::find($id);
        $data = DesignChild::where('design_id','=',$id)
            ->orderBy('created_at','DESC')
            ->get();
        return view('admin.design.subDesign.index',compact('master','data'));
    }

    public function createSubDesign($id)
    {
        $master = Design::find($id);
        return view('admin.design.subDesign.create',compact('master'));
    }

    public function storeDesign(Request $request)
    {
        $this->validate($request,[
            'designName' => 'required',
            'supplier' => 'required',
        ]);

        if ($request->hasFile('designImage')){
            if ($request->file('designImage')->isValid()){
                $name = Carbon::now()->timestamp.'.'.$request->file('designImage')->getClientOriginalExtension();
                $store_path = 'public/fotoDesign';
                $request->file('designImage')->storeAs($store_path,$name);
            }
        }

        $store = new Design();
        $store->designName = $request['designName'];
        $store->supplier_id = $request['supplier'];
        $store->designImage = isset($name) ? "/storage/fotoDesign/".$name : '/storage/img/dummy.jpg';
        $store->save();

        return redirect()->route('admin.design')->with('message','Design is successfully created');
    }

    public function updateDesign(Request $request,$id)
    {
        $this->validate($request,[
            'designName' => 'required',
            'supplier' => 'required'
        ]);

        if ($request->hasFile('designImage')){
            if ($request->file('designImage')->isValid()){
                $name = Carbon::now()->timestamp.'.'.$request->file('designImage')->getClientOriginalExtension();
                $store_path = 'public/fotoDesign';
                $request->file('designImage')->storeAs($store_path,$name);
            }
        }

        $data = Design::where('id','=',$id)->first();
        if($request['designName'])
        {
            $data->designName = $request['designName'];
        }
        if($request['supplier'])
        {
            $data->supplier_id = $request['supplier'];
        }
        if ($request->hasFile('designImage')) {

            if($data->designImage !== "/storage/img/dummy.jpg")
            {
                $images_path = public_path().$data->designImage;
                if (is_file($images_path)) {
                    unlink($images_path);
                }
            }
            $data->designImage = isset($name) ? "/storage/fotoDesign/" . $name : '/storage/img/dummy.jpg';
        }
        $data->update();

        return redirect()->route('admin.design')->with('message','Category is successfully updated');
    }

    public function storeSubDesign(Request $request,$id)
    {
        $this->validate($request,[
            'designName' => 'required'
        ]);

        if ($request->hasFile('designImage')){
            if ($request->file('designImage')->isValid()){
                $name = Carbon::now()->timestamp.'.'.$request->file('designImage')->getClientOriginalExtension();
                $store_path = 'public/fotoSubDesign';
                $request->file('designImage')->storeAs($store_path,$name);
            }
        }

        $store = new DesignChild();
        $store->design_id = $id;
        $store->designName = $request['designName'];
        $store->designImage = isset($name) ? "/storage/fotoSubDesign/".$name : '/storage/img/dummy.jpg';
        $store->save();

        return redirect()->route('admin.subDesign',$id)->with('message','Design is successfully created');
    }

    public function editSubDesign($id)
    {
        $data = DesignChild::find($id);
        return view('admin.design.subDesign.edit',compact('data'));
    }

    public function updateSubDesign(Request $request,$id)
    {
        $this->validate($request,[
            'designName' => 'required'
        ]);

        if ($request->hasFile('designImage')){
            if ($request->file('designImage')->isValid()){
                $name = Carbon::now()->timestamp.'.'.$request->file('designImage')->getClientOriginalExtension();
                $store_path = 'public/fotoSubDesign';
                $request->file('designImage')->storeAs($store_path,$name);
            }
        }

        $data = DesignChild::where('id','=',$id)->first();
        if($request['designName'])
        {
            $data->designName = $request['designName'];
        }
        if ($request->hasFile('designImage')) {

            if($data->designImage !== "/storage/img/dummy.jpg") {

                $images_path = public_path() . $data->designImage;
                if (is_file($images_path)) {
                    unlink($images_path);
                }
            }
            $data->designImage = isset($name) ? "/storage/fotoSubDesign/" . $name : '/storage/img/dummy.jpg';
        }
        $data->update();

        return redirect()->route('admin.subDesign',$data->design_id)->with('message','Design is successfully updated');
    }
}
