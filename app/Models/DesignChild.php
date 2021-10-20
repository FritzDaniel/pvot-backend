<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DesignChild extends Model
{
    use HasFactory;

    protected $table = 'design_child';
    protected $primaryKey ='id';
    protected $fillable = [
        'design_id',
        'designName',
        'designImage'
    ];
    public $timestamps = true;

    public function DesignParent()
    {
        return $this->belongsTo(Design::class,'design_id');
    }
}
