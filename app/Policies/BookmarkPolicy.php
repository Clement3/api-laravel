<?php

namespace App\Policies;

use App\Item;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookmarkPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the given item can be bookmarked by the user.
     *
     * @param  \App\User  $user
     * @param  \App\Bookmark  $item
     * @return bool
     */
    public function create(User $user, Item $item)
    {
        return $user->id !== $item->user_id && $user->bookmarks()->where('item_id', $item->id)->doesntExist();
    }
}
