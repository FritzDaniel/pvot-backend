<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Http\Controllers\Api\BaseController as BaseController;

class AuthController extends BaseController
{
    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            $success['token'] = $user->createToken('User Login Token')->plainTextToken;
            $success['name'] = $user->name;

            activity()
                ->causedBy($user->id)
                ->createdAt(now())
                ->log($user->name.' is Login to your apps!');

            return $this->sendResponse($success, 'User login successfully.');
        }
        else{
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'c_password' => 'required|same:password',
            'phone' => 'required|unique:users'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors(),400);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        event(new Registered($user));

        $success['token'] = $user->createToken('User Register Token')->plainTextToken;
        $success['name'] = $user->name;

        $dataWallet = [
            'user_id' => $user->id,
            'balance' => 0
        ];
        Wallet::create($dataWallet);

        activity()
            ->causedBy($user->id)
            ->createdAt(now())
            ->log('Someone is Registered to your apps!');

        return $this->sendResponse($success, 'User register successfully.');
    }

    public function sendVerificationEmail(Request $request)
    {
        try {
            if($request->user()->hasVerifiedEmail())
            {
                return $this->sendError('Already Verified', null, 400);
            }

            $request->user()->sendEmailVerificationNotification();

            activity()->log($request->user()->name.' is Send Verification Email');

            return $this->sendResponse(null, 'Verification link sent');
        }catch (\Exception $e) {
            return $this->sendError($e,'Error Send Email');
        }
    }

    public function verify(Request $request): RedirectResponse
    {
        $user = User::find($request->route('id'));

        if ($user->hasVerifiedEmail()) {
            return redirect(env('FRONT_URL') . 'email/verify/already-success');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        activity()
            ->causedBy($user->id)
            ->createdAt(now())
            ->log($user->name.' Email is Verified');

        return redirect(env('FRONT_URL') . 'register/verified');
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();

        activity()
            ->causedBy($user->id)
            ->createdAt(now())
            ->log($user->name.' is Logout');

        return $this->sendResponse($user, 'User logout successfully.');
    }
}
