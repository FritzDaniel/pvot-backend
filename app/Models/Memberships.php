<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Memberships extends Model
{
    use HasFactory;

    protected $table = 'memberships';
    protected $primaryKey ='id';
    protected $fillable = [
        'user_id',
        'marketplaceCount',
        'marketplaceSelect',
        'status',
        'paymentChannel',
        'price',
        'expiredDate'
    ];
    public $timestamps = true;
}
