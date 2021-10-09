<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'category';
    protected $primaryKey ='id';
    protected $fillable = [
        'name',
        'logo'
    ];
    public $timestamps = true;

    public function Child()
    {
        return $this->hasMany(SubCategory::class, 'category_id', 'id');
    }
}
