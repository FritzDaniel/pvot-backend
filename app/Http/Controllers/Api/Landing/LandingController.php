<?php

namespace App\Http\Controllers\Api\Landing;

use App\Http\Controllers\Api\BaseController;
use App\Models\Category;
use App\Models\Design;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;

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

    public function getProduct()
    {
        $data = Product::with([
            'userDetail','productPhoto'
        ])->orderBy('productName','ASC')->get();
        return $this->sendResponse($data,'List Design.');
    }
}
