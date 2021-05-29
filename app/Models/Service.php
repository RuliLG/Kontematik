<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Laravel\Scout\Searchable;

class Service extends Model
{
    use HasFactory, Searchable;

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id', 'id');
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

    public function getTagsAttribute()
    {
        return array_filter(array_map('trim', explode(',', $this->attributes['tags'])));
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            'slug' => $this->slug,
            'category' => $this->category->name,
            'category_description' => $this->category->description,
            'name' => $this->name,
            'tags' => $this->tags,
        ];
    }

    /**
     * Modify the query used to retrieve models when making all of the models searchable.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function makeAllSearchableUsing($query)
    {
        return $query->with('category');
    }

    /**
     * Get the name of the index associated with the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return App::environment('production') ? 'services' : 'dev_services';
    }

    /**
     * Determine if the model should be searchable.
     *
     * @return bool
     */
    public function shouldBeSearchable()
    {
        return $this->is_enabled === 1;
    }
}
