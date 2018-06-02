<?php

namespace App;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use Searchable, SoftDeletes;
    
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
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            'title' => $this->title,
            'body' => $this->body
        ];
    }

    /**
     * Scout - shouldBeSearchable
     * 
     * @return boolean
     */
    public function shouldBeSearchable()
    {
        return $this->isActive();
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

    public function isActive()
    {
        return is_null($this->selled_at) && !is_null($this->verified_at) && now() <= $this->expired_at;
    }
}
