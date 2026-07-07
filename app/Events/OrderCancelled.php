<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Database\Eloquent\Model;

class OrderCancelled
{
    use Dispatchable;

    public $orderable;
    public function __construct(Model $orderable)
    {
        
       
        $this->orderable = $orderable;
    }

 
 
}
