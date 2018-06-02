<?php

namespace App\Observers;

use App\Item;
use Vinkla\Hashids\Facades\Hashids;

class ItemObserver
{
    /**
     * Listen to the Item creating event.
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