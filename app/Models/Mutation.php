<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mutation extends Model
{
    use HasFactory;

    protected $table = 'mutation';
    protected $primaryKey ='id';
    protected $fillable = [
        'bank_id',
        'account_number',
        'date',
        'amount',
        'description',
        'type',
        'balance',
        'kode_unik',
        'id_order',
        'user_id'
    ];
    public $timestamps = true;

    public function User()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
