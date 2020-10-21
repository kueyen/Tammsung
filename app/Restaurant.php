<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Food;

class Restaurant extends Model
{

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