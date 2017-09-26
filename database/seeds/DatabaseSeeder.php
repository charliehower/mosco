<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call('UserTableCsvSeeder');
        $this->call('NavTableSeeder');
        $this->call('ActivityTableSeeder');
        $this->call('TitleTableSeeder');
        $this->call('TitleUserTableCsvSeeder');
    }
}
