<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseController;
use App\Models\Testimoni;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

class TestimoniController extends BaseController
{
    public function storeTestimoni(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'age' => 'required',
                'testimoni' => 'required',
                'company' => 'required'
            ]);

            if($validator->fails()) {
                return $this->sendError($validator->errors(),'Error input data.',400);
            }

            if ($request->hasFile('photo')){
                if ($request->file('photo')->isValid()){
                    $name = Carbon::now()->timestamp.'.'.$request->file('photo')->getClientOriginalExtension();
                    $store_path = 'public/fotoTestimoni';
                    $request->file('photo')->storeAs($store_path,$name);
                }
            }

            $store = new Testimoni();
            $store->photo = isset($name) ? "/storage/fotoTestimoni/".$name : null;
            $store->name = $request['name'];
            $store->age = $request['age'];
            $store->testimoni = $request['testimoni'];
            $store->company = $request['company'];
            $store->save();

            return $this->sendResponse($store,'Create testimoni success.',200);

        } catch (\Exception $e)
        {
            $this->sendError($e,'Error data.',400);
        }
    }
}
