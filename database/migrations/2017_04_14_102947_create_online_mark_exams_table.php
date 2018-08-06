<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOnlineMarkExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('online_mark_exams', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('exam_id')->unsigned();
            $table->integer('cuy_id')->unsigned();
            $table->text('answers');
            $table->integer('mark');
            $table->string('date');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('exam_id')
                ->references('id')
                ->on('online_exams')
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
        Schema::dropIfExists('online_mark_exams');
    }
}
