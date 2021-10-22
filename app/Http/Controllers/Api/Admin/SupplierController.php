<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Withdraw;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

class SupplierController extends BaseController
{
    public function storeSupplier(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required',
            'password' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors(),'Error',400);
        }

        if ($request->hasFile('profilePicture')) {
            if ($request->file('profilePicture')->isValid()) {
                $name = Carbon::now()->timestamp . '.' . $request->file('profilePicture')->getClientOriginalExtension();
                $store_path = 'public/fotoUser';
                $request->file('profilePicture')->storeAs($store_path, $name);
            }
        }

        $store = new User();
        $store->name = $request['name'];
        $store->email = $request['email'];
        $store->email_verified_at = Carbon::now();
        $store->password = bcrypt($request['password']);
        if($request['phone'])
        {
            $phoneValidation = substr($request['phone'], 0,1);

            if($phoneValidation == "0")
            {
                $store->phone = '+62'.substr($request['phone'], 1);
            }else {
                $store->phone = '+62'.$request['phone'];
            }
            $store->phone = $request['phone'];
        }
        $store->profilePicture = isset($name) ? '/storage/fotoUser/'.$name : '/storage/img/dummyUser.jpg';
        $store->country = $request['country'];
        $store->alamat = $request['alamat'];
        $store->provinsi = $request['provinsi'];
        $store->kodepos = $request['kodepos'];
        $store->save();

        $store->assignRole('Supplier');

        $dataWallet = [
            'user_id' => $store->id,
            'balance' => 0
        ];
        Wallet::create($dataWallet);

        return $this->sendResponse($store,'Store Supplier Success.');
    }

    public function updateSupplier(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors(), 'Validation Error.', 400);
        }

        if ($request->hasFile('profilePicture')) {
            if ($request->file('profilePicture')->isValid()) {
                $name = Carbon::now()->timestamp . '.' . $request->file('profilePicture')->getClientOriginalExtension();
                $store_path = 'public/fotoUser';
                $request->file('profilePicture')->storeAs($store_path, $name);
            }
        }

        $store = User::where('id', '=', $id)->first();
        if($request['name'])
        {
            $store->name = $request['name'];
        }
        if($request['password'])
        {
            $store->password = bcrypt($request['password']);
        }
        if($request['phone'])
        {
            $phoneValidation = substr($request['phone'], 0,1);

            if($phoneValidation == "0")
            {
                $store->phone = '+62'.substr($request['phone'], 1);
            }else {
                $store->phone = '+62'.$request['phone'];
            }
            $store->phone = $request['phone'];
        }
        if ($request->hasFile('profilePicture'))
        {
            $store->profilePicture = isset($name) ? '/storage/fotoUser/'.$name : '/storage/img/dummyUser.jpg';
        }
        if($request['country'])
        {
            $store->country = $request['country'];
        }
        if($request['alamat'])
        {
            $store->alamat = $request['alamat'];
        }
        if($request['provinsi'])
        {
            $store->provinsi = $request['provinsi'];
        }
        if ($request['kodepos'])
        {
            $store->kodepos = $request['kodepos'];
        }
        $store->update();

        return $this->sendResponse($store,'Success');
    }

    public function listWithdraw()
    {
        $data = Withdraw::orderBy('created_at','DESC')->get();
        return $this->sendResponse($data,'Success');
    }

    public function changeStatusWithdraw(Request $request,$id)
    {
        if ($request->hasFile('buktiTransfer')) {
            if ($request->file('buktiTransfer')->isValid()) {
                $name = Carbon::now()->timestamp . '.' . $request->file('buktiTransfer')->getClientOriginalExtension();
                $store_path = 'public/buktiTransfer';
                $request->file('buktiTransfer')->storeAs($store_path, $name);
            }
        }

        $data = Withdraw::where('id','=',$id)->first();
        $data->status = "Settle";
        $data->buktiTransfer = isset($name) ? '/storage/buktiTransfer/'.$name : null;
        $data->update();
        return $this->sendResponse($data, 'Success');
    }
}
