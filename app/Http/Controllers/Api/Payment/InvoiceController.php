<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Api\BaseController;
use App\Models\Memberships;
use App\Models\Payment;
use App\Models\User;
use App\Notifications\SuccessPaymentMembership;
use Carbon\Carbon;
use Illuminate\Http\Request;
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

    public function callbackInvoice(Request $request)
    {
        $xendit_id = $request['id'];
        $status = $request['status'];

        $payment = Payment::where('xendit_id','=',$xendit_id)->exists();
        if($payment){
            if($status == "PAID") {
                $update = Payment::where('xendit_id','=',$xendit_id)->first();
                $update->status = "Paid";
                $update->update();

                if($update->description == "Pembayaran Membership")
                {
                    $updateMembership = Memberships::where('user_id','=',$update->user_id)->first();
                    $updateMembership->membership = true;
                    $updateMembership->expiredDate = Carbon::now()->addYear();
                    $updateMembership->status = "Active";
                    $updateMembership->update();

                    $user = User::find($update->user_id);
                    $user->notify(new SuccessPaymentMembership());
                }

                if($update->description == "Pembayaran Membership")
                {

                }

                return $this->sendResponse($update,'Sukses Membayar.');
            }
        }else {
            return $this->sendError(null, 'Data tidak ada', 400);
        }
    }
}
