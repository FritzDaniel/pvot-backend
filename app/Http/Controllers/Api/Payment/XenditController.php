<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Api\BaseController;
use App\Models\Payment;
use App\Models\User;
use App\Notifications\SuccessPaymentMembership;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Xendit\Xendit;

class XenditController extends BaseController
{
    private $token = "xnd_development_we3wcctrm3DWwxOgXN6b3xNuGjn2Ycgnp2wVjcUmZq9J2pmXA5bRBErNjOL9c";

    // Virtual Account
    public function getListVa() {
        Xendit::setApiKey($this->token);
        $getVABanks = \Xendit\VirtualAccounts::getVABanks();
        return $this->sendResponse($getVABanks,'List Virtual Account.');
    }

    public function createVa(Request $request) {
        try {
            Xendit::setApiKey($this->token);
            $external_id = "va-".time();

            $params = [
                "external_id" => $external_id,
                "bank_code" => $request->bank,
                "name" => $request->name,
                "expected_amount" => $request->price,
                "is_closed" => true,
                "expiration_date" => Carbon::now()->addDays(1)->toISOString(),
                "is_single_use" => true
            ];
            $createVA = \Xendit\VirtualAccounts::create($params);

            $dataPayment = [
                'external_id' => $external_id,
                'payment_channel' => $request->bank.' Virtual Account',
                'email' => $request->email,
                'price' => $request->price,
                'description' => $request->description
            ];
            Payment::create($dataPayment);

            $user = User::where('email','=',$request->email)->first();
            activity()
                ->causedBy($user->id)
                ->createdAt(now())
                ->log('User Create Virtual Account');

            return $this->sendResponse($createVA,'Create Virtual Account.');

        }catch (\Xendit\Exceptions\ApiException $e) {
            return $this->sendError('Error Payment', $e->getMessage(), 400);
        }
    }

    public function callbackVa(Request $request)
    {
        $external_id = $request->external_id;
        $status = $request->status;
        $payment = Payment::where('external_id','=',$external_id)->exists();
        if($payment){
            if($status == "ACTIVE") {
                $update = Payment::where('external_id','=',$external_id)->first();
                $update->status = "Paid";
                $update->update();
                if($update->status == "Paid")
                {
                    //$user->notify(new SuccessPaymentMembership());
                    return $this->sendResponse($update,'Sukses Membayar Va');
                }
                return $this->sendError('Pembayaran Masih Pending', null, 400);
            }
        }else {
            return $this->sendError('Data tidak ada', null, 400);
        }
    }

    //Ewallet

    //QrCode

    //Retail Outlet
}
