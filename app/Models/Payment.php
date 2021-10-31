<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';
    protected $primaryKey ='id';
    protected $fillable = [
        'xendit_id',
        'external_id',
        'uniq_code',
        'user_id',
        'payment_channel',
        'email',
        'price',
        'status',
        'description'
    ];
    public $timestamps = true;

    public function Transaction()
    {
        return $this->hasMany(Transaction::class,'payment_id','id')->with('Product');
    }
}
