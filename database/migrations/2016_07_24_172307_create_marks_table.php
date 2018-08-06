<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cuy_id')->unsigned();
            $table->integer('subject_id')->unsigned();
            $table->string('status',45);
            $table->float('mark')->nullable();
            $table->string('date');
            $table->softDeletes();
            $table->timestamps();


            $table->foreign('cuy_id')
                ->references('id')
                ->on('cuys')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('subject_id')
                ->references('id')
                ->on('subjects')
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
        Schema::drop('marks');
    }
}
