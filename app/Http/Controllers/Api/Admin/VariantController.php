<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseController;
use App\Models\Variant;
use Illuminate\Http\Request;
use Validator;

class VariantController extends BaseController
{
    public function variant()
    {
        $data = Variant::all();
        return $this->sendResponse($data, 'Success');
    }

    public function variantStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'variantType' => 'required',
        ]);

        if($validator->fails()) {
            return $this->sendError($validator->errors(),'Error',400);
        }
        $data = new Variant();
        $data->variantType = $request['variantType'];
        $data->save();

        return $this->sendResponse($data,'Success');
    }

    public function variantUpdate(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'variantType' => 'required',
        ]);

        if($validator->fails()) {
            return $this->sendError($validator->errors(),'Error',400);
        }
        $data = Variant::where('id','=',$id)->first();
        $data->variantType = $request['variantType'];
        $data->update();

        return $this->sendResponse($data,'Success');
    }

    public function variantDelete($id)
    {
        $data = Variant::where('id','=',$id)->first();
        $data->delete();

        return $this->sendResponse($data,'Success');
    }
}
