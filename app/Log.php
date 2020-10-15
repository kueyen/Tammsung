<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable = ['message', 'reply_token', 'text', 'post_body'];

    protected $casts = [
        'post_body' => 'array',
    ];

    public function getPostBody()
    {
        return json_encode($this->post_body);
    }
}
