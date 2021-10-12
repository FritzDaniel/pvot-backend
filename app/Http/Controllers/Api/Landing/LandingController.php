<?php

namespace App\Http\Controllers\Api\Landing;

use App\Http\Controllers\Api\BaseController;
use App\Models\Category;
use App\Models\Design;
use App\Models\Product;
use App\Models\Testimoni;
use App\Models\User;
use Illuminate\Http\Request;

class LandingController extends BaseController
{
    public function getCategory()
    {
        $data = Category::with("Child")
            ->orderBy('name','ASC')
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

    public function getDesign()
    {
        $data = Design::orderBy('designName','ASC')->get();
        return $this->sendResponse($data,'List Design.');
    }

    public function getProduct(Request $request)
    {
        $limit = $request->query('limit');
        $sortBy = $request->query('sortBy');

        if($sortBy == "popular")
        {
            $data = Product::with([
                'userDetail','productPhoto','productCategory'
            ])
                ->withCount('productSold')
                ->orderBy('product_sold_count','DESC')
                ->limit($limit)
                ->get();
        }else {
            $data = Product::with([
                'userDetail','productPhoto','productCategory'
            ])
                ->withCount('productSold')
//                ->orderBy('productName','ASC')
                ->limit($limit)
                ->get();
        }
        return $this->sendResponse($data,'List Design.');
    }

    public function getTestimoni()
    {
        $data = Testimoni::orderBy('created_at','DESC')->get();
        return $this->sendResponse($data,'List testimoni.');
    }
}
