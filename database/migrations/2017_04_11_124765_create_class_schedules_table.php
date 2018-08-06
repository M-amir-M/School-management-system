<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('clase_id')->unsigned();
            $table->integer('subject_id')->unsigned();
            $table->integer('year_id')->unsigned();
            $table->string('day_week');
            $table->string('start_time');
            $table->string('end_time');
            $table->softDeletes();
            $table->timestamps();


            $table->foreign('clase_id')
                ->references('id')
                ->on('clases')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('subject_id')
                ->references('id')
                ->on('subjects')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('year_id')
                ->references('id')
                ->on('years')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('class_schedules');
    }
}
