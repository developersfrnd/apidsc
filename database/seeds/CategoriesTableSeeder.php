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
        DB::table('categories')->insert([
            [
                'name' => 'Girls'
            ],
            [
                'name' => 'Couples'
            ],
            [
                'name' => 'Mumbai'
            ],
            [
                'name' => 'Delhi'
            ],
            [
                'name' => 'Higo Live'
            ],
            [
                'name' => 'Popular'
            ],
        ]);
    }
}