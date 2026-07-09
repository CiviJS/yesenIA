<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class OrderItem extends Model
{
    use SoftDeletes;
    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2'
    ];
    protected $fillable = [
        'product_id',
        'quantity',
        'unit_price',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id')->withTrashed();
    }

    public function orderable()
    {
        return $this->morphTo();
    }
    public function getStatus()
    {
        return !$this->trashed();
    }

}
