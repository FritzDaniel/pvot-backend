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
        'supplier_id',
        'shop_id',
        'designName',
        'designImage'
    ];
    public $timestamps = true;

    public function Supplier()
    {
        return $this->belongsTo(User::class,'supplier_id');
    }

    public function Shop()
    {
        return $this->belongsTo(Shops::class,'shop_id');
    }
}
