<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'item_id'
    ];

    /**
     * Get the item for the bookmark.
     */
    public function item()
    {
        return $this->belongsTo('App\Item')->withTrashed();
    }

    /**
     * Get the user for the bookmark.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
