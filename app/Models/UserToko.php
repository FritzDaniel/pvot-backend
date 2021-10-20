<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserToko extends Model
{
    use HasFactory;

    protected $table = 'user_toko';
    protected $primaryKey ='id';
    protected $fillable = [
        'user_id',
        'tokoCount',
        'marketplaceCount',
        'marketplaceSelect',
        'status'
    ];
    public $timestamps = true;
}
