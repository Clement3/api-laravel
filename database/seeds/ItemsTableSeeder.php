<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 11; $i++) {
            DB::table('items')->insert([
                'slug' => 'item-'.$i,
                'price' => 10 * $i,
                'title' => 'Item '.$i,
                'body' => 'Contenu de l\'item',
                'user_id' => $i,
                'parent_category_id' => 1,
                'child_category_id' => 7,
                'created_at' => now(),
                'verified_at' => now(),
                'expired_at' => \Carbon\Carbon::parse(now())->addDays(30)
            ]);
        }
    }
}
