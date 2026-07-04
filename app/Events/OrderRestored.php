<?php

namespace App\Events;


use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Database\Eloquent\Model;
class OrderRestored
{
    use Dispatchable;

    /**
     * Create a new event instance.
     */

    public $orderable;
    public function __construct(Model $orderable)
    {
        $this->orderable = $orderable;
    }

}
