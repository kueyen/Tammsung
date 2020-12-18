<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class table extends Model
{
    use SoftDeletes;

    protected $appends = ['qr_url', 'created_at_text', 'updated_at_text',];
    protected $fillable = ['name', 'key', 'restaurant_id'];

    public function getQrUrlAttribute()
    {
        $liffUrl = 'https://liff.line.me/1654579616-VqmeqPmM';
        return "https://api.qrserver.com/v1/create-qr-code/?data={$liffUrl}?key={$this->key}";
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function latest_bills()
    {
        return $this->hasOne(Bill::class)->whereStatus(1)->latest();
    }



    public function getCreatedAtTextAttribute()
    {
        return $this->created_at ? \Carbon\Carbon::parse($this->created_at)->format('d/m/Y H:i:s') : '-';
    }
    public function getUpdatedAtTextAttribute()
    {
        return $this->created_at ? \Carbon\Carbon::parse($this->created_at)->format('d/m/Y H:i:s') : '-';
    }

    public function foods()
    {
        return $this->hasMany('App\Food');
    }
}