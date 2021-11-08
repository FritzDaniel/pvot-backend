<?php

namespace App\Http\Controllers\Suppliers;

use App\Http\Controllers\Controller;
use App\Models\Payment;
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
}
