<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Food;

class Restaurant extends Model
{

    protected $fillable = ['name', 'profile_url', 'description', 'key'];

    protected $appends = ['created_at_text'];
    public function getCreatedAtTextAttribute()
    {
        return $this->created_at ? \Carbon\Carbon::parse($this->created_at)->format('d/m/Y H:i:s') : '-';
    }
    public function tables()
    {
        return $this->hasMany('App\Table');
    }

    public function categories()
    {
        return $this->hasMany('App\Category');
    }

    public function food_recomments()
    {
        return $this->hasManyThrough('App\Food', 'App\Category')->where('is_recommend', 1);
    }

    public function foods()
    {
        return $this->hasManyThrough('App\Food', 'App\Category');
    }
}