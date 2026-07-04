<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $casts = [
        'total_amount' => 'decimal:2'
    ];
    protected $fillable = [
        'client_id',
        'total_amount',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class , 'client_id');
    }
    public function items()
    {
        return $this->morphMany(OrderItem::class, 'orderable');
    }
    public function payments()
    {
        return $this->hasMany(Payment::class, 'order_id');
    }
}
