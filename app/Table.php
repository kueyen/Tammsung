<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class table extends Model
{
    protected $appends = ['qr_url'];

    public function getQrUrlAttribute()
    {
        $liffUrl = 'https://liff.line.me/1654579616-VqmeqPmM';
        return "https://api.qrserver.com/v1/create-qr-code/?data={$liffUrl}?key={$this->key}";
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}