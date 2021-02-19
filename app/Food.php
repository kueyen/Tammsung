<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Food extends Model
{

    use SoftDeletes;
    protected $fillable = ['name', 'description', 'image_url', 'is_recommend', 'price', 'discount', 'category_id'];
    protected $appends = ['real_price', 'created_at_text'];
    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function bill_details()
    {
        return $this->hasMany('App\BillDetail');
    }

    public function getCreatedAtTextAttribute()
    {
        return $this->created_at ? \Carbon\Carbon::parse($this->created_at)->format('d/m/Y H:i:s') : '-';
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