<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use SoftDeletes;
    
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
        return $this->belongsTo('App\User')->withTrashed();
    }

    /**
     * Get the parent category for the item.
     */
    public function parentCategory()
    {
        return $this->hasOne('App\Category', 'id', 'parent_category_id');
    }

    /**
     * Get the children category for the item.
     */
    public function childCategory()
    {
        return $this->hasOne('App\Category', 'id', 'child_category_id');
    } 
    
    /**
     * Count how many bookmarks for the item.
     */
    public function countBookmarks()
    {
        return $this->hasMany('App\Bookmark')->count();
    }
}
