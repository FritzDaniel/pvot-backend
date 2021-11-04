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
        return redirect('https://pvotdigital.com/pembayaran-sukses/'.$data->xendit_id);
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
