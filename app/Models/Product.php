<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'product';
    protected $primaryKey ='id';
    protected $fillable = [
        'user_id',
        'productName',
        'productDesc',
        'productQty',
        'productPrice',
        'productRevenue',
        'showPrice'
    ];
    public $timestamps = true;

    public function userDetail()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function productPhoto()
    {
        return $this->hasMany(ProductPicture::class,'product_id','id');
    }

    public function productSold()
    {
        $data = $this->hasMany(Transaction::class,'product_id','id');
        return $data;
    }

    public function productCategory()
    {
        return $this->hasMany(ProductCategory::class,'product_id','id');
    }

    public function productVariant()
    {
        return $this->hasMany(ProductVariant::class,'product_id','id')->with('Tipe');
    }
}
