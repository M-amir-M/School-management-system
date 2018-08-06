<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLibrariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('libraries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('bookName');
            $table->string('bookDescription')->nullable();
            $table->string('bookAuthor');
            $table->string('bookType');//electronic or physical
            $table->string('bookPrice')->nullable();
            $table->text('bookPath')->nullable();//download link for electronic books and code of physical books
            $table->integer('number')->nullable();//number of book
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('libraries');
    }
}
