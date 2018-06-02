<?php

namespace App\Observers;

use App\Item;

class ItemObserver
{
    /**
     * Listen to the Item creating event.
     * Generate a random a slug
     * 
     * @param  \App\Item  $item
     * @return void
     */
    public function creating(Item $item)
    {
        $flag = true;

        while ($flag) {
            $random = str_random(10);
            $query = Item::where('slug', $random)->doesntExist();

            if ($query) {
                $flag = false;
            }
        }

        $item->slug = $random;
    }
}