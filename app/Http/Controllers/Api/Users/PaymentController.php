<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Api\BaseController;
use App\Models\Mutation;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ReceiptPayment;
use App\Models\Settings;
use App\Models\Transaction;
use App\Models\TransactionSequence;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\Request;

class PaymentController extends BaseController
{
    public function getPaymentAndRedirect($id)
    {
        $data = Payment::where('external_id','=',$id)->first();
        return redirect('https://pvotdigital.com/pembayaran-sukses/'.$data->xendit_id);
    }

    public function getOrderList(Request $request)
    {
        $from = $request->query('tanggal_awal');
        $to = $request->query('tanggal_akhir');
        $status = $request->query('status');
        $user = $request->user();

        if($status == "Semua")
        {
            if($from !== null && $to !== null)
            {
                $data = Payment::with(['Transaction'])
                    ->whereBetween('created_at', [$from.' 00:00:00', $to.' 23.59.59'])
                    ->where('description','=','Pembayaran Product')
                    ->where('user_id','=',$user->id)
                    ->orderBy('created_at','DESC')
                    ->paginate(5);
            }else {
                $data = Payment::with(['Transaction'])
                    ->where('description','=','Pembayaran Product')
                    ->where('user_id','=',$user->id)
                    ->orderBy('created_at','DESC')
                    ->paginate(5);
            }
        }
        elseif($status == "Dikemas")
        {
            if($from !== null && $to !== null)
            {
                $data = Payment::with(['Transaction'])
                    ->whereBetween('created_at', [$from.' 00:00:00', $to.' 23.59.59'])
                    ->where('description','=','Pembayaran Product')
                    ->where('user_id','=',$user->id)
                    ->where('status','=','Processed')
                    ->orderBy('created_at','DESC')
                    ->paginate(5);
            }else {
                $data = Payment::with(['Transaction'])
                    ->where('description','=','Pembayaran Product')
                    ->where('user_id','=',$user->id)
                    ->where('status','=','Processed')
                    ->orderBy('created_at','DESC')
                    ->paginate(5);
            }
        }
        elseif($status == "Dikirim")
        {
            if($from !== null && $to !== null)
            {
                $data = Payment::with(['Transaction'])
                    ->whereBetween('created_at', [$from.' 00:00:00', $to.' 23.59.59'])
                    ->where('description','=','Pembayaran Product')
                    ->where('user_id','=',$user->id)
                    ->where('status','=','Sent')
                    ->orderBy('created_at','DESC')
                    ->paginate(5);
            }else {
                $data = Payment::with(['Transaction'])
                    ->where('description','=','Pembayaran Product')
                    ->where('user_id','=',$user->id)
                    ->where('status','=','Sent')
                    ->orderBy('created_at','DESC')
                    ->paginate(5);
            }
        }
        elseif($status == "Berhasil")
        {
            if($from !== null && $to !== null)
            {
                $data = Payment::with(['Transaction'])
                    ->whereBetween('created_at', [$from.' 00:00:00', $to.' 23.59.59'])
                    ->where('description','=','Pembayaran Product')
                    ->where('user_id','=',$user->id)
                    ->where('status','=','Complete')
                    ->orderBy('created_at','DESC')
                    ->paginate(5);
            }else {
                $data = Payment::with(['Transaction'])
                    ->where('description','=','Pembayaran Product')
                    ->where('user_id','=',$user->id)
                    ->where('status','=','Complete')
                    ->orderBy('created_at','DESC')
                    ->paginate(5);
            }
        }else {
            $data = Payment::with(['Transaction'])
                ->where('description','=','Pembayaran Product')
                ->where('user_id','=',$user->id)
                ->orderBy('created_at','DESC')
                ->paginate(5);
        }

        return $this->sendResponse($data,'Success');
    }

    public function getTransactionList($id)
    {
        $data = Transaction::with(['Product','User'])
            ->where('transaction_id','=',$id)
            ->get();
        return $this->sendResponse($data,'Success');
    }

    public function getWebhookCallback($bank)
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
                                ->where('price','=',$jquin['amount'])
                                ->first();

