<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\Payment;
use App\Models\TransactionSequence;
use App\Models\User;
use App\Models\UserToko;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;
use Xendit\Xendit;

class MembershipController extends BaseController
{
    private $token = "xnd_production_MzWocU45WH6PXbFiqURguaPr32UEnmIdM0bQGKjoSrxLt7PbM2zEyhaCJQpsXtaE";

    public function detailPayment(Request $request)
    {
        Xendit::setApiKey($this->token);

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
        $store->namaPerusahaan = isset($request['namaPerusahaan']) ? $request['namaPerusahaan'] : $user->namaPerusahaan;
        $store->country = isset($request['country']) ? $request['country'] : $user->country;
        $store->alamat = isset($request['alamat']) ? $request['alamat'] : $user->alamat;
        $store->city = isset($request['city']) ? $request['city'] : $user->city;
        $store->provinsi = isset($request['provinsi']) ? $request['provinsi'] : $user->provinsi;
        $store->kodepos = isset($request['kodepos']) ? $request['kodepos'] : $user->kodepos;
        $store->informasiTambahan = isset($request['informasiTambahan']) ? $request['informasiTambahan'] : $user->informasiTambahan;
        $store->update();

        $checkPayment = Payment::where('user_id','=',$user->id)
            ->where('xendit_id','!=',null)
            ->where('status','=','Pending')
            ->first();

        if($checkPayment !== null)
        {
            $dataInvoice = \Xendit\Invoice::retrieve($checkPayment->xendit_id);
            if($dataInvoice['status'] == "PENDING")
            {
                $updatePayment = Payment::where('xendit_id','=',$checkPayment->xendit_id)->first();
                $updatePayment->status = "Expired";
                $updatePayment->update();
            }
        }

        $runningSeq = TransactionSequence::where('user_id','=',$user->id)->first();

        if($runningSeq == null)
        {
            $storeSequence = new TransactionSequence();
            $storeSequence->user_id = $user->id;
            $storeSequence->type = "DP";
            $storeSequence->running_seq = 1;
            $storeSequence->save();

            $uuid = $storeSequence->type.
                $storeSequence->user_id.
                $storeSequence->running_seq.
                Carbon::parse($storeSequence->updated_at)->format('dmY');
        }else {
            $uuid = $runningSeq->type.
                $runningSeq->user_id.
                $runningSeq->running_seq.
                Carbon::parse($runningSeq->updated_at)->format('dmY');
        }

        $external_id = $uuid;

        $biayaAdmin = 4500;
        $feeAdmin[] = [
            "type" => 'ADMIN',
            "value" => $biayaAdmin
        ];

        if($user->Membership->status == "Active")
        {
            $items = [
                [
                    "name" => "Penambahan 1 Toko",
                    "quantity" => 1,
                    "price" => 300000
                ],
                [
                    "name" => "Pembuatan 1 Toko di ".$request['marketplaceCount']." Marketplace",
                    "quantity" => 1,
                    "price" => $request['marketplaceCount'] == 1 ? 0 : 150000
                ]
            ];
        }else {
            $items = [
                [
                    "name" => "Membership PVOT Digital 1 Years",
                    "quantity" => 1,
                    "price" => 300000
                ],
                [
                    "name" => "Pembuatan 1 Toko di ".$request['marketplaceCount']." Marketplace",
                    "quantity" => 1,
                    "price" => $request['marketplaceCount'] == 1 ? 0 : 150000
                ]
            ];
        }

        $marketplaceCountPrice = $request['marketplaceCount'] == 1 ? 0 : 150000;
        $amount = 300000 + $marketplaceCountPrice + $biayaAdmin;

        $params = [
            'external_id' => $external_id,
            'payer_email' => $user->email,
            'description' => $user->Membership->status == "Active" ? "Penambahan Toko" : "Pembayaran Membership",
            'amount' => $amount,
            'customer' => [
                'given_names' => $user->name,
                'email' => $user->email,
                'mobile_number' => $user->phone,
                //'address' => $request->address
            ],
            "success_redirect_url" => "https://dashboard.pvotdigital.com/api/v1/payment/retrieve/".$external_id,
            "invoice_duration" => 86400,
            "customer_notification_preference" => [
                "invoice_created" => ["email"],
                //"invoice_reminder" => ["email"],
                "invoice_paid" => ["email"],
                //"invoice_expired" => ["email"]
            ],
            "currency" => "IDR",
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
            'description' => $user->Membership->status == "Active" ? "Penambahan Toko" : "Pembayaran Membership"
        ];
        Payment::create($dataPayment);

        $dataTokoUser = [
            'user_id' => $user->id,
            'transaction_id' => $external_id,
            'tokoCount' => 1,
            'marketplaceCount' => $request['marketplaceCount'],
            'marketplaceSelect' => $request['marketplaceSelect'],
        ];
        UserToko::create($dataTokoUser);

        $updateSequence = TransactionSequence::where('user_id','=',$user->id)->first();
        $updateSequence->running_seq = $updateSequence->running_seq + 1;
        $updateSequence->update();

        activity()
            ->causedBy($user)
            ->createdAt(now())
            ->log($user->name . $user->Membership->status == "Active" ? ' Add shop can create' : ' Register for Membership.');

        return $this->sendResponse($createInvoice,'Create Url Invoice.');
    }
}
