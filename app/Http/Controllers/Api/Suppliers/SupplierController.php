<?php

namespace App\Http\Controllers\Api\Suppliers;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class SupplierController extends BaseController
{
    public function listProduct(Request $request)
    {
        $user = $request->user();
        $data = Product::where('user_id','=', $user->id)->get();

        return $this->sendResponse($data,'List my product');
    }
}
