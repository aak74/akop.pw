<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppPortalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_portals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('portal_id')->unsigned();
            $table->integer('app_id')->unsigned();
            $table->text('settings');
            $table->timestamps();

        });

        Schema::table('app_portals', function (Blueprint $table) {
            $table->foreign('portal_id')
                  ->references('id')->on('portals')
                  ->onDelete('cascade');

            $table->foreign('app_id')
                  ->references('id')->on('apps')
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
        Schema::drop('app_portals');
    }
}
