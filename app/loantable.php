<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class loantable extends Model
{
    protected $fillable = [
        'user_id', 'amount', 'term', 'next_payment_date', 'status'
    ];
}
