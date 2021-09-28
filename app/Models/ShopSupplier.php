<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopSupplier extends Model
{
    use HasFactory;

    protected $table = 'shop_supplier';
    protected $primaryKey ='id';
    protected $fillable = [
        'user_id',
        'shop_id',
        'supplier_id',
    ];
    public $timestamps = true;
}
