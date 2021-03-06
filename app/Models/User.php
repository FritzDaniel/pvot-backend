<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasRoles, HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'profilePicture',
        'namaPerusahaan',
        'country',
        'alamat',
        'city',
        'provinsi',
        'kodepos',
        'informasiTambahan',
        'userRole'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sendPasswordResetNotification($token)
    {
        $appUrl = 'https://pvotdigital.com/';
        $url = $appUrl.'ubah-password?token='.$token;

        $this->notify(new ResetPasswordNotification($url));
    }

    public function EWallet()
    {
        return $this->hasOne(Wallet::class,'user_id','id');
    }

    public function Membership()
    {
        return $this->hasOne(Memberships::class,'user_id','id');
    }

    public function isAdmin(){
        $check = User::where([
            ['userRole','=','Superadmin'],
            ['id','=',$this->id]
        ])->first();
        return $check != null;
    }

    public function isSupplier(){
        $check = User::where([
            ['userRole','=','Supplier'],
            ['id','=',$this->id]
        ])->first();
        return $check != null;
    }
}
