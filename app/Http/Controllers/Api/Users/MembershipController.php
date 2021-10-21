<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\Payment;
use App\Models\User;
use App\Models\UserToko;
use Illuminate\Http\Request;
use Validator;
use Xendit\Xendit;

class MembershipController extends BaseController
{
    private $token = "xnd_development_we3wcctrm3DWwxOgXN6b3xNuGjn2Ycgnp2wVjcUmZq9J2pmXA5bRBErNjOL9c";

    public function getUserDetail(Request $request)
    {
        $user = $request->user();
        $data = UserDetail::where('user_id','=',$user->id)->first();
        return $this->sendResponse($data,'User Detail.');
    }

    public function detailPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'email' => 'required',
            'country' => 'required',
            'alamat' => 'required',
            'city' => 'required',
            'provinsi' => 'required',
            'kodepos' => 'required',
            'nomorTelepon' => 'required',
            'marketplaceCount' => 'required',
            'marketplaceSelect' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors(),'Validation Error.',400);
        }
        $user = User::where('email','=',$request['email'])->first();

        $store = $user;
        $store->namaPerusahaan = isset($request['namaPerusahaan']) ? $request['namaPerusahaan'] : null;
        $store->country = isset($request['country']) ? $request['country'] : null;
        $store->alamat = isset($request['alamat']) ? $request['alamat'] : null;
        $store->city = isset($request['city']) ? $request['city'] : null;
        $store->provinsi = isset($request['provinsi']) ? $request['provinsi'] : null;
        $store->kodepos = isset($request['kodepos']) ? $request['kodepos'] : null;
        $store->informasiTambahan = isset($request['informasiTambahan']) ? $request['informasiTambahan'] : null;
        $store->update();

        $dataTokoUser = [
            'user_id' => $user->id,
            'tokoCount' => 1,
            'marketplaceCount' => $request['marketplaceCount'],
            'marketplaceSelect' => $request['marketplaceSelect'],
        ];
        UserToko::create($dataTokoUser);

        Xendit::setApiKey($this->token);
        $external_id = "invoice-".time();
        $biayaAdmin = 4500;
        $feeAdmin[] = [
            "type" => 'ADMIN',
            "value" => $biayaAdmin
        ];

        $items = [
            [
                "name" => "Membership PVOT Digital 1 Years",
                "quantity" => 1,
                "price" => 500000
            ],
            [
                "name" => "Pembuatan 1 Toko di ".$request['marketplaceCount']." Marketplace",
                "quantity" => 1,
                "price" => $request['marketplaceCount'] == 1 ? 0 : 250000
            ]
        ];

        $marketplaceCountPrice = $request['marketplaceCount'] == 1 ? 0 : 250000;
        $amount = 500000 + $marketplaceCountPrice + $biayaAdmin;

        $params = [
            'external_id' => $external_id,
            'payer_email' => $user->email,
            'description' => "Pembayaran Membership",
            'amount' => $amount,
            'customer' => [
                'given_names' => $user->name,
                'email' => $user->email,
                'mobile_number' => $user->phone,
                //'address' => $request->address
            ],
            "success_redirect_url" => "http://api.pvotdigital.com/public/api/v1/payment/retrieve/".$external_id,
            "invoice_duration" => 3600,
            "customer_notification_preference" => [
                "invoice_created" => ["email"],
                //"invoice_reminder" => ["email"],
                "invoice_paid" => ["email"],
                //"invoice_expired" => ["email"]
            ],
            "currency" => "IDR",
            "fixed_va" => true,
            "items" => $items,
            "fees" => $feeAdmin
        ];
        $createInvoice = \Xendit\Invoice::create($params);

        $dataPayment = [
            'xendit_id' => $createInvoice['id'],
            'external_id' => $external_id,
            'user_id' => $user->id,
            'payment_channel' => "Xendit Invoice",
            'email' => $user->email,
            'price' => $amount,
            'description' => "Pembayaran Membership"
        ];
        Payment::create($dataPayment);

        activity()
            ->causedBy($user)
            ->createdAt(now())
            ->log($user->name.' Register for Membership.');

        return $this->sendResponse($createInvoice,'Create Url Invoice.');
    }
}
