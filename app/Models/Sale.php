<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Sale extends Model
{
    use SoftDeletes;

    protected $casts = [];
    protected $fillable = [
        
    ];
  
    public function items()
    {
        return $this->morphMany(OrderItem::class , 'orderable');
    }

}
