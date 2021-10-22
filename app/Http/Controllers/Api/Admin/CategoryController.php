<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseController;
use App\Models\Category;
use App\Models\SubCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

class CategoryController extends BaseController
{
    public function storeCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if($validator->fails()) {
            return $this->sendError($validator->errors(),'Error',400);
        }

        if ($request->hasFile('logo')){
            if ($request->file('logo')->isValid()){
                $name = Carbon::now()->timestamp.'.'.$request->file('logo')->getClientOriginalExtension();
                $store_path = 'public/fotoKategori';
                $request->file('logo')->storeAs($store_path,$name);
            }
        }

        $store = [
            'name' => $request['name'],
            'logo' => isset($name) ? "/storage/fotoKategori/".$name : '/storage/img/dummy.jpg',
        ];
        $data = Category::create($store);

        return $this->sendResponse($data,'Success',200);
    }

    public function updateCategory(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if($validator->fails()) {
            return $this->sendError($validator->errors(),'Error',400);
        }

        if ($request->hasFile('logo')){
            if ($request->file('logo')->isValid()){
                $name = Carbon::now()->timestamp.'.'.$request->file('logo')->getClientOriginalExtension();
                $store_path = 'public/fotoKategori';
                $request->file('logo')->storeAs($store_path,$name);
            }
        }

        $data = Category::where('id','=',$id)->first();
        $data->name = $request['name'];
        $data->logo = isset($name) ? "/storage/fotoKategori/".$name : '/storage/img/dummy.jpg';
        $data->update();

        return $this->sendResponse($data,'Success',200);
    }

    public function deleteCategory($id)
    {
        $data = Category::where('id','=',$id)->first();
        if($data == null)
        {
            return $this->sendError(['error'=>'No Data'],'Error',400);
        }
        $data->delete();

        return $this->sendResponse($data,'Success');
    }
}
