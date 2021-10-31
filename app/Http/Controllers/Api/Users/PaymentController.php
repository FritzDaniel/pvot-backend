<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Mutation;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Wallet;
use Carbon\Carbon;
use Dflydev\DotAccessData\Data;
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
            $store->variants = $request['variants'];
            $store->save();

            $product = Product::where('id','=',$itm['product_id'])->first();
            $product->productQty = $product->productQty - $itm['quantity'];
            $product->update();
        }
        return $this->sendResponse($payment,'Store cart success');
    }

    public function getWebhookCallback()
    {
        $notification = file_get_contents("php://input");

        $neko = json_decode($notification, TRUE);

        if($notification)
        {
            foreach ($neko as $jquin)
            {
                $kode_unik = substr($jquin['amount'], -3);

//                $jOrder = Payment::find($kode_unik);
//                $idOrder = $jOrder->external_id;

                $data = array(
                    'bank_id' => $jquin['bank_id'],
                    'account_number' => $jquin['account_number'],
                    'bank_type' => $jquin['bank_type'],
                    'date' => date('Y-m-d H:i:s'),
                    'amount' => $jquin['amount'],
                    'description' => $jquin['description'],
                    'type' => $jquin['type'],
                    'balance' => $jquin['balance'],
                    'kode_unik' => $kode_unik,
                    'id_order' => 'qwiojai412',
                    'user_id' => '2'
                );
                Mutation::create($data);
            }

            return $this->sendResponse(null,'Success');
        }
        return $this->sendError([
            'message' => 'Check mutasi gagal'
        ],'Error');
    }

    public function getCallback(Request $request,$id)
    {
        $invoice_id = $id;
        $dataPayment = Payment::where('external_id','=',$invoice_id)->first();
        if($dataPayment->status == "Pending")
        {
            $dataPayment->status = "Paid";
            $dataPayment->update();

            // Send E-Wallet
            $price = Transaction::where('payment_id','=',$dataPayment->id)->get();
            $total = 0;
            $supplier_id = 0;

            foreach ($price as $prc)
            {
                $product = Product::where('id','=',$prc->product_id)->first();
                $total += $product->productPrice * $prc->qty;
                $supplier_id = $prc->supplier_id;
            }

            $wallet = Wallet::where('user_id','=',$supplier_id)->first();
            $wallet->balance = $total;
            $wallet->update();

            return $this->sendResponse(
                [
                   'status' => 'Paid',
                   'invoice_id' => $invoice_id
            ],'Success');
        }else {
            return $this->sendResponse(
                [
                    'status' => 'Paid',
                    'invoice_id' => $invoice_id
                ],'Success');
        }
    }

    public function cartSummary($id)
    {
        $data = Payment::with('Transaction')
            ->where('external_id','=',$id)->first();
        return $this->sendResponse($data,'Success');
    }
}
