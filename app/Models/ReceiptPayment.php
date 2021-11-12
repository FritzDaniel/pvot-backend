<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiptPayment extends Model
{
    use HasFactory;

    protected $table = 'receipt_payment';
    protected $primaryKey ='id';
    protected $fillable = ['payment_id','receiptImage','receiptNumber'];
    public $timestamps = true;

    public function Payment()
    {
        return $this->belongsTo(Payment::class,'payment_id','external_id');
    }
}
