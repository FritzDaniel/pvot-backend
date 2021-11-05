<?php

namespace App\Http\Controllers\Suppliers;

use App\Models\NoRek;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use App\Models\Withdraw;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        return view('supplier.dashboard');
    }

    public function product()
    {
        $user = Auth::user();
        $data = Product::where('supplier_id','=',$user->id)->get();

        return view('supplier.product.index',compact('data'));
    }

    public function orders()
    {
        $data = Payment::where('supplier_id','=',Auth::user()->id)
            ->where('status','=','Paid')
            ->get();
        return view('supplier.order.index',compact('data'));
    }

    public function transactionHistory()
    {
        return view('supplier.history.index');
    }

    public function withdraw()
    {
        $noRek = NoRek::where('supplier_id','=',Auth::user()->id)->get();
        $data = Withdraw::where('user_id','=',Auth::user()->id)->get();
        return view('supplier.withdraw.index',compact('data','noRek'));
    }

    public function profile()
    {
        $data = User::find(Auth::user()->id);
        return view('supplier.profile.index',compact('data'));
    }

    public function updateProfile(Request $request)
    {
        $this->validate($request,[
            'name' => 'required',
            'address' => 'required'
        ]);

        if($request['phone'])
        {
            $this->validate($request,[
                'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/'
            ]);
        }

        if($request['new_password'])
        {
            $this->validate($request,[
                'old_password' => [
                    'required', function ($attribute, $value, $fail) {
                        if (!Hash::check($value, Auth::user()->password)) {
                            $fail('Incorrect old password');
                        }
                    },
                ],
                'new_password' => [
                    'required',
                ],
                'c_password' => 'required|same:new_password',
            ]);
        }

        if ($request->hasFile('profilePicture')) {
            if ($request->file('profilePicture')->isValid()) {
                $name = Carbon::now()->timestamp . '.' . $request->file('profilePicture')->getClientOriginalExtension();
                $store_path = 'public/fotoUser';
                $request->file('profilePicture')->storeAs($store_path, $name);
            }
        }

        $store = User::where('id', '=', Auth::user()->id)->first();
        if($request['name'])
        {
            $store->name = $request['name'];
        }
        if($request['new_password'])
        {
            $store->password = bcrypt($request['new_password']);
        }
        if($request['phone'])
        {
            $phoneValidation = substr($request['phone'], 0,1);

            if($phoneValidation == "0")
            {
                $store->phone = '+62'.substr($request['phone'], 1);
            }else {
                $store->phone = '+62'.$request['phone'];
            }
            $store->phone = $request['phone'];
        }
        if ($request->hasFile('profilePicture'))
        {
            if($store->profilePicture !== "/storage/img/dummyUser.jpg")
            {
                $images_path = public_path().$store->profilePicture;
                unlink($images_path);
            }
            $store->profilePicture = isset($name) ? '/storage/fotoUser/'.$name : '/storage/img/dummyUser.jpg';
        }
        if($request['country'])
        {
            $store->country = $request['country'];
        }
        if($request['address'])
        {
            $store->alamat = $request['address'];
        }
        if($request['province'])
        {
            $store->provinsi = $request['province'];
        }
        if ($request['postalCode'])
        {
            $store->kodepos = $request['postalCode'];
        }
        $store->update();

        return redirect()->back()->with('message','Profile update successfully');
    }
}
