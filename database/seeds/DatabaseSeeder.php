<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CategoriesTableSeeder::class);
        $this->call(EthnicitiesTableSeeder::class);
        $this->call(LanguagesSeeder::class);
        $this->call(contents_table_seeder::class);
        $this->call(SettingsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
    }
}
