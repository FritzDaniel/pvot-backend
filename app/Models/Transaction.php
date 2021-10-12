<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transaction';
    protected $primaryKey ='id';
    protected $fillable = [
        'user_id',
        'product_id',
        'payment_id',
        'qty',
        'transaction_date'
    ];
    public $timestamps = true;
}
