<?php

namespace App\Http\Controllers\Suppliers;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function changeStatus($id)
    {
        $data = Payment::where('external_id','=',$id)
            ->first();

        return view('supplier.order.status',compact('data'));
    }

    public function detailTransaction($id)
    {
        $data = Payment::where('external_id','=',$id)
            ->first();
        return view('supplier.order.detail',compact('data'));
    }

    public function updateStatus(Request $request, $id)
    {
        $data = Payment::where('external_id','=',$id)
            ->where('status','!=',"Complete")
            ->first();
        if($data == null)
        {
            return redirect()->route('supplier.orders')->with('error','Failed to update the status');
        }
        $data->status = $request['status'];
        $data->update();

        return redirect()->route('supplier.orders')->with('message','Status updated');
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

        return redirect()->route('supplier.orders')->with('message','Status updated');
    }
}
