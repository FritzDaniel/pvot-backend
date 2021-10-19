<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Api\BaseController;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Validator;

class UsersController extends BaseController
{
    public function getWallet(Request $request)
    {
        try {
            $user = $request->user();
            $data = Wallet::where('user_id','=',$user->id)->first();
            return $this->sendResponse($data,'Wallet Data.');
        }catch (\Exception $e) {
            return $this->sendError('There something wrong!',$e);
        }
    }

    public function profile(Request $request)
    {
        $user = $request->user();
        $data = User::with(['roles','userDetail','membership'])
            ->where('id','=',$user->id)
            ->first();

        return $this->sendResponse($data, 'Profile Data.');
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

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if($status == Password::RESET_LINK_SENT) {
            return $this->sendResponse(null, "Change password link has been sent to your email");
        }

        $this->sendError(trans($status),'Error forgot password',400);
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
            return $this->sendError($validator->errors(),'Validation Error.',400);
        }

        $status = Password::reset(
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

        if($status == Password::PASSWORD_RESET) {
            return $this->sendResponse(null, 'Password reset successfully');
        }

        return $this->sendError($status, "Error when change the password",400);
    }
}
