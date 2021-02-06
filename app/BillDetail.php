<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BillDetail extends Model
{
    protected $fillable = ['bill_id', 'user_id', 'food_id', 'price', 'price_sum', 'amount', 'status'];
    protected $appends = ['created_at_text', 'amount_v'];

    public function getCreatedAtTextAttribute()
    {
        return $this->created_at ? \Carbon\Carbon::parse($this->created_at)->format('d/m/Y H:i:s') : '-';
    }

    public function getAmountVAttribute()
    {
        return $this->amount;
    }
    public function food()
    {
        return $this->belongsTo(Food::class);
    }

    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }
}