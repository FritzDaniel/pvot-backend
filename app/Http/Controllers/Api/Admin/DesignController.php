<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseController;
use App\Models\Design;
use App\Models\DesignChild;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

class DesignController extends BaseController
{
    public function listDesign()
    {
        $data = Design::orderBy('created_at','DESC')->get();
        return $this->sendResponse($data,'List Design');
    }

    public function listSubDesign()
    {
        $data = DesignChild::with('DesignParent')->orderBy('created_at','DESC')->get();
        return $this->sendResponse($data,'List Design');
    }

    public function storeDesign(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'designName' => 'required',
        ]);

        if($validator->fails()) {
            return $this->sendError($validator->errors(),'Error input data.',400);
        }

        if ($request->hasFile('designImage')){
            if ($request->file('designImage')->isValid()){
                $name = Carbon::now()->timestamp.'.'.$request->file('designImage')->getClientOriginalExtension();
                $store_path = 'public/fotoDesign';
                $request->file('designImage')->storeAs($store_path,$name);
            }
        }

        $store = new Design();
        $store->designName = $request['designName'];
        $store->designImage = isset($name) ? "/storage/fotoDesign/".$name : '/storage/img/dummy.jpg';
        $store->save();

        return $this->sendResponse($store,'Create design success.',200);
    }

    public function storeSubDesign(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'designName' => 'required',
        ]);

        if($validator->fails()) {
            return $this->sendError($validator->errors(),'Error input data.',400);
        }

        if ($request->hasFile('designImage')){
            if ($request->file('designImage')->isValid()){
                $name = Carbon::now()->timestamp.'.'.$request->file('designImage')->getClientOriginalExtension();
                $store_path = 'public/fotoSubDesign';
                $request->file('designImage')->storeAs($store_path,$name);
            }
        }

        $store = new DesignChild();
        $store->design_id = $request['design_id'];
        $store->designName = $request['designName'];
        $store->designImage = isset($name) ? "/storage/fotoSubDesign/".$name : '/storage/img/dummy.jpg';
        $store->save();

        return $this->sendResponse($store,'Create subdesign success.',200);
    }
}
