<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Api\BaseController;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
                "invoice_duration" => 3600,
                "customer_notification_preference" => [
                    "invoice_created" => ["email"],
                    "invoice_reminder" => ["email"],
                    "invoice_paid" => ["email"],
                    "invoice_expired" => ["email"]
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
            return $this->sendError('Error Create Invoice', $e->getMessage(), 400);
        }
    }
}