                if($jOrder !== null)
                {
                    $idOrder = $jOrder->external_id;

                    $data = array(
                        'bank_id' => $bank,
                        'account_number' => $jquin['account_number'],
                        'date' => date('Y-m-d H:i:s'),
                        'amount' => $jquin['amount'],
                        'description' => $jquin['description'],
                        'type' => $jquin['type'],
                        'balance' => $jquin['balance'],
                        'kode_unik' => $kode_unik,
                        'id_order' => $idOrder,
                        'user_id' => $jOrder->user_id
                    );
                    Mutation::create($data);

                    if($jOrder->status == "Pending")
                    {
                        $jOrder->status = "Paid";
                        $jOrder->update();
                    }
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
        $dataPayment = Payment::with(['Transaction'])
            ->where('external_id','=',$invoice_id)->first();

        if($dataPayment == null)
        {
            return $this->sendError(['message' => 'Data tidak ada'],'Error',400);
        }

        return $this->sendResponse(
            $dataPayment,
            'Success'
        );
    }

    public function orderComplete($id)
    {
        $Order = Payment::where('external_id','=',$id)
            ->where('status','=','Sent')
            ->first();

        if($Order == null)
        {
            return $this->sendError(['error' => 'Data tidak ada'],'Error',400);
        }

        $Order->status = "Complete";
        $Order->update();

        $price = Transaction::where('transaction_id','=',$Order->external_id)->get();
        $total = 0;
        $supplier_id = 0;

        foreach ($price as $prc)
        {
            $product = Product::where('uuid','=',$prc->product_id)->first();
            $total += $product->productPrice * $prc->qty;
            $supplier_id = $prc->supplier_id;
        }

        $wallet = Wallet::where('user_id','=',$supplier_id)->first();
        $sumBalance = $wallet->balance + $total;
        $wallet->balance = $sumBalance;
        $wallet->update();

        return $this->sendResponse($Order,'Success');
    }

    public function cartSummary($id)
    {
        $data = Payment::with(['Transaction','Receipt'])
            ->where('external_id','=',$id)->first();
        return $this->sendResponse($data,'Success');
    }

    public function beliProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'shop_id' => 'required',
            'supplier_id' => 'required',
            'items' => 'required',
            'payment_bank' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors(),'Error',400);
        }

        $totalPrice = 0;
        foreach ($request['items'] as $total)
        {
            $price = Product::where('uuid','=',$total['product_id'])->first();
            $totalPrice += $price->showPrice * $total['quantity'];
        }

        $user = $request->user();

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

        $biayaAdminProduct = Settings::find(2);
        $amount = $totalPrice + $biayaAdminProduct->value;

        $user_id = $user->id;

        $dataPayment = [
            'external_id' => $external_id,
            'uniq_code' =>  substr($amount, -3),
            'user_id' => $user_id,
            'supplier_id' => $request['supplier_id'],
            'shop_id' => $request['shop_id'],
            'payment_channel' => 'Moota',
            'payment_bank' => $request['payment_bank'],
            'email' => $user->email,
            'price' => $amount,
            'description' => 'Pembayaran Product',
        ];
        $payment = Payment::create($dataPayment);

        foreach ($request['items'] as $itm)
        {
            $store = new Transaction();
            $store->transaction_id = $payment->external_id;
            $store->transaction_date = Carbon::now();
            $store->user_id = $user->id;
            $store->supplier_id = $request['supplier_id'];
            $store->product_id = $itm['product_id'];
            $store->qty = $itm['quantity'];
            $store->variants = $itm['variants'];
            $store->save();

            $product = Product::where('uuid','=',$itm['product_id'])->first();
            $product->productStock = $product->productStock - $itm['quantity'];
            $product->update();
        }

        $updateSequence = TransactionSequence::where('user_id','=',$user->id)->first();
        $updateSequence->running_seq = $updateSequence->running_seq + 1;
        $updateSequence->update();

        return $this->sendResponse($payment,'Store cart success');
    }

    public function createReceipt(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bukti_resi' => 'required',
            'no_resi' => 'required',
            'payment_id' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors(),'Error',400);
        }

        if ($request->hasFile('bukti_resi')){
            if ($request->file('bukti_resi')->isValid()){
                $name = Carbon::now()->timestamp.'.'.$request->file('bukti_resi')->getClientOriginalExtension();
                $store_path = 'public/buktiResi';
                $request->file('bukti_resi')->storeAs($store_path,$name);
            }
        }

        $dataResi = new ReceiptPayment();
        $dataResi->payment_id = $request['payment_id'];
        $dataResi->receiptImage = isset($name) ? 'storage/buktiResi/'.$name : null;
        $dataResi->receiptNumber = $request['no_resi'];
        $dataResi->save();

        return $this->sendResponse($dataResi,'Success');
    }
}
