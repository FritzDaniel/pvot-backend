<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\Memberships;
use App\Models\UserDetail;
use App\Notifications\SuccessPaymentMembership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use function PHPUnit\Framework\isNull;

class MembershipController extends BaseController
{
    public function getUserDetail(Request $request)
    {
        $user = $request->user();
        $data = UserDetail::where('user_id','=',$user->id)->first();
        return $this->sendResponse($data,'User Detail.');
    }

    public function storeUserDetail(Request $request)
    {
        $user = $request->user();
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'email' => 'required',
            'negara' => 'required',
            'alamat' => 'required',
            'kota' => 'required',
            'provinsi' => 'required',
            'kodepos' => 'required',
            'nomorTelepon' => 'required',
            'marketplaceCount' => 'required',
            'marketplaceSelect' => 'required',
            'paymentChannel' => 'required',
            'price' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors(),400);
        }

        $store = UserDetail::firstOrNew(array('user_id' => $user->id));
        $store->user_id = $user->id;
        $store->namaPerusahaan = $request['namaPerusahaan'];
        $store->negara = $request['negara'];
        $store->alamat = $request['alamat'];
        $store->kota = $request['kota'];
        $store->provinsi = $request['provinsi'];
        $store->kodepos = $request['kodepos'];
        $store->informasiTambahan = $request['informasiTambahan'];
        $store->save();

        $membershipCheck = Memberships::where('user_id','=',$user->id)
            ->where('status','=','Pending')
            ->first();
        if($membershipCheck !== null)
        {
            $dataMembership = $membershipCheck;
        } else {
            $dataMembership = [
                'user_id' => $user->id,
                'marketplaceCount' => $request['marketplaceCount'],
                'marketplaceSelect' => $request['marketplaceSelect'],
                'paymentChannel' => $request['paymentChannel'],
                'price' => $request['price'],
            ];
            Memberships::create($dataMembership);
        }

        activity()
            ->causedBy($user)
            ->createdAt(now())
            ->log($user->name.' Register for Membership.');

//        $user->notify(new SuccessPaymentMembership());

        return $this->sendResponse($dataMembership,'UserDetail');
    }
}
