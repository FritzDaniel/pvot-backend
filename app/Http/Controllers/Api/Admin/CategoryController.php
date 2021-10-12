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
            return $this->sendError($validator->errors(),'Error input data.',400);
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
            'logo' => isset($name) ? "/storage/fotoKategori/".$name : null,
        ];
        $data = Category::create($store);

        $subCategory = $request['subCategory'];
        if($subCategory)
        {
            foreach ($subCategory as $subC)
            {
                $storeSubCategory = new SubCategory();
                $storeSubCategory->category_id = $data->id;
                $storeSubCategory->name = $subC;
                $storeSubCategory->save();
            }
        }

        return $this->sendResponse($data,'Category Created Successfully.',201);
    }
}
