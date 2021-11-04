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
        'uuid',
        'supplier_id',
        'productName',
        'productDesc',
        'productStock',
        'productPrice',
        'productPicture',
        'productCategory',
        'productRevenue',
        'showPrice',
        'status'
    ];
    public $timestamps = true;

    public function productSold()
    {
        $data = $this->hasMany(Transaction::class,'product_id','id');
        return $data;
    }

    public function productCategory()
    {
        return $this->belongsTo(Category::class,'productCategory');
    }

    public function productVariant()
    {
        return $this->hasMany(ProductVariant::class,'product_id','uuid')->with('Tipe');
    }

}
