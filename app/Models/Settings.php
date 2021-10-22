<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;

    protected $table = 'settings';
    protected $primaryKey ='id';
    protected $fillable = [
        'name',
        'value',
        'tipe'
    ];
    public $timestamps = true;
}