<?php

namespace App\Http\Controllers\Suppliers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Variant;
use Illuminate\Http\Request;

class VariantController extends Controller
{
    public function index($id)
    {
        $master = Product::where('uuid','=',$id)->first();
        $data = ProductVariant::where('product_id','=',$id)
            ->orderBy('tipe','DESC')
            ->get();
        $variant = Variant::orderBy('variantType','ASC')->get();
        return view('supplier.variant.index',compact('master','data','variant'));
    }

    public function store(Request $request,$id)
    {
        $this->validate($request,[
            'variantType' => 'required',
            'variantName' => 'required'
        ]);
        $data = new ProductVariant();
        $data->product_id = $id;
        $data->tipe = $request['variantType'];
        $data->variantName = $request['variantName'];
        $data->save();

        return redirect()->back()->with('message','Success Add Product Variant');
    }

    public function delete($id)
    {
        $data = ProductVariant::where('id','=',$id)->first();
        $data->delete();
        return redirect()->back()->with('message','Success Delete Product Variant');
    }
}
