<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Api\BaseController;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Password as ForgotPassword;
use Illuminate\Support\Str;
use Validator;

class UsersController extends BaseController
{
    public function getWallet(Request $request)
    {
        try {
            $user = $request->user();
            $data = Wallet::where('user_id', '=', $user->id)->first();
            return $this->sendResponse($data, 'Wallet Data.');
        } catch (\Exception $e) {
            return $this->sendError('There something wrong!', $e);
        }
    }

    public function transactionHistory(Request $request)
    {
        $user = $request->user();
        if($user->roles[0]->name == "Supplier")
        {
            $data = Transaction::with('Product')->where('supplier_id','=',$user->id)->paginate(10);
        }else {
            $data = Transaction::with('Product')->where('user_id','=',$user->id)->paginate(10);
        }
        return $this->sendResponse($data,'Success');
    }

    public function profile(Request $request)
    {
        $user = $request->user();
        $role = $user->getRoleNames();

        if($role[0] == 'Superadmin') {
            $data = User::with(['roles'])
                ->where('id','=',$user->id)
                ->first();
        }
        else if($role[0] == 'Supplier') {
            $data = User::with(['roles','EWallet'])
                ->where('id','=',$user->id)
                ->first();
        }
        else {
            $data = User::with(['roles','membership'])
                ->where('id','=',$user->id)
                ->first();
        }

        return $this->sendResponse($data, 'Profile Data.');
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'unique:users|regex:/^([0-9\s\-\+\(\)]*)$/'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors(), 'Validation Error.', 400);
        }

        if ($request->hasFile('profilePicture')) {
            if ($request->file('profilePicture')->isValid()) {
                $name = Carbon::now()->timestamp . '.' . $request->file('profilePicture')->getClientOriginalExtension();
                $store_path = 'public/fotoUser';
                $request->file('profilePicture')->storeAs($store_path, $name);
            }
        }

        $user = $request->user();
        $update = User::where('id', '=', $user->id)->first();
        if ($request->hasFile('profilePicture')) {
            $update->profilePicture = isset($name) ? '/storage/fotoUser/'.$name : null;
        }
        if ($request['namaPerusahaan'])
        {
            $update->namaPerusahaan = $request['namaPerusahaan'];
        }
        if ($request['country'])
        {
            $update->country = $request['country'];
        }
        if ($request['alamat'])
        {
            $update->alamat = $request['alamat'];
        }
        if ($request['city'])
        {
            $update->city = $request['city'];
        }
        if ($request['provinsi'])
        {
            $update->provinsi = $request['provinsi'];
        }
        if ($request['kodepos'])
        {
            $update->kodepos = $request['kodepos'];
        }
        if ($request['informasiTambahan'])
        {
            $update->informasiTambahan = $request['informasiTambahan'];
        }
        $update->update();

        activity()
            ->causedBy($user)
            ->createdAt(now())
            ->log($user->name.' is update the data.');

        return $this->sendResponse($user, 'Successfully update user.');
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => [
                'required', function ($attribute, $value, $fail) {
                    if (!Hash::check($value, Auth::user()->password)) {
                        $fail('Old Password didn\'t match');
                    }
                },
            ],
            'new_password' => [
                'required',
                Password::min(6)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
            'confirm_password' => 'required|same:new_password',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors(), 'Validation Error.', 400);
        }
        $user = $request->user();
        $update = User::where('id','=',$user->id)->first();
        $update->password = bcrypt($request['new_password']);
        $update->update();

        activity()
            ->causedBy($user)
            ->createdAt(now())
            ->log($user->name.' is update his password.');

        return $this->sendResponse($update, 'Update password success.');
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if($validator->fails()) {
            return $this->sendError($validator->errors(),'Validation Error.',400);
        }

        $emailValid = User::where('email','=',$request['email'])->first();

        if($emailValid == null)
        {
            return $this->sendError(null,'This email is not registered!',400);
        }

        $status = ForgotPassword::sendResetLink(
            $request->only('email')
        );

        if($status == ForgotPassword::RESET_LINK_SENT) {
            return $this->sendResponse(['message' => "Change password link has been sent to your email"], "Success");
        }

        $this->sendError(['error'=>'Error forgot password'],'Error',400);
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => [
                'required',
                \Illuminate\Validation\Rules\Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
            'c_password' => 'required|same:password',
        ]);

        if($validator->fails()) {
            return $this->sendError($validator->errors(),'Error.',400);
        }

        $status = ForgotPassword::reset(
            $request->only('email','password','c_password','token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => bcrypt($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                activity()
                    ->causedBy($user->id)
                    ->createdAt(now())
                    ->log($user->name.' is Change Password');

                event(new PasswordReset($user));
            }
        );

        if($status == ForgotPassword::PASSWORD_RESET) {
            return $this->sendResponse(['message' => 'Password reset successfully'], 'Success');
        }

        return $this->sendError(["error" => "Error when change the password"],400);
    }

    public function getProduct($id)
    {
        $data = Product::with(['userDetail', 'productPhoto', 'productVariant'])
            ->where('id','=',$id)
            ->first();

        return $this->sendResponse($data,'Success');
    }
}
