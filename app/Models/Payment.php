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
        'external_id',
        'payment_channel',
        'email',
        'price',
        'status'
    ];
    public $timestamps = true;
}
