<?php

use Illuminate\Database\Seeder;

class EthnicitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ethnicities')->insert([
            [
                'name' => 'White'
            ],
            [
                'name' => 'Black'
            ],
            [
                'name' => 'Middle Eastern'
            ],
            [
                'name' => 'Asian'
            ],
            [
                'name' => 'Hispanic / Latina'
            ],
            [
                'name' => 'Pacific Islander'
            ],
            [
                'name' => 'Mixed'
            ],
        ]);
    }
}
