<?php

namespace App\Http\Controllers\Api\Landing;

use App\Http\Controllers\Api\BaseController;
use App\Models\Category;
use App\Models\Design;
use App\Models\DesignChild;
use App\Models\Product;
use App\Models\Testimoni;
use App\Models\User;
use Illuminate\Http\Request;

class LandingController extends BaseController
{
    public function getCategory()
    {
        $data = Category::orderBy('name','ASC')
            ->get();

        return $this->sendResponse($data, 'Category List.');
    }

    public function getSupplier()
    {
        $data = User::role('Supplier')
            ->orderBy('name','ASC')
            ->get();

        return $this->sendResponse($data, 'Supplier List.');
    }

    public function getSupplierDetail($id)
    {
        $data = User::role('Supplier')
            ->where('id','=',$id)
            ->first();

        return $this->sendResponse($data, 'Supplier Detail.');
    }

    public function getSupplierByCategory($id)
    {
        $data = User::role('Supplier')
            ->where('category','=',$id)
            ->get();

        return $this->sendResponse($data, 'Supplier Category.');
    }

    public function getSupplierProduct($id)
    {
        $data = Product::with('productVariant')
            ->where('supplier_id','=',$id)
            ->where('status','=','Active')
            ->get();

        return $this->sendResponse($data, 'Product Supplier List.');
    }

    public function getDesign()
    {
        $data = Design::orderBy('designName','ASC')->get();
        return $this->sendResponse($data,'List Design.');
    }

    public function getSubDesign($id)
    {
        $data = DesignChild::where('design_id','=',$id)->get();
        return $this->sendResponse($data,'Sub Design');
    }

    public function getProduct(Request $request)
    {
        $limit = $request->query('limit');
        $sortBy = $request->query('sortBy'); //popular|newest|mostBuy|highPrice|lowPrice|search

        if($sortBy == "popular")
        {
            $data = Product::with([
                'productCategory'
            ])
                ->withCount('productSold')
                ->where('status','=','Active')
                ->orderBy('product_sold_count','DESC')
                ->limit($limit)
                ->get();
        }else {
            $data = Product::with([
                'productCategory'
            ])
                ->withCount('productSold')
//                ->orderBy('productName','ASC')
                ->limit($limit)
                ->get();
        }
        return $this->sendResponse($data,'Success');
    }

    public function getDetailProduct($id)
    {
        $data = Product::with([
            'productCategory','productVariant'
        ])
            ->where('id','=',$id)->first();

        return $this->sendResponse($data,'Success');
    }

    public function getTestimoni()
    {
        $data = Testimoni::orderBy('created_at','DESC')->get();
        return $this->sendResponse($data,'List testimoni.');
    }
}
