<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkbooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workbooks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('subject_id')->unsigned();
            $table->integer('cuy_id')->unsigned();
            $table->float('mos1')->nullable();
            $table->float('pay1')->nullable();
            $table->float('mos2')->nullable();
            $table->float('pay2')->nullable();
            $table->softDeletes();
            $table->timestamps();


            $table->foreign('subject_id')
                ->references('id')
                ->on('subjects')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('cuy_id')
                ->references('id')
                ->on('cuys')
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
        Schema::drop('workbooks');
    }
}
