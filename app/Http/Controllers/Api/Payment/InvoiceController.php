<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Api\BaseController;
use App\Models\Memberships;
use App\Models\Payment;
use App\Models\User;
use App\Notifications\SuccessPaymentMembership;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Xendit\Xendit;

class InvoiceController extends BaseController
{
    private $token = "xnd_development_we3wcctrm3DWwxOgXN6b3xNuGjn2Ycgnp2wVjcUmZq9J2pmXA5bRBErNjOL9c";

    public function getInvoice($id)
    {
        Xendit::setApiKey($this->token);
        $dataInvoice = \Xendit\Invoice::retrieve($id);
        return $this->sendResponse($dataInvoice,'Invoice Data.');
    }

    public function createInvoice(Request $request) {

        try {
            Xendit::setApiKey($this->token);
            $external_id = "invoice-".time();
            $user = $request->user();
            $biayaAdmin = 4500;
            $feeAdmin[] = [
                "type" => 'ADMIN',
                "value" => $biayaAdmin
            ];

            $items = $request['items'];

            $params = [
                'external_id' => $external_id,
                'payer_email' => $user->email,
                'description' => $request->description,
                'amount' => $request->amount + $biayaAdmin,
                'customer' => [
                    'given_names' => $user->name,
                    'email' => $user->email,
                    'mobile_number' => $user->phone,
                    //'address' => $request->address
                ],
                "success_redirect_url" => "https://pvotdigital.com/pembayaran-sukses/".$external_id,
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
                'external_id' => $createInvoice['id'],
                'user_id' => $user->id,
                'payment_channel' => "Xendit Invoice",
                'email' => $user->email,
                'price' => $request->amount,
                'description' => $request->description
            ];
            Payment::create($dataPayment);

            activity()
                ->causedBy($user)
                ->createdAt(now())
                ->log('User Create Invoice');

            return $this->sendResponse($createInvoice,'Create Url Invoice.');

        }catch (\Xendit\Exceptions\ApiException $e) {
            return $this->sendError($e->getMessage(),'Error Create Invoice', 400);
        }
    }

    public function callbackInvoice(Request $request)
    {
        $external_id = $request['id'];
        $status = $request['status'];

        $payment = Payment::where('external_id','=',$external_id)->exists();
        if($payment){
            if($status == "PAID") {
                $update = Payment::where('external_id','=',$external_id)->first();
                $update->status = "Paid";
                $update->update();

                if($update->description == "Payment Membership")
                {
                    $updateMembership = Memberships::where('user_id','=',$update->user_id)->first();
                    $updateMembership->expiredDate = Carbon::now()->addYear();
                    $updateMembership->status = "Active";
                    $updateMembership->update();

                    $user = User::find($update->user_id);
                    $user->notify(new SuccessPaymentMembership());
                }

                return $this->sendResponse($update,'Sukses Membayar.');
            }
        }else {
            return $this->sendError(null, 'Data tidak ada', 400);
        }
    }
}
