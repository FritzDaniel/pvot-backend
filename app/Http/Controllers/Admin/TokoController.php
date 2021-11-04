<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shops;
use Illuminate\Http\Request;

class TokoController extends Controller
{
    public function getDetailToko($id)
    {
        $data = Shops::where('io','=',$id)->first();
        return $this->sendResponse($data,'Shop Detail');
    }

    public function listToko()
    {
        $data = Shops::orderBy('created_at','DESC')->get();
        return $this->sendResponse($data,'List Shop Dropshipper');
    }

    public function changeStatus(Request $request,$id)
    {
        $data = Shops::orderBy('id',$id)->first();
        $data->url_tokopedia = $request['url_tokopedia'];
        $data->url_shopee = $request['url_shopee'];
        $data->status = "Created";
        $data->update();
        return $this->sendResponse($data,'Update Status Shop Dropshipper');
    }
}
