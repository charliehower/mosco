<?php

use Illuminate\Database\Seeder;

class TitleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('titles')->insert([
            [
                'name' => '辅导员',//id=1
                'rank' => 1,
            ],
            [
                'name' => '大班长',//id=2
                'rank' => 2,
            ],
            [
                'name' => '大班团支书',//id=3
                'rank' => 3,
            ],

            [
                'name' => '小班长',//id=4
                'rank' => 5,
            ],
            [
                'name' => '小班团支书',//id=5
                'rank' => 6,
            ]
        ]);
        DB::table('titles')->insert([
            [
                'name' => '大班生活委员',//id=6
                'rank' => 4,
                'dy'   => 'sheng',
            ],
            [
                'name' => '小班生委',//id=7
                'rank' => 7,
                'dy'   => 'sheng',
            ],

            [
                'name' => '大班心理委员',//id=8
                'rank' => 4,
                'dy'   => 'xinli',
            ],
            [
                'name' => '小班心委',//id=9
                'rank' => 7,
                'dy'   => 'xinli',
            ],

            [
                'name' => '大班科技委员',//id=10
                'rank' => 4,
                'dy'   => 'keji',
            ],
            [
                'name' => '小班科委',//id=11
                'rank' => 7,
                'dy'   => 'keji',
            ],

            [
                'name' => '大班学习委员',//12
                'rank' => 4,
                'dy'   => 'xuexi',
            ],

            [
                'name' => '小班学委',//13
                'rank' => 7,
                'dy'   => 'xuexi',
            ],
            [
                'name' => '大班宣传委员',//14
                'rank' => 4,
                'dy'   => 'xuan',
            ],
            [
                'name' => '小班宣委',//15
                'rank' => 7,
                'dy'   => 'xuan',
            ],

            [
                'name' => '大班组织委员',//16
                'rank' => 4,
                'dy'   => 'zu',
            ],
            [
                'name' => '小班组委',//17
                'rank' => 7,
                'dy'   => 'zu',
            ],

            [
                'name' => '大班文体委员',//18
                'rank' => 4,
                'dy'   => 'ti',
            ],
            [
                'name' => '小班文体委',//19
                'rank' => 7,
                'dy'   => 'ti',
            ],
            [
                'name' => '宿舍长',//id=20
                'rank' => 10,
                'dy'   => 'sushe',
            ],
        ]);
    }
}
