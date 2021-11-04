<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Mutation;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionSequence;
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

        $runningSeq = TransactionSequence::where('user_id','=',$user->id)->first();

        if($runningSeq == null)
        {
            $storeSequence = new TransactionSequence();
            $storeSequence->user_id = $user->id;
            $storeSequence->type = "PV";
            $storeSequence->running_seq = 1;
            $storeSequence->save();

            $uuid = $storeSequence->type.$storeSequence->user_id.$storeSequence->running_seq.Carbon::parse($storeSequence->created_at)->format('dmY');
        }else {
            $uuid = $runningSeq->type.$runningSeq->user_id.$runningSeq->running_seq.Carbon::parse($runningSeq->created_at)->format('dmY');
        }

        $external_id = $uuid;

        $amount = $totalPrice;
        $user_id = $user->id;

        $dataPayment = [
            'external_id' => $external_id,
            'uniq_code' =>  substr($amount, -3),
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
            $store->transaction_id = $payment->external_id;
            $store->transaction_date = Carbon::now();
            $store->user_id = $user->id;
            $store->supplier_id = $supplier_id->user_id;
            $store->product_id = $itm['product_id'];
            $store->qty = $itm['quantity'];
            $store->variants = $request['variants'];
            $store->save();

            $product = Product::where('id','=',$itm['product_id'])->first();
            $product->productQty = $product->productQty - $itm['quantity'];
            $product->update();
        }

        $updateSequence = TransactionSequence::where('user_id','=',$user->id)->first();
        $updateSequence->running_seq = $updateSequence->running_seq + 1;
        $updateSequence->update();

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

                $jOrder = Payment::where('uniq_code','=',$kode_unik)
                                ->where('status','=','Pending')
                                ->whereDate('created_at', Carbon::today())
                                ->first();
                $idOrder = $jOrder->external_id;

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
                    'id_order' => $idOrder,
                    'user_id' => $idOrder->user_id
                );
                Mutation::create($data);

                if($jOrder->status == "Pending")
                {
                    $jOrder->status = "Paid";
                    $jOrder->update();

                    // Send E-Wallet
                    $price = Transaction::where('transaction_id','=',$jOrder->external_id)->get();
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
                }
            }

            return $this->sendResponse(null,'Success');
        }
        return $this->sendError([
            'message' => 'Check mutasi gagal'
        ],'Error');
    }

    public function checkPayment(Request $request,$id)
    {
        $invoice_id = $id;
        $dataPayment = Payment::where('external_id','=',$invoice_id)->first();

        if($dataPayment == null)
        {
            return $this->sendError(['message' => 'Data tidak ada'],'Error',400);
        }

        return $this->sendResponse(
            $dataPayment,
            'Success'
        );
    }

    public function cartSummary($id)
    {
        $data = Payment::with('Transaction')
            ->where('external_id','=',$id)->first();
        return $this->sendResponse($data,'Success');
    }
}
