<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Food extends Model
{

    protected $appends = ['real_price'];
    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function getRealPriceAttribute()
    {

        if (!$this->discount) {
            return $this->price;
        }
        $percentage = $this->discount;
        $totalWidth = $this->price;

        $new_width = ($percentage / 100) * $totalWidth;
        return number_format((float)$this->price - $new_width, 2, '.', '');
    }
}