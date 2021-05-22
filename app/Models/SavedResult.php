<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Laravel\Scout\Searchable;

class SavedResult extends Model
{
    use HasFactory, Searchable;

    public function getParamsAttribute()
    {
        return json_decode($this->attributes['params'], true);
    }
    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return array_merge($this->params, [
            'output' => $this->output,
            'user_id' => $this->user_id,
        ]);
    }

    /**
     * Get the name of the index associated with the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return App::environment('production') ? 'saved_results' : 'dev_saved_results';
    }
}
