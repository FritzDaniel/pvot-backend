<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends BaseController
{
    public function getDataPayment($id)
    {
        $data = Payment::where('external_id','=',$id)->first();
        return $this->sendResponse($data,'Payment data');
    }
}
