<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BillDetail extends Model
{
    protected $fillable = ['bill_id', 'user_id', 'food_id', 'price', 'price_sum', 'amount'];

    public function food()
    {
        return $this->belongsTo(Food::class);
    }
}