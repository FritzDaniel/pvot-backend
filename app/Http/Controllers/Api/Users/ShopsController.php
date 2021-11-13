<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Api\BaseController;
use App\Mail\ShopCreateMail;
use App\Models\Category;
use App\Models\Design;
use App\Models\Memberships;
use App\Models\ShopPicture;
use App\Models\Shops;
use App\Models\User;
use App\Models\UserToko;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Validator;

class ShopsController extends BaseController
{
    public function canBuatToko(Request $request)
    {
        $user = $request->user();
        $checkShopcCanCreate = UserToko::whereHas('Payment', function ($q) {
            $q->where('status','Paid');
        })
            ->where('user_id','=',$user->id)
            ->count();
        $checkTokoList = Shops::where('user_id','=',$user->id)->count();
        if($checkShopcCanCreate == $checkTokoList)
        {
            return $this->sendResponse([
                'buatToko' => false
            ],'Success');
        }else {
            return $this->sendResponse([
                'buatToko' => true
            ],'Success');
        }
    }
    public function myShop(Request $request)
    {
        $user = $request->user();
        $data = Shops::with(['Supplier','Design','Category'])
            ->where('user_id','=',$user->id)->get();
        return $this->sendResponse($data,'List Shop');
    }

    public function shopCreate(Request $request)
    {
        $user = $request->user();

        $checkMembership = Memberships::where('user_id','=',$user->id)
            ->where('status','=','Active')
            ->first();

        if(!$checkMembership)
        {
            return $this->sendError(null,'Please create membership first.',400);
        }

        $checkShopcCanCreate = UserToko::whereHas('Payment', function ($q) {
            $q->where('status','Paid');
        })
            ->where('user_id','=',$request->user()->id)
            ->count();
        $checkTokoList = Shops::where('user_id','=',$user->id)->count();

        if($checkShopcCanCreate == $checkTokoList)
        {
            return $this->sendError(['error' => 'You only can create '.$checkShopcCanCreate.' shop.'],'Error',400);
        }

        $validator = Validator::make($request->all(), [
            'kategoriToko' => 'required',
            'supplier' => 'required',
            'design' => 'required'
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
            'emailToko' => $request['emailToko'] ? $request['emailToko'] : $request->user()->email,
            'handphoneToko' => $request['handphoneToko'] ? $request['emailToko'] : $request->user()->phone,
            'namaToko' => $request['namaToko'] ? $request['namaToko'] : 'No Name',
            'alamatToko' => $request['alamatToko'] ? $request['alamatToko'] : $request->user()->alamat,
            'category_id' => $request['kategoriToko'],
            'fotoToko' => isset($name_fotoToko) ? "/storage/fotoToko/".$name_fotoToko : '/storage/img/dummy.jpg',
            'fotoHeaderToko' => isset($name_fotoHeaderToko) ? "/storage/fotoHeaderToko/".$name_fotoHeaderToko : '/storage/img/dummy.jpg',
            'design_id' => $request['design'],
            'supplier_id' => $request['supplier'],
            'description' => $request['descToko']
        ];
        $data = Shops::create($store);

        $designUpdate = Design::find($request['design']);
        $designUpdate->shop_id = $data->id;
        $designUpdate->update();

        $userToko = UserToko::whereHas('Payment', function ($q) {
            $q->where('status','Paid');
            }
        )
            ->where('user_id','=',$request->user()->id)
            ->where('shop_id','=',null)
            ->first();

        if($userToko)
        {
            $userToko->shop_id = $data->id;
            $userToko->update();
        }

        $marketplace = $userToko->marketplaceSelect == 3 ? "Tokopedia & Shopee" : $userToko == 1 ? "Tokopedia" : "Shopee";
        $category = Category::find($data->category_id);
        $supplier = User::find($data->supplier_id);

        Mail::to('support@pvotdigital.com')->send(new ShopCreateMail(
            $user->name,$user->email,$user->phone,
            $data->namaToko,$marketplace,$category->name,
            $supplier->name,$designUpdate->designName
            )
        );

        return $this->sendResponse($data, 'Success Create Shop.');
    }
}
