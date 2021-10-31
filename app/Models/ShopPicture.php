<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopPicture extends Model
{
    use HasFactory;

    protected $table = 'shops';
    protected $primaryKey ='id';
    protected $fillable = [
        'shop_id',
        'fotoToko',
        'fotoHeaderToko',
    ];
    public $timestamps = true;
}
