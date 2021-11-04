<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoRek extends Model
{
    use HasFactory;

    protected $table = 'no_rek';
    protected $primaryKey ='id';
    protected $fillable = [
        'supplier_id',
        'bank',
        'account_number',
    ];
    public $timestamps = true;
}
