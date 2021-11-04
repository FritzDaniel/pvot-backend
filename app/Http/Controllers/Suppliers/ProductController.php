<?php

namespace App\Http\Controllers\Suppliers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Settings;
use App\Models\TransactionSequence;
use App\Models\Variant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function create()
    {
        $variants = Variant::orderBy('variantType','ASC')->get();
        $category = Category::orderBy('name','DESC')->get();
        return view('supplier.product.create',compact('category','variants'));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'productName' => 'required',
            'productDesc' => 'required',
            'productStock' => 'required|numeric|gt:1',
            'productCategory' => 'required',
            'productPrice' => 'required|numeric|gt:1',
        ]);

        if ($request->hasFile('productPicture')){
            if ($request->file('productPicture')->isValid()){
                $name = Carbon::now()->timestamp.'.'.$request->file('productPicture')->getClientOriginalExtension();
                $store_path = 'public/fotoProduct';
                $request->file('productPicture')->storeAs($store_path,$name);
            }
        }

        $settings = Settings::find(1);
        $productRevenue = $request['productPrice'] * ($settings->value / 100);

        $runningSeq = TransactionSequence::where('user_id','=',Auth::user()->id)->first();

        if($runningSeq == null)
        {
            $storeSequence = new TransactionSequence();
            $storeSequence->user_id = Auth::user()->id;
            $storeSequence->type = "SP";
            $storeSequence->running_seq = 1;
            $storeSequence->save();

            $uuid = $storeSequence->type.
                $storeSequence->user_id.
                $storeSequence->running_seq.
                Carbon::parse($storeSequence->created_at)->format('dmY');
        }else {
            $uuid = $runningSeq->type.
                $runningSeq->user_id.
                $runningSeq->running_seq.
                Carbon::parse($runningSeq->created_at)->format('dmY');
        }

        $store = new Product();
        $store->uuid = $uuid;
        $store->supplier_id = Auth::user()->id;
        $store->productName = $request['productName'];
        $store->productDesc = $request['productDesc'];
        $store->productStock = $request['productStock'];
        $store->productPicture = isset($name) ? "/storage/fotoProduct/".$name : '/storage/img/dummy.jpg';
        $store->productCategory = $request['productCategory'];
        $store->productPrice = $request['productPrice'];
        $store->productRevenue = ceil($productRevenue);
        $store->showPrice = $request['productPrice'] + ceil($productRevenue);
        $store->save();

        $updateSequence = TransactionSequence::where('user_id','=',Auth::user()->id)->first();
        $updateSequence->running_seq = $updateSequence->running_seq + 1;
        $updateSequence->update();

        return redirect()->route('supplier.product')->with('message','Product is successfully created');
    }

    public function edit($id)
    {
        $variants = Variant::orderBy('variantType','ASC')->get();
        $category = Category::orderBy('name','DESC')->get();
        $data = Product::where('uuid','=',$id)->first();
        return view('supplier.product.edit',compact('data','variants','category'));
    }

    public function update(Request $request,$id)
    {
        $this->validate($request,[
            'productName' => 'required',
            'productDesc' => 'required',
            'productStock' => 'required|numeric|gt:1',
            'productCategory' => 'required',
            'productPrice' => 'required|numeric|gt:1',
        ]);

        if ($request->hasFile('productPicture')) {
            if ($request->file('productPicture')->isValid()){
                $name = Carbon::now()->timestamp.'.'.$request->file('productPicture')->getClientOriginalExtension();
                $store_path = 'public/fotoProduct';
                $request->file('productPicture')->storeAs($store_path,$name);
            }
        }

        if($request['productPrice'])
        {
            $settings = Settings::find(1);
            $productRevenue = $request['productPrice'] * ($settings->value / 100);
        }

        $data = Product::where('uuid','=',$id)->first();
        $data->supplier_id = Auth::user()->id;
        $data->productName = $request['productName'];
        $data->productDesc = $request['productDesc'];
        $data->productStock = $request['productStock'];
        if ($request->hasFile('productPicture')) {
            if($data->productPicture !== "/storage/img/dummy.jpg")
            {
                $images_path = public_path().$data->productPicture;
                unlink($images_path);
            }
            $data->productPicture = isset($name) ? "/storage/fotoProduct/" . $name : '/storage/img/dummy.jpg';
        }
        $data->productCategory = $request['productCategory'];
        $data->productPrice = $request['productPrice'];
        $data->productRevenue = ceil($productRevenue);
        $data->showPrice = $request['productPrice'] + ceil($productRevenue);
        $data->update();

        return redirect()->route('supplier.product')->with('message','Product is successfully updated');
    }

    public function active($id)
    {
        $variant = ProductVariant::where('product_id','=',$id)->get();
        $countVariant = count($variant);

        if($countVariant == 0)
        {
            return redirect()->back()->with('error','Please add product variant first');
        }

        $data = Product::where('uuid','=',$id)->first();
        $data->status = "Active";
        $data->update();

        return redirect()->back()->with('message','Product status is successfully updated');
    }

    public function deactivate($id)
    {
        $data = Product::where('uuid','=',$id)->first();
        $data->status = "Not Active";
        $data->update();

        return redirect()->back()->with('message','Product status is successfully updated');
    }
}
