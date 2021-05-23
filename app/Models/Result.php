<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function getResponseAttribute()
    {
        return json_decode($this->attributes['response'], true);
    }

    public function getParamsAttribute()
    {
        return json_decode($this->attributes['params'], true);
    }

    public function getWebflowUrlAttribute()
    {
        if (!$this->webflow_share_uuid) {
            return null;
        }

        return 'https://www.kontematik.com/ai-copywriting/' . $this->webflow_share_uuid;
    }
}
