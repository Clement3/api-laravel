<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $id = 1;
        
        for ($i = 0; $i <= 5; $i++) {
            DB::table('categories')->insert([
                'slug' => 'parent-categorie-'.$i,
                'name' => 'Parent Catégorie '.$i
            ]);
        }

        for ($i = 0; $i <= 5; $i++) {
            DB::table('categories')->insert([
                'slug' => 'child-categorie-'.$i,
                'name' => 'Child Catégorie '.$i,
                'parent_id' => $id++
            ]);
        }        
    }
}
