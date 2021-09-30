<?php

namespace App\Http\Controllers\Api\Landing;

use App\Http\Controllers\Api\BaseController;
use App\Models\Category;
use App\Models\User;

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
}
