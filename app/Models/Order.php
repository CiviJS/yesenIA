<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Order extends Model
{
    use SoftDeletes;

    protected $casts = [
        'total_amount' => 'decimal:2'
    ];

    protected $fillable = [
        'client_id',
        'total_amount',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function items()
    {
        return $this->morphMany(OrderItem::class, 'orderable')->withTrashed();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'order_id');
    }

    public function getStatus()
    {
        return !$this->trashed();
    }

  
    protected function paidAmount(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->payments->sum('amount'),
        );
    }

  
    protected function remainingAmount(): Attribute
    {
        return Attribute::make(
            get: fn() => max(0, $this->total_amount - $this->paid_amount),
        );
    }
}