<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Design;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Shops;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Withdraw;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function create()
    {
        $category = Category::all();
        return view('admin.supplier.create',compact('category'));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'aboutSupplier' => 'required',
            'category' => 'required'
        ]);

        if ($request->hasFile('profilePicture')) {
            if ($request->file('profilePicture')->isValid()) {
                $name = Carbon::now()->timestamp . '.' . $request->file('profilePicture')->getClientOriginalExtension();
                $store_path = 'storage/fotoUser';
                $request->file('profilePicture')->move($store_path, $name);
            }
        }

        $store = new User();
        $store->name = $request['name'];
        $store->email = $request['email'];
        $store->email_verified_at = Carbon::now();
        $store->password = bcrypt($request['password']);
        $store->userRole = "Supplier";
        $store->category = $request['category'];
        $store->informasiTambahan = $request['aboutSupplier'];
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
        $store->profilePicture = isset($name) ? '/storage/fotoUser/'.$name : '/storage/img/dummyUser.jpg';
        $store->country = $request['country'];
        $store->alamat = $request['address'];
        $store->provinsi = $request['province'];
        $store->kodepos = $request['postalCode'];
        $store->save();

        $store->assignRole('Supplier');

        $dataWallet = [
            'user_id' => $store->id,
            'balance' => 0
        ];
        Wallet::create($dataWallet);

        return redirect()->route('admin.supplier')->with('message','Supplier is successfully created');
    }

    public function supplierDetail($id)
    {
        $data = User::where('id','=',$id)->first();
        return view('admin.supplier.detail',compact('data'));
    }

    public function supplierTransaction($id)
    {
        $data = Payment::where('supplier_id','=',$id)
            ->orderBy('created_at','DESC')
            ->get();
        return view('admin.supplier.transaction',compact('data'));
    }

    public function supplierPassword($id)
    {
        $category = Category::orderBy('name','ASC')->get();
        $data = User::where('id','=',$id)->first();
        return view('admin.supplier.password',compact('data','category'));
    }

    public function passwordUpdate(Request $request,$id)
    {
        $store = User::find($id);
        if($request['category'])
        {
            $store->category = $request['category'];
        }
        if($request['password'])
        {
            $store->password = bcrypt($request['password']);
        }
        $store->update();

        return redirect()->route('admin.supplier')->with('message','Success update supplier data');
    }

    public function changeStatusWithdraw(Request $request,$id)
    {
        if ($request->hasFile('buktiTransfer')) {
            if ($request->file('buktiTransfer')->isValid()) {
                $name = Carbon::now()->timestamp . '.' . $request->file('buktiTransfer')->getClientOriginalExtension();
                $store_path = 'public/buktiTransfer';
                $request->file('buktiTransfer')->storeAs($store_path, $name);
            }
        }

        $data = Withdraw::where('id','=',$id)->first();
        $data->status = "Settle";
        $data->buktiTransfer = isset($name) ? '/storage/buktiTransfer/'.$name : null;
        $data->update();
        return $this->sendResponse($data, 'Success');
    }

    public function processed($id)
    {
        $data = Withdraw::where('uuid','=',$id)->first();
        $data->status = "Processed";
        $data->update();

        return redirect()->back()->with('message','Product status is successfully updated');
    }

    public function upload($id)
    {
        $data = Withdraw::where('uuid','=',$id)->first();
        return view('admin.withdraw.upload',compact('data'));
    }

    public function storeTransferReceipt(Request $request, $id)
    {
        $this->validate($request,[
            'buktiTransfer' => 'required'
        ]);

        if ($request->hasFile('buktiTransfer')) {
            if ($request->file('buktiTransfer')->isValid()) {
                $name = Carbon::now()->timestamp . '.' . $request->file('buktiTransfer')->getClientOriginalExtension();
                $store_path = 'public/buktiTransfer';
                $request->file('buktiTransfer')->storeAs($store_path, $name);
            }
        }

        $data = Withdraw::where('uuid','=',$id)->first();
        $data->status = "Settle";
        $data->buktiTransfer = isset($name) ? '/storage/buktiTransfer/'.$name : null;
        $data->save();

        return redirect()->route('admin.supplier')->with('message','Upload receipt is success');
    }

    public function delete($id)
    {
        $data = User::where('id','=',$id)->first();
        if($data->profilePicture !== "/storage/img/dummy.jpg")
        {
            $images_path = public_path().$data->profilePicture;
            if (is_file($images_path)) {
                unlink($images_path);
            }
        }

        $dataPayment = Payment::where('supplier_id','=',$id)->get();

        if(count($dataPayment) > 0)
        {
            foreach ($dataPayment as $pay) {
                $pay->supplier_id = null;
                $pay->update();
            }
        }

        $dataWallet = Wallet::where('user_id','=',$id)->first();
        $dataWallet->delete();

        $dataProduct = Product::where('supplier_id','=',$id)->get();
        if(count($dataProduct) > 0)
        {
            foreach ($dataProduct as $pr) {
                if($pr->productPicture !== "/storage/img/dummy.jpg")
                {
                    $images_path = public_path().$pr->productPicture;
                    if (is_file($images_path)) {
                        unlink($images_path);
                    }
                }
                $pr->delete();
            }
        }

        $dataShop = Shops::where('supplier_id','=',$id)->get();
        if(count($dataShop) > 0)
        {
            foreach ($dataShop as $ds) {
                $ds->supplier_id = null;
                $ds->update();
            }
        }

        $design = Design::where('supplier_id','=',$id)->get();
        if(count($design) > 0)
        {
            foreach ($design as $dsgn) {
                $dsgn->supplier_id = null;
                $dsgn->update();
            }
        }
        $data->delete();
        return redirect()->route('admin.supplier')->with('message','Delete supplier success');
    }
}
