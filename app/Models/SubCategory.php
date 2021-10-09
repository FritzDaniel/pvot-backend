<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;

    protected $table = 'sub_category';
    protected $primaryKey ='id';
    protected $fillable = [
        'category_id',
        'name',
    ];
    public $timestamps = true;

    public function SubChild()
    {
        return $this->hasMany(SubChildCategory::class, 'sub_category_id', 'id');
    }
}
