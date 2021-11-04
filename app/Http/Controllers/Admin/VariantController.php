<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Variant;
use Illuminate\Http\Request;

class VariantController extends Controller
{
    public function variantCreate()
    {
        return view('admin.variant.create');
    }

    public function variantStore(Request $request)
    {
        $this->validate($request,[
            'variantType' => 'required'
        ]);

        $data = new Variant();
        $data->variantType = $request['variantType'];
        $data->save();

        return redirect()->route('admin.variant')->with('message','Variant successfully created');
    }

    public function edit($id)
    {
        $data = Variant::find($id);
        return view('admin.variant.edit',compact('data'));
    }

    public function variantUpdate(Request $request,$id)
    {
        $this->validate($request,[
            'variantType' => 'required'
        ]);

        $data = Variant::where('id','=',$id)->first();
        $data->variantType = $request['variantType'];
        $data->update();

        return redirect()->route('admin.variant')->with('message','Variant successfully updated');
    }

    public function variantDelete($id)
    {
        $data = Variant::where('id','=',$id)->first();
        $data->delete();

        return $this->sendResponse($data,'Success');
    }
}
