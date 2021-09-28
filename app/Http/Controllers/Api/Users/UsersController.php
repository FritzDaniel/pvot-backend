<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Wallet;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Mockery\Generator\StringManipulation\Pass\Pass;
use Throwable;
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

        if($user->hasVerifiedEmail()) {
            return $this->sendResponse($user, 'Profile Data.');
        } else {
            return $this->sendError('Verify email first', null,400);
        }
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if($status == Password::RESET_LINK_SENT) {
            return $this->sendResponse(null, "Change password link has been sent to your email");
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
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

        return $this->sendError(null, $status,500);
    }
}
