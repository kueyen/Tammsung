<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    protected $fillable = ['table_id', 'price_sum', 'discount', 'status', 'coupon_id'];

    public function details()
    {
        return $this->hasMany(BillDetail::class);
    }

    public function detail_s()
    {
        return $this->hasMany(BillDetail::class)->whereStatus(2);
    }
    public function table()
    {
        return $this->belongsTo(Table::class);
    }
}