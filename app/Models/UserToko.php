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
        'transaction_id',
        'tokoCount',
        'marketplaceCount',
        'marketplaceSelect',
    ];
    public $timestamps = true;

    public function Payment()
    {
        return $this->belongsTo(Payment::class,'transaction_id','external_id');
    }
}
