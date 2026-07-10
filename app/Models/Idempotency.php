<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Idempotency extends Model
{
    protected $fillable = [
        'idempotency_key',
        'status',
        'redirect_url',
        'key',
        'message'
    ];
}
