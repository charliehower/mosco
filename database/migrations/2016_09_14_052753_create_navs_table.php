<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNavsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('navs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name','200')->comment("分数项目名");
            $table->string('ename','200')->comment("英文");
            $table->string('type','200')->comment("打分类型");
            $table->string('detail','3000')->comment("备注");
            $table->float('begin')->default(6)->comment("打分起点");
            $table->float('add')->default(1)->comment("增加值");
            $table->integer('end')->default(10)->comment("打分终点");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('navs');
    }
}
