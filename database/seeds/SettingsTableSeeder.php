<?php

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            [
                'pricePerCredit' => '1',
                'minCreditPurchase' => '1',
                'adminCommissionPercent' => '1',
            ],
        ]);
    }
}
