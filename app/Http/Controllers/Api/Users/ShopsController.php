<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Api\BaseController;
use App\Models\Memberships;
use App\Models\Shops;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

class ShopsController extends BaseController
{
    public function myShop(Request $request)
    {
        $user = $request->user();
        $data = Shops::with('userDetail')
            ->where('user_id','=',$user->id)->get();
        return $this->sendResponse($data,'List Shop');
    }

    public function shopCreate(Request $request)
    {

        $checkMembership = Memberships::where('user_id','=',$request->user()->id)
            ->where('status','=','Active')
            ->first();

        if(!$checkMembership)
        {
            return $this->sendError(null,'Please create membership first.',400);
        }

        //$checkShopCreate = Shops::where('user_id','=',$request->user()->id)->count();

        //if($checkShopCreate == $checkMembership->marketplaceCount)
        //{
        //    return $this->sendError(null,'You only can create '.$checkMembership->marketplaceCount.' shop.',400);
        //}

        $validator = Validator::make($request->all(), [
            'emailToko' => 'required|email',
            'handphoneToko' => 'required',
            'namaToko' => 'required',
            'alamatToko' => 'required',
            'fotoToko' => 'mimes:jpg,png,jpeg|max:2048',
            'fotoHeaderToko' => 'mimes:jpg,png,jpeg|max:2048',
            'kategoriToko' => 'required',
            'supplier' => 'required',
            'design' => 'required',
            'descToko' => 'required'
        ]);

        if($validator->fails()) {
            return $this->sendError($validator->errors(),'Validation Error.',400);
        }

        if ($request->hasFile('fotoToko')){
            if ($request->file('fotoToko')->isValid()){
                $name_fotoToko = Carbon::now()->timestamp.'.'.$request->file('fotoToko')->getClientOriginalExtension();
                $store_path = 'public/fotoToko';
                $request->file('fotoToko')->storeAs($store_path,$name_fotoToko);
            }
        }

        if ($request->hasFile('fotoHeaderToko')){
            if ($request->file('fotoHeaderToko')->isValid()){
                $name_fotoHeaderToko = Carbon::now()->timestamp.'.'.$request->file('fotoHeaderToko')->getClientOriginalExtension();
                $store_path = 'public/fotoHeaderToko';
                $request->file('fotoHeaderToko')->storeAs($store_path,$name_fotoHeaderToko);
            }
        }

        $store = [
            'user_id' => $request->user()->id,
            'emailToko' => $request['emailToko'],
            'handphoneToko' => $request['handphoneToko'],
            'namaToko' => $request['namaToko'],
            'alamatToko' => $request['alamatToko'],
            'fotoToko' => isset($name_fotoToko) ? "/storage/fotoToko/".$name_fotoToko : '/storage/fotoHeaderToko/dummy.jpg',
            'fotoHeaderToko' => isset($name_fotoHeaderToko) ? "/storage/fotoHeaderToko/".$name_fotoHeaderToko : '/storage/fotoHeaderToko/dummy.jpg',
            'kategoriToko' => $request['kategoriToko'],
            'design' => $request['design'],
            'supplier' => $request['supplier'],
            'descToko' => $request['descToko']
        ];
        $data = Shops::create($store);

        return $this->sendResponse($data, 'Success Create Shop.');
    }
}
