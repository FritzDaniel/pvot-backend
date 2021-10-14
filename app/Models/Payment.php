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
        'user_id',
        'payment_channel',
        'email',
        'price',
        'status',
        'description'
    ];
    public $timestamps = true;
}
