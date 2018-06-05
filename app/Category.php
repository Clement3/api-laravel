<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    
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
     * Get all items for one category
     */    
    public function items()
    {
        return $this->hasManyThrough('App\Item', 'App\Category', 'parent_id', 'category_id', 'id', 'id');
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

    public function parent() 
    {
        return $this->hasOne('App\Category', 'id', 'parent_id');
    }
}
