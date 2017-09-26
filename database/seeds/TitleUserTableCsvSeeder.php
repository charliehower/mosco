<?php

use Flynsarmy\CsvSeeder\CsvSeeder;

class TitleUserTableCsvSeeder extends CsvSeeder
{
    /**
     * Run the database seeds.
     *
     *
     */

    public function __construct()
    {
        $this->table = 'title_user';
        $this->filename = base_path().'/database/seeds/csvs/title_user.csv';
        $this->mapping = [
            0 => 'user_id',
    	    1 => 'title_id',
            2 => 'title',
            3 => 'rank',
            4 => 'score',
            5 => 'time'
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
