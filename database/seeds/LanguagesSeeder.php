<?php

use Illuminate\Database\Seeder;

class LanguagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('languages')->insert([
            [
                'name' => 'English',
                'flag' => 'indian.png'
            ],
            [
                'name' => 'Hindi',
                'flag' => 'uk.jpg'
            ],
            [
                'name' => 'German',
                'flag' => 'american.png'
            ],
            [
                'name' => 'Russian',
                'flag' => 'uk.png'
            ],
            [
                'name' => 'Urdu',
                'flag' => 'indian.png'
            ]
        ]);
    }
}
