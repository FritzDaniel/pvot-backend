<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transaction';
    protected $primaryKey = 'id';
    protected $fillable = [
        'transaction_id',
        'transaction_date',
        'user_id',
        'product_id',
        'qty',
        'transaction_date',
        'variants'
    ];
    public $timestamps = true;

    public function Product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function Payment()
    {
        return $this->belongsTo(Payment::class,'payment_id');
    }
}
