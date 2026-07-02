<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Payment extends Model
{
    use SoftDeletes;
    protected $casts = [
        'amount' =>  "decimal:2",
        'payment_date'=> 'date'
    ];
    protected $fillable = [
        'amount',
        'order_id',
        'payment_method',
        'payment_date',
    ];
    public function order(){
        return $this->belongsTo(Order::class, 'order_id');
    }
}
