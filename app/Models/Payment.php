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
        'supplier_id',
        'shop_id',
        'payment_channel',
        'payment_bank',
        'email',
        'price',
        'status',
        'description',
        'receiptImage',
        'receiptNumber'
    ];
    public $timestamps = true;

    public function Transaction()
    {
        return $this->hasMany(Transaction::class,'payment_id','id')->with('Product');
    }

    public function User()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function Shop()
    {
        return $this->belongsTo(Shops::class,'shop_id');
    }
}
