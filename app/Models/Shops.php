<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shops extends Model
{
    use HasFactory;

    protected $table = 'shops';
    protected $primaryKey ='id';
    protected $fillable = [
        'user_id',
        'emailToko',
        'handphoneToko',
        'namaToko',
        'alamatToko',
        'fotoToko',
        'fotoHeaderToko',
        'kategoriToko',
        'supplier',
        'design',
        'descToko',
        'status'
    ];
    public $timestamps = true;

    public function userDetail()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
