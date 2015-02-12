<?php

use Illuminate\Database\Migrations\Migration;

class CreateBlacklistsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blacklists', function($table)
        {
            $table->increments('id');
            $table->string('title');
            $table->text('element');
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
        Schema::drop('blacklists');
    }

}