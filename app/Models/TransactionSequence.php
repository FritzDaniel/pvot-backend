<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionSequence extends Model
{
    use HasFactory;

    protected $table = 'transaction_sequence';
    protected $primaryKey = 'id';
    protected $fillable = [
        'type',
        'user_id',
        'running_seq'
    ];
    public $timestamps = true;
}
