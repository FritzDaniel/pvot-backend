<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Design extends Model
{
    use HasFactory;

    protected $table = 'design';
    protected $primaryKey ='id';
    protected $fillable = [
        'designName',
        'designImage'
    ];
    public $timestamps = true;
}
