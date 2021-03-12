<?php

use Illuminate\Database\Seeder;

class contents_table_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('contents')->insert([
            [
                'title' => 'About Us',
                'slug' => 'about-us',
                'description' => '<p> About Us</p>'
            ],
            [
                'title' => 'Terms & Conditions',
                'slug' => 'terms',
                'description' => '<p> Terms & Conditions </p>'
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy',
                'description' => '<p> Privacty Policy </p>'
            ]
        ]);
    }
}
