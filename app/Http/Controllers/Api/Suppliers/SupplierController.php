<?php

namespace App\Http\Controllers\Api\Suppliers;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductPicture;
use App\Models\Settings;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

class SupplierController extends BaseController
{
    public function myProduct(Request $request)
    {
        $user = $request->user();
        $data = Product::with(['userDetail','productPhoto'])
            ->where('user_id','=',$user->id)
            ->orderBy('created_at','DESC')
            ->get();
        return $this->sendResponse($data,'My product.',200);
    }

    public function detailProduct(Request $request,$id)
    {
        $user = $request->user();
        $data = Product::with(['userDetail','productPhoto'])
            ->where('user_id','=',$user->id)
            ->where('id','=',$id)
            ->first();
        return $this->sendResponse($data,'Detail product.');
    }

    public function storeProduct(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'productName' => 'required',
                'productDesc' => 'required',
                'qty' => 'required',
                'price' => 'required',
            ]);

            if($validator->fails()){
                return $this->sendError($validator->errors(),'Validation Error.',400);
            }

            // Product Revenue
            $settings = Settings::where('name','=','Product Markup')->first();
            $productRevenue = $request['price'] * ($settings->value/100);

            $store = new Product();
            $store->user_id = $request->user()->id;
            $store->productName = $request['productName'];
            $store->productDesc = $request['productDesc'];
            $store->productQty = $request['qty'];
            $store->productPrice = $request['price'];
            $store->productRevenue = ceil($productRevenue);
            $store->showPrice = $request['price'] + ceil($productRevenue);
            $store->save();

            if($request['productCategory'])
            {
                foreach ($request['productCategory'] as $cat)
                {
                    $storeCategory = [
                        'product_id' => $store->id,
                        'category_id' => $cat,
                    ];
                    ProductCategory::create($storeCategory);
                }
            }

            if ($request->hasFile('productPhoto')){
                foreach($request->file('productPhoto') as $image)
                {
                    $name = str_replace(' ', '', $request['productName']).Carbon::now()->timestamp.'.'.$image->getClientOriginalName();
                    $store_path = 'public/fotoProduct';
                    $image->storeAs($store_path,$name);

                    $storePhoto = [
                        'product_id' => $store->id,
                        'productPicture' => isset($name) ? "/storage/fotoProduct/".$name : null,
                    ];
                    ProductPicture::create($storePhoto);
                }
            }
        } catch (\Exception $e) {
            return $this->sendError($e,'Error.',400);
        }
        return $this->sendResponse($store,'Add Product Success.',201);
    }
}
