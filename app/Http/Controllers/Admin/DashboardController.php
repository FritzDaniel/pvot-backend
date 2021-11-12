<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Design;
use App\Models\Mutation;
use App\Models\Payment;
use App\Models\Settings;
use App\Models\Shops;
use App\Models\Testimoni;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserToko;
use App\Models\Variant;
use App\Models\Withdraw;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }

    # Dropshipper
    public function dropshipper()
    {
        $data = User::where('userRole','=','Dropshipper')->get();
        return view('admin.dropshipper.index',compact('data'));
    }

    public function dropshipperDetail($id)
    {
        $data = User::where('id','=',$id)->first();
        return view('admin.dropshipper.detail',compact('data'));
    }

    public function dropshipperTransaction($id)
    {
        $data = Payment::where('user_id','=',$id)->get();
        return view('admin.dropshipper.transaction',compact('data'));
    }

    public function dropshipperPassword($id)
    {
        $data = User::where('id','=',$id)->first();
        return view('admin.dropshipper.password',compact('data'));
    }

    public function passwordUpdate(Request $request,$id)
    {
        $this->validate($request,[
            'password' => 'required',
        ]);

        $store = User::find($id);
        $store->password = bcrypt($request['password']);
        $store->update();

        return redirect()->back()->with('message','Success update password user');
    }

    #Toko
    public function toko()
    {
        $data = UserToko::whereHas('Payment', function ($q) {
            $q->where('status','Paid');
        })
        ->get();
        return view('admin.toko.index',compact('data'));
    }

    # Supplier
    public function supplier()
    {
        $data = User::where('userRole','=','Supplier')->get();
        return view('admin.supplier.index',compact('data'));
    }

    # Withdraw
    public function withdraw()
    {
        $data = Withdraw::orderBy('created_at','DESC')->get();
        return view('admin.withdraw.index',compact('data'));
    }

    # Category
    public function category()
    {
        $data = Category::orderBy('created_at','DESC')->get();
        return view('admin.category.index',compact('data'));
    }

    # Variant
    public function variant()
    {
        $data = Variant::orderBy('created_at','DESC')->get();
        return view('admin.variant.index',compact('data'));
    }

    # Design
    public function design()
    {
        $data = Design::orderBy('created_at','DESC')->get();
        return view('admin.design.index',compact('data'));
    }

    # Testimony
    public function testimony()
    {
        $data = Testimoni::orderBy('created_at','DESC')->get();
        return view('admin.testimony.index',compact('data'));
    }

    # Logs
    public function logs()
    {
        $data = Activity::with('causer')
            ->orderBy('created_at','DESC')
            ->get();
        return view('admin.log.index',compact('data'));
    }

    # Mutation
    public function mutation()
    {
        $data = Mutation::orderBy('created_at','DESC')->get();
        return view('admin.mutation.index',compact('data'));
    }

    # Settings
    public function settings()
    {
        $data = Settings::all();
        return view('admin.setting.index',compact('data'));
    }

    # Settings
    public function ticket()
    {
        $data = Ticket::orderBy('created_at','DESC')->get();
        return view('admin.ticket.index',compact('data'));
    }
}
