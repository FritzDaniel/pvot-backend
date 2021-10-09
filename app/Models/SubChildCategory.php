<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubChildCategory extends Model
{
    use HasFactory;

    protected $table = 'sub_child_category';
    protected $primaryKey ='id';
    protected $fillable = [
        'sub_category_id',
        'name',
    ];
    public $timestamps = true;
}
