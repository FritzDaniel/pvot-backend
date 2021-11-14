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
        'category_id',
        'supplier_id',
        'design_id',
        'description',
        'status'
    ];
    public $timestamps = true;

    public function User()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function OrderCount()
    {
        $data = Payment::where('shop_id','=',$this->id)
            ->where('status','!=','Pending')
            ->get();

        return count($data);
    }

    public function completeOrder()
    {
        $data = Payment::where('shop_id','=',$this->id)
            ->where('status','=','Complete')
            ->get();

        return count($data);
    }

    public function Supplier()
    {
        return $this->belongsTo(User::class,'supplier_id');
    }

    public function Design()
    {
        return $this->belongsTo(DesignChild::class,'design_id')->with('DesignParent');
    }

    public function Category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }

    public function UserToko()
    {
        return $this->hasOne(UserToko::class,'shop_id','id');
    }
}
