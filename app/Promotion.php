<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $fillable = ['text', 'image_url', 'restaurant_id', 'approve_at', 'approve_by'];

    protected $appends = ['created_at_text', 'approve_at_text'];

    public function getCreatedAtTextAttribute()
    {
        return $this->created_at ? \Carbon\Carbon::parse($this->created_at)->format('d/m/Y H:i:s') : '-';
    }

    public function getApproveAtTextAttribute()
    {
        return $this->approve_at ? \Carbon\Carbon::parse($this->approve_at)->format('d/m/Y H:i:s') : '-';
    }
}