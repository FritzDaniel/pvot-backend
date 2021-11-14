<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use HasFactory;

    protected $table = 'edukasi';
    protected $primaryKey ='id';
    protected $fillable = [
        'group',
        'title',
        'url_youtube',
        'status',
        'paymentChannel',
        'price',
        'expiredDate'
    ];
    public $timestamps = true;
}
