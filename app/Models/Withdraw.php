<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    use HasFactory;

    protected $table = 'withdraw';
    protected $primaryKey ='id';
    protected $fillable = [
        'user_id',
        'bank',
        'no_rek',
        'amount',
        'status',
        'buktiTransfer'
    ];
    public $timestamps = true;

    public function Supplier()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
