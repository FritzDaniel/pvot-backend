<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PaymentController extends BaseController
{
    public function getPaymentAndRedirect($id)
    {
        $data = Payment::where('external_id','=',$id)->first();
        return redirect('http://pvotdigital.com/pembayaran-sukses/'.$data->xendit_id);
    }

    public function beliProduct(Request $request)
    {
        $totalPrice = 0;
        foreach ($request['items'] as $total)
        {
            $price = Product::where('id','=',$total['product_id'])->first();
            $totalPrice += $price->showPrice * $total['quantity'];
        }

        $user = $request->user();
        $external_id = "invoice-".time();
        $amount = $totalPrice;
        $user_id = $user->id;

        $dataPayment = [
            'external_id' => $external_id,
            'user_id' => $user_id,
            'payment_channel' => 'Moota',
            'email' => $user->email,
            'price' => $amount,
            'description' => 'Pembayaran Product'
        ];
        $payment = Payment::create($dataPayment);
        foreach ($request['items'] as $itm)
        {
            $supplier_id = Product::where('id','=',$itm['product_id'])->first();
            $store = new Transaction();
            $store->user_id = $user->id;
            $store->supplier_id = $supplier_id->user_id;
            $store->product_id = $itm['product_id'];
            $store->payment_id = $payment->id;
            $store->qty = $itm['quantity'];
            $store->transaction_date = Carbon::now();
            $store->save();
        }
        return $this->sendResponse($payment,'Store cart success');
    }
}
