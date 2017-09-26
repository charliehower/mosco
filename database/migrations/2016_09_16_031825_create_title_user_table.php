<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTitleUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('title_user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->comment("学号");
            $table->integer('title_id')->comment("头衔")->nullable();;
            $table->string('title',50)->comment("职位名称");
            $table->string('rank',10)->comment("A为原始得分，B-0.1，C-0.2");
            $table->float('score')->comment("本职位得分");
            $table->float('time')->comment("时长系数");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('title_user');
    }
}
