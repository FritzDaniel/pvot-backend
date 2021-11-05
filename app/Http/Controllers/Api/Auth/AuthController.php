<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\Memberships;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Validator;
use App\Http\Controllers\Api\BaseController as BaseController;

class AuthController extends BaseController
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors(),'Error',400);
        }

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = $request->user();
            $success['token'] = $user->createToken('User Login Token')->plainTextToken;
            $success['name'] = $user->name;
            $success['role'] = $user->userRole;

            activity()
                ->causedBy($user)
                ->createdAt(now())
                ->log($user->name.' is Login to your apps!');

            return $this->sendResponse($success, 'Success',200);
        }
        else{
            return $this->sendError(['error'=>'Wrong username or password.'],'Error',401);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => [
                'required',
                Password::min(6)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
            'c_password' => 'required|same:password',
            'phone' => 'required|unique:users|regex:/^([0-9\s\-\+\(\)]*)$/'
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors(),'Validation Error.',400);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $input['userRole'] = 'Dropshipper';

        $phoneValidation = substr($input['phone'], 0,1);

        if($phoneValidation == "0")
        {
            $input['phone'] = '+62'.substr($input['phone'], 1);
        }else {
            $input['phone'] = '+62'.$input['phone'];
        }
        $input['profilePicture'] = "/storage/img/dummyUser.jpg";
        $user = User::create($input);

        $user->assignRole('Dropshipper');

        event(new Registered($user));

        $success['name'] = $user->name;
        $success['role'] = "Dropshipper";
        $success['token'] = $user->createToken('User Register Token')->plainTextToken;

        $dataMembership = [
            'user_id' => $user->id,
            'membership' => false,
            'status' => 'Not Active',
        ];
        Memberships::create($dataMembership);

        activity()
            ->causedBy($user->id)
            ->createdAt(now())
            ->log($user->name.' Registered to your apps!');

        return $this->sendResponse($success, 'Success');
    }

    public function sendVerificationEmail(Request $request)
    {
        try {
            if($request->user()->hasVerifiedEmail())
            {
                return $this->sendError(['message' => 'Already Verified'], 'Error',400);
            }

            $request->user()->sendEmailVerificationNotification();

            activity()->log($request->user()->name.' is Send Verification Email');

            return $this->sendResponse(['message' => 'Verification link sent'],'Success', 200);
        }
        catch (\Exception $e) {
            return $this->sendError($e,'Error',400);
        }
    }

    public function verify(Request $request): RedirectResponse
    {
        $user = User::find($request->route('id'));

        if ($user->hasVerifiedEmail()) {
            return redirect('https://pvotdigital.com/verifikasi-sukses');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        activity()
            ->causedBy($user->id)
            ->createdAt(now())
            ->log($user->name.' Email is Verified');

        return redirect( 'https://pvotdigital.com/verifikasi-sukses');
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();

        activity()
            ->causedBy($user)
            ->createdAt(now())
            ->log($user->name.' is Logout');

        return $this->sendResponse($user, 'User logout successfully.');
    }
}
