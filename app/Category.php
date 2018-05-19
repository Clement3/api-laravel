<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug', 'name'
    ];

    /**
     * Get the route key name for Laravel.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get the childrens for one category.
     */
    public function childrens()
    {
        return $this->hasMany('App\Category', 'parent_id', 'id');
    }

    /**
     * Scope a query to only include parents categories.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }

    public function items()
    {
        if (!is_null($this->parent_id)) {
            return $this->hasMany('App\Item', 'child_category_id', 'id');
        }

        return $this->hasMany('App\Item', 'parent_category_id', 'id');
    }
}
