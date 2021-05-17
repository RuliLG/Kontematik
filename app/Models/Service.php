<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class);
    }

    public function fields()
    {
        return $this->hasMany(ServiceField::class)
            ->orderBy('order', 'ASC');
    }

    public function prompts()
    {
        return $this->hasMany(ServicePrompt::class);
    }
}
