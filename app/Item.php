<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug', 
        'title', 
        'body', 
        'parent_category_id', 
        'child_category_id'
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
     * Get the user that owns the item.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Get the parent category that owns the item.
     */
    public function parentCategory()
    {
        return $this->hasOne('App\Category', 'id', 'parent_category_id');
    }

    /**
     * Get the children category that owns the item.
     */
    public function childCategory()
    {
        return $this->hasOne('App\Category', 'id', 'child_category_id');
    }                  
}
