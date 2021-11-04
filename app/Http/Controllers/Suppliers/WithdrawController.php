<?php

namespace App\Http\Controllers\Suppliers;

use App\Http\Controllers\Controller;
use App\Models\NoRek;
use App\Models\TransactionSequence;
use App\Models\Wallet;
use App\Models\Withdraw;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WithdrawController extends Controller
{
    public function storeNoRek(Request $request)
    {
        $this->validate($request,[
            'bank' => 'required',
            'account_number' => 'required'
        ]);

        $store = new NoRek();
        $store->supplier_id = Auth::user()->id;
        $store->bank = $request['bank'];
        $store->account_number = $request['account_number'];
        $store->save();

        return redirect()->back()->with('message','Account is successfully created');
    }

    public function deleteNoRek($id)
    {
        $data = NoRek::find($id);
        $data->delete();
        return redirect()->back()->with('message','Account is successfully deleted');
    }

    public function create()
    {
        $noRek = NoRek::all();
        return view('supplier.withdraw.create',compact('noRek'));
    }

    public function store(Request $request)
    {
        $messages = [
            'amount.gt'  => 'The amount must be greater than 500000',
        ];
        $this->validate($request,[
            'bank' => 'required',
            'amount' => 'required|gt:499999|numeric'
        ],$messages);

        $noRek = NoRek::find($request['bank']);
        $wallet = Wallet::where('user_id', '=', Auth::user()->id)->first();

        if($request['amount'] > $wallet->balance)
        {
            return redirect()->back()->with('error','Insufficient balance, please check your balance');
        }

        $runningSeq = TransactionSequence::where('user_id','=',Auth::user()->id)
            ->first();

        if($runningSeq == null)
        {
            $storeSequence = new TransactionSequence();
            $storeSequence->user_id = Auth::user()->id;
            $storeSequence->type = "SP";
            $storeSequence->running_seq = 1;
            $storeSequence->save();

            $uuid = $storeSequence->type.$storeSequence->user_id.$storeSequence->running_seq.Carbon::parse($storeSequence->created_at)->format('dmY');
        }else {
            $uuid = $runningSeq->type.$runningSeq->user_id.$runningSeq->running_seq.Carbon::parse($runningSeq->created_at)->format('dmY');
        }


        $store = new Withdraw();
        $store->uuid = $uuid;
        $store->user_id = $noRek->supplier_id;
        $store->bank = $noRek->bank;
        $store->no_rek = $noRek->account_number;
        $store->amount = $request['amount'];
        $store->status = "Pending";
        $store->save();

        $wallet->balance = $wallet->balance - $request['amount'];
        $wallet->update();

        $updateSequence = TransactionSequence::where('user_id','=',Auth::user()->id)->first();
        $updateSequence->running_seq = $updateSequence->running_seq + 1;
        $updateSequence->update();

        return redirect()
            ->route('supplier.withdraw')
            ->with('message','Withdraw is successfully created, Waiting for admin approval');
    }
}
