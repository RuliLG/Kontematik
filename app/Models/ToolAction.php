<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToolAction extends Model
{
    use HasFactory;

    public function getActionsAttribute()
    {
        return json_decode($this->attributes['actions'], true);
    }
}
