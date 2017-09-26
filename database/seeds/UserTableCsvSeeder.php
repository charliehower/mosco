<?php

use Flynsarmy\CsvSeeder\CsvSeeder;

class UserTableCsvSeeder extends CsvSeeder
{
    /**
     * Run the database seeds.
     *
     *
     */

    public function __construct()
    {
        $this->table = 'users';
        $this->filename = base_path().'/database/seeds/csvs/user.csv';
        $this->hashable = 'password';
        $this->mapping = [
    	    0 => 'id',
    	    1 => 'name',
            2 => 'daban',
            3 => 'class',
            4 => 'password',
       ];
    }

    public function run()
    {
        // Recommended when importing larger CSVs
        //DB::disableQueryLog();

        // Uncomment the below to wipe the table clean before populating
        DB::table($this->table)->truncate();
        parent::run();
    }
}
